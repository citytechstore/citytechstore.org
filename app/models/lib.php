<?php

function user_input_sanitize($user_input): string {
    $illegal_char =  array_merge(array_map('chr', range(0, 31)), ["&quot;", "&copy;", "&lt;", "&gt;", "&amp;", "<", ">", ":", ";", "'", "\"", "/", "\\", "|", "?", "\/", "*", "!", "#", "$", "%", "^", "&", "(", ")", "`", "~", "{", "}", "[", "]", ",", "=", "<script>", "</script>", "&lt;script&gt;", "&lt;/script&gt;", "-", "'", "true", "false", "union"]);
    return trim(str_replace($illegal_char, "", htmlspecialchars(strip_tags($user_input), ENT_QUOTES, 'UTF-8')));
}


/**
 * Require the logged-in staff user to have one of the given roles,
 * redirecting (with exit) otherwise. Usable as a one-line guard at the
 * top of any admin-only controller, or mid-file for a single branch.
 *
 * @param string|array $allowedRoles One role, or a list of allowed roles.
 */
function requireRole($allowedRoles) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_session']['role'])) {
        header('Location: ' . $basePath . '/login');
        exit();
    }

    if (!in_array($_SESSION['user_session']['role'], (array) $allowedRoles, true)) {
        header('Location: ' . $basePath . '/dashboard');
        exit();
    }
}

/**
 * Get (or create) this session's CSRF token. Reuses the existing token
 * for the lifetime of the session rather than regenerating on every call —
 * a single session-scoped token lets multiple forms on the same page
 * (e.g. the several modals on manage?type=products) all stay valid
 * together, instead of submitting one invalidating the others.
 *
 * @return string The current session's CSRF token.
 */
function generateCsrfToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Validate a submitted CSRF token against the session's token, using a
 * timing-safe comparison. Returns false (not an exception) so every call
 * site can decide its own rejection response (redirect vs. JSON).
 *
 * @param string $submittedToken The token submitted with the request.
 * @return bool True if it matches the session's token.
 */
function validateCsrfToken($submittedToken) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token']) || empty($submittedToken)) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $submittedToken);
}

/**
 * Get the base URL of the site.
 *
 * @return string The base URL of the site.
 */
function getBaseUrl() {
    // Check if the site is accessed via HTTPS
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

    // Get the server name (domain)
    $host = $_SERVER['HTTP_HOST'];

    // Construct and return the base URL
    return $protocol . '://' . $host . '/';
}

/**
 * Replace placeholders in the message with actual values.
 *
 * @param string $message The message with placeholders.
 * @param array $placeholders An associative array where keys are placeholders and values are the replacement values.
 * @return string The message with placeholders replaced by actual values.
 */
function replacePlaceholders($message, $placeholders) {
    // Iterate over the placeholders array and replace each placeholder
    foreach ($placeholders as $key => $value) {
        $message = str_replace('{{' . $key . '}}', $value, $message);
    }
    return $message;
}

// Example usage
// $sectionId = '12345';
// $placeholders = ['SECTION_ID' => $sectionId];
// $message = replacePlaceholders(WA_SECTION_MESSAGE_TEXT, $placeholders);

// echo $message;


// echo user_input_sanitize("'1=1 OR --");