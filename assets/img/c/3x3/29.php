<?php
function renameFilesInDirectory($filepath) {
    // Ensure the filepath is a directory
    if (!is_dir($filepath)) {
        throw new Exception("Provided path is not a directory");
    }

    // Scan the directory for all files
    $files = array_diff(scandir($filepath), array('..', '.'));

    // Initialize a counter
    $counter = 1;

    // Loop through the files and rename them
    foreach ($files as $file) {
        $filePath = $filepath . DIRECTORY_SEPARATOR . $file;

        // Check if it's a file (not a directory)
        if (is_file($filePath)) {
            // Get the file extension
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

            // Create the new file name
            $newFileName = $counter . '.' . $fileExtension;

            // Create the full path for the new file name
            $newFilePath = $filepath . DIRECTORY_SEPARATOR . $newFileName;

            // Rename the file
            if (!rename($filePath, $newFilePath)) {
                throw new Exception("Failed to rename file: $filePath to $newFilePath");
            }

            // Increment the counter
            $counter++;
        }
    }
}

// Example usage:
try {
    renameFilesInDirectory('.');
    echo "Files renamed successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
