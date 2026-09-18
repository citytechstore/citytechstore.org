<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/config.php';
require_once 'app/models/Database.php';
require_once 'app/models/Customer.php';

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

function googleAuthFail($basePath, $message) {
    // Preserve the ?redirect=checkout context on failure too, so a customer
    // who came from checkout and hits a Google sign-in error can still log
    // in with a password and land back at checkout, instead of losing that
    // destination entirely. Same allowlist as everywhere else this is used.
    $allowedRedirects = ['checkout'];
    $storedRedirect = $_SESSION['google_oauth_redirect'] ?? null;
    $query = 'message=' . urlencode($message);
    if ($storedRedirect !== null && in_array($storedRedirect, $allowedRedirects, true)) {
        $query .= '&redirect=' . urlencode($storedRedirect);
    }

    header('Location: ' . $basePath . '/customer-login?' . $query);
    exit;
}

// Google returned an error (e.g. the user declined) or the response is
// missing pieces we need.
if (isset($_GET['error']) || empty($_GET['state']) || empty($_GET['code'])) {
    googleAuthFail($basePath, 'Google sign-in was cancelled or failed.');
}

// CSRF-style state validation — reject if missing or mismatched, then
// unset immediately so the state can't be replayed.
$sessionState = $_SESSION['google_oauth_state'] ?? '';
unset($_SESSION['google_oauth_state']);

if ($sessionState === '') {
    // No state left in the session even though the request otherwise looks
    // like a real Google redirect (has both state and code). Most likely
    // cause: this is a stale replay of a callback URL that already
    // succeeded once — the state was already consumed and unset by that
    // earlier request (back button, refresh, or duplicate tab), not a
    // forged request. The security check itself is unchanged: we still
    // refuse to log in on this request either way, only the message differs.
    googleAuthFail($basePath, 'You may already be signed in — please check your account, or try signing in again.');
}

if (!hash_equals($sessionState, $_GET['state'])) {
    googleAuthFail($basePath, 'Google sign-in failed. Please try again.');
}

// Exchange the authorization code for an access token. Server-side only —
// GOOGLE_CLIENT_SECRET never reaches the browser.
$tokenPayload = [
    'code' => $_GET['code'],
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'grant_type' => 'authorization_code',
];

$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenPayload));
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$tokenResponse = curl_exec($ch);
$tokenCurlError = curl_error($ch);
$tokenHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($tokenResponse === false || $tokenCurlError !== '' || $tokenHttpCode !== 200) {
    // Log Google's actual error (e.g. "invalid_client", "redirect_uri_mismatch")
    // so a future failure shows the real cause instead of just the generic
    // customer-facing message. Never logs the client secret or $tokenPayload.
    if ($tokenCurlError !== '') {
        error_log('Google OAuth token exchange: cURL error: ' . $tokenCurlError);
    } else {
        $tokenErrorData = json_decode((string) $tokenResponse, true);
        $tokenError = $tokenErrorData['error'] ?? 'unknown_error';
        $tokenErrorDescription = $tokenErrorData['error_description'] ?? '(no description)';
        error_log('Google OAuth token exchange failed: HTTP ' . $tokenHttpCode . ' - ' . $tokenError . ': ' . $tokenErrorDescription);
    }

    googleAuthFail($basePath, 'Could not complete Google sign-in. Please try again.');
}

$tokenData = json_decode($tokenResponse, true);
$accessToken = $tokenData['access_token'] ?? null;

if (!$accessToken) {
    error_log('Google OAuth token exchange: HTTP 200 response had no access_token. Raw response: ' . $tokenResponse);
    googleAuthFail($basePath, 'Could not complete Google sign-in. Please try again.');
}

// Fetch the signed-in Google account's email/name.
$ch = curl_init('https://www.googleapis.com/oauth2/v3/userinfo');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$userInfoResponse = curl_exec($ch);
$userInfoHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($userInfoResponse === false || $userInfoHttpCode !== 200) {
    error_log('Google OAuth userinfo fetch failed: HTTP ' . $userInfoHttpCode . ' - ' . (string) $userInfoResponse);
    googleAuthFail($basePath, 'Could not fetch your Google account details. Please try again.');
}

$googleUser = json_decode($userInfoResponse, true);
$googleId = $googleUser['sub'] ?? null;
$email = $googleUser['email'] ?? null;
$emailVerified = $googleUser['email_verified'] ?? false;
$firstName = $googleUser['given_name'] ?? ($googleUser['name'] ?? 'Google');
$lastName = $googleUser['family_name'] ?? 'User';

if (!$googleId || !$email || !$emailVerified) {
    googleAuthFail($basePath, 'Your Google account could not be verified.');
}

$existingCustomer = $CustomerModel->findByEmail($email);

if ($existingCustomer) {
    if (empty($existingCustomer['google_id'])) {
        // Registered normally with a password — do not auto-link a
        // different login method to an existing account.
        googleAuthFail($basePath, 'An account with this email already exists. Please log in with your password instead.');
    }

    $customer = $existingCustomer;
} else {
    try {
        $customer = $CustomerModel->registerWithGoogle([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'google_id' => $googleId,
        ]);
    } catch (Exception $e) {
        googleAuthFail($basePath, 'Could not create your account. Please try again.');
    }
}

unset($customer['password']);
$_SESSION['customer_loggedin'] = true;
$_SESSION['customer_session'] = $customer;

// Same allowlist-only pattern as customer_login.php. Re-checked here even
// though this value was only ever set by our own code in
// controllers.google_auth_start.php after already validating it —
// defense in depth against a future change accidentally trusting an
// unvalidated value.
$allowedRedirects = ['checkout'];
$redirectTarget = '/';
$storedRedirect = $_SESSION['google_oauth_redirect'] ?? null;
unset($_SESSION['google_oauth_redirect']);
if ($storedRedirect !== null && in_array($storedRedirect, $allowedRedirects, true)) {
    $redirectTarget = '/' . $storedRedirect;
}

header('Location: ' . $basePath . $redirectTarget);
exit;
