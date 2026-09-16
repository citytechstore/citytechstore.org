<?php

function user_input_sanitize($user_input): string {
    $illegal_char =  array_merge(array_map('chr', range(0, 31)), ["&quot;", "&copy;", "&lt;", "&gt;", "&amp;", "<", ">", ":", ";", "'", "\"", "/", "\\", "|", "?", "\/", "*", "!", "#", "$", "%", "^", "&", "(", ")", "`", "~", "{", "}", "[", "]", ",", "=", "<script>", "</script>", "&lt;script&gt;", "&lt;/script&gt;", "-", "'", "true", "false", "union"]);
    return trim(str_replace($illegal_char, "", htmlspecialchars(strip_tags($user_input), ENT_QUOTES, 'UTF-8')));
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