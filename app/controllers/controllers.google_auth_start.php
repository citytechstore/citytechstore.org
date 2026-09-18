<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/config.php';

// One-time-use, verified on callback against $_SESSION — CSRF protection
// specific to the OAuth redirect flow (distinct from the site's own
// form CSRF tokens, but the same hash_equals()-checked-token pattern).
$state = bin2hex(random_bytes(32));
$_SESSION['google_oauth_state'] = $state;

// Same allowlist-only pattern as customer_login.php's own ?redirect=
// handling. Stored server-side rather than round-tripped through Google's
// redirect_uri, since Google requires that URI to match the registered
// one exactly — appending a query string to it would break the request.
$allowedRedirects = ['checkout'];
if (isset($_GET['redirect']) && in_array($_GET['redirect'], $allowedRedirects, true)) {
    $_SESSION['google_oauth_redirect'] = $_GET['redirect'];
} else {
    unset($_SESSION['google_oauth_redirect']);
}

$params = [
    'client_id' => GOOGLE_CLIENT_ID,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'openid email profile',
    'state' => $state,
    'access_type' => 'online',
    'prompt' => 'select_account',
];

header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
exit;
