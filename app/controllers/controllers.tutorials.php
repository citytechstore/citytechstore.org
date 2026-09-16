<?php
// Include the Parsedown library
require 'vendor/autoload.php'; // Ensure this path is correct based on your Composer setup

$Parsedown = new Parsedown();

// Define the mapping of sections to file paths
$howTo = [
    'measure-screen-size' => [
        'hotlink' => 'How to measure screen size',
        'md_path' => 'app/views/markdown/measure-screen-size.md'
    ]
    // Add more entries as needed
];
// Get the section parameter from the URL
$section = isset($_GET['section']) ? $_GET['section'] : '';

// Initialize variables for page content
$htmlContent = '';
$errorMessage = '';
$navigationHtml = '';

// Check the section parameter and render content accordingly
if (empty($section)) {
    // No section provided, show the introduction page
    $htmlContent = '
        <div class="container my-4">
            <h2 class="mb-3">Introduction</h2>
            <p>Welcome to the CityTechStore help section. Below are links to various help pages:</p>
            <ul class="list-group">';

    foreach ($howTo as $key => $info) {
        $htmlContent .= '<li class="list-group-item"><a href="/tutorials?section=' . htmlspecialchars($key) . '">' . htmlspecialchars($info['hotlink']) . '</a></li>';
    }

    $htmlContent .= '
            </ul>
        </div>';
} elseif (array_key_exists($section, $howTo)) {
    // Valid section, render the Markdown file
    $filePath = $howTo[$section]['md_path'];

    // Ensure the file exists and is a Markdown file
    if (file_exists($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'md') {
        // Get the contents of the Markdown file
        $markdownContent = file_get_contents($filePath);

        // Convert Markdown to HTML
        $htmlContent = $Parsedown->text($markdownContent);

        // Generate navigation buttons
        $keys = array_keys($howTo);
        $currentIndex = array_search($section, $keys);

        $prevKey = $keys[$currentIndex - 1] ?? null;
        $nextKey = $keys[$currentIndex + 1] ?? null;

        $navigationHtml = '<div class="d-flex justify-content-between my-4">';
        if ($prevKey) {
            $navigationHtml .= '<a href="/tutorials?section=' . htmlspecialchars($prevKey) . '" class="btn btn-secondary">Previous</a>';
        }
        $navigationHtml .= '<a href="/tutorials" class="btn btn-primary">Back to Tutorials</a>';
        if ($nextKey) {
            $navigationHtml .= '<a href="/tutorials?section=' . htmlspecialchars($nextKey) . '" class="btn btn-success">Next</a>';
        }
        $navigationHtml .= '</div>';
    } else {
        $errorMessage = '<p>File not found or invalid file type.</p>';
    }
} else {
    // Invalid section, show "Page Not Found"
    $errorMessage = '<p>Page not found.</p>';
}


require "app/views/views.tutorials.php";
