<?php
session_start();    
require "app/models/StoreSections.php";

// EXPERIMENTAL

// $section_data = [
//     "section_image" => "image_path.jpg",
//     "section_name" => "Electronics",
//     "section_description" => "All kinds of electronic devices.",
//     "section_short_description" => "Electronics",
//     "section_tags_description" => "gadgets, devices, tech"
// ];

// $update_section_data = [
//     "section_image" => "image_path.jpg",
//     "section_name" => "Electronics3",
//     "section_description" => "All kinds of electronic devices will be updated again.",
//     "section_short_description" => "Electronics Update 3",
//     "section_tags_description" => "gadgets, devices, tech, update 3"
// ];

// $whereClause = [
//     "section_name" => "Electronics3",
// ];
// echo $StoreSectionInit->update($update_section_data, ['id' => 2]);

// $StoreSectionInit->create($section_data);
// $StoreSectionInit->update($update_section_data, $whereClause);

// print_r($StoreSectionInit->read($whereClause));


// process create section form
if (isset($_POST['addSectionForm']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireRole(['admin', 'worker']);

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        header('Location: /storesection');
        exit;
    }

    // Check if all required fields are provided
    if (isset($_POST['section_name'], $_POST['section_description'], $_POST['section_short_description'], $_POST['section_tags_description']) && isset($_FILES['section_images'])) {

        $target_dir = "assets/img/sections/";  // Directory to store images
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 5 * 1024 * 1024;  // Max file size in bytes (5MB)
        $max_images = 20;  // Maximum number of images to upload
        $uploaded_images = [];

        // Process each uploaded file
        for ($i = 0; $i < min(count($_FILES['section_images']['name']), $max_images); $i++) {
            $imageFileType = strtolower(pathinfo($_FILES['section_images']['name'][$i], PATHINFO_EXTENSION));
            $file_size = $_FILES['section_images']['size'][$i];
            $file_tmp = $_FILES['section_images']['tmp_name'][$i];

            // Validation for file type
            if (!in_array($imageFileType, $allowed_types)) {
                echo "Only JPG, JPEG, PNG, & GIF files are allowed.";
                continue;
            }

            // Validation for file size (max 5MB)
            if ($file_size > $max_file_size) {
                echo "Sorry, your file is too large. Maximum size is 5MB.";
                continue;
            }

            // Validate the image using getimagesize to ensure it's a valid image file
            $check = getimagesize($file_tmp);
            if ($check === false) {
                echo "File is not an image.";
                continue;
            }

            // Generate a unique filename using md5 hash
            $random_int = rand(1000, 9999);
            $current_time = time();
            $random_str = bin2hex(random_bytes(8)); // Generate a random string
            $unique_filename = md5(uniqid() . $random_int . $current_time . $random_str) . "." . $imageFileType;

            // Full file path to save the file
            $target_file = $target_dir . $unique_filename;

            // Check if the file already exists (though highly unlikely with md5 hashed filenames)
            if (file_exists($target_file)) {
                echo "Sorry, file already exists: " . $unique_filename;
                continue;
            }

            // Proceed with file upload
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Add the successfully uploaded image path to the array
                $uploaded_images[] = $target_file;
            } else {
                echo "Error uploading the image: " . $_FILES['section_images']['name'][$i];
            }
        }

        // If at least one image was successfully uploaded
        if (!empty($uploaded_images)) {
            // Store the form data along with the uploaded images
            $section_data = [
                "section_name" => htmlspecialchars($_POST['section_name']),
                "section_image" => json_encode($uploaded_images), // Store the images as JSON
                "section_description" => htmlspecialchars($_POST['section_description']),
                "section_short_description" => htmlspecialchars($_POST['section_short_description']),
                "section_tags_description" => htmlspecialchars($_POST['section_tags_description']),
            ];

            // Initialize the database connection and StoreSection class
            // $DatabaseModel = new Database(); // Assuming you have a DB class
            // $StoreSection = new StoreSection($DatabaseModel);

            // Check if section already exists and create it if it doesn't
            if ($storeSection->create($section_data)) {
                header('Location: /storesection');
            } else {
                header('Location: /storesection');
            }
        } else {
            echo "No valid images were uploaded!";
        }
    } else {
        echo "All fields are required!";
    }
}

// Process update section form
if (isset($_POST['updateSectionForm']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireRole(['admin', 'worker']);

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        header('Location: /storesection');
        exit;
    }

    // Check if all required fields are provided
    if (isset($_POST['section_id'], $_POST['section_name'], $_POST['section_description'], $_POST['section_short_description'], $_POST['section_tags_description'])) {

        // Retrieve the current section data from the database
        $section_id = $_POST['section_id'];
        $section = $storeSection->read(["id" => $section_id]);

        // Prepare updated data
        $updated_data = [
            "section_name" => htmlspecialchars($_POST['section_name']),
            "section_description" => htmlspecialchars($_POST['section_description']),
            "section_short_description" => htmlspecialchars($_POST['section_short_description']),
            "section_tags_description" => htmlspecialchars($_POST['section_tags_description']),
        ];

        // Check if the user uploaded a new image
        if (!empty($_FILES['section_image']['name'])) {
            // File handling variables
            $target_dir = "assets/img/sections/";  // Directory to store images
            $imageFileType = strtolower(pathinfo($_FILES["section_image"]["name"], PATHINFO_EXTENSION));
            $file_size = $_FILES["section_image"]["size"];

            // Validation for file type
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowed_types)) {
                echo "Only JPG, JPEG, PNG, & GIF files are allowed.";
                exit;
            }

            // Validation for file size (max 5MB)
            if ($file_size > 5 * 1024 * 1024) {
                echo "Sorry, your file is too large. Maximum size is 5MB.";
                exit;
            }

            // Validate the image
            $check = getimagesize($_FILES["section_image"]["tmp_name"]);
            if ($check === false) {
                echo "File is not an image.";
                exit;
            }

            // Generate a unique filename for the new image
            $random_str = bin2hex(random_bytes(8));
            $unique_filename = md5(uniqid() . time() . $random_str) . "." . $imageFileType;
            $target_file = $target_dir . $unique_filename;

            // Check if the file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                exit;
            }

            // Proceed with file upload
            if (move_uploaded_file($_FILES["section_image"]["tmp_name"], $target_file)) {
                // File successfully uploaded, now update the image in the array

                // Get the current images from the database (assuming it's stored as JSON)
                $current_images = json_decode($section['section_image'], true);
                
                // Check if image_index is provided
                if (isset($_POST['image_index'])) {
                    $image_index = $_POST['image_index'][0] - 1; // Convert to zero-based index

                    // Check if the index is valid
                    if ($image_index >= 0 && $image_index < count($current_images)) {
                        // Update the specific image in the array
                        $current_images[$image_index] = $target_file;

                        // Update the section images
                        $updated_data['section_image'] = json_encode($current_images);
                    }
                }
            } else {
                echo "Error uploading the image!";
            }
        }

        // Update the section details in the database
        if ($storeSection->update($updated_data, ["id" => $section_id])) {
            header('Location: /storesection');
        } else {
            echo "Error updating the section!";
        }
    } else {
        echo "All fields are required!";
    }
}


if(isset($_GET["render"]) && $_GET["render"] == "edit" && isset($_GET["section_id"]) && !empty($_GET["section_id"])){
    $section_id = user_input_sanitize($_GET["section_id"]);
    $whereClause = [
        "id"=> $section_id
    ];
    $section = $storeSection->read($whereClause);
    // print_r($section);
}


if (isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["section_id"]) && !empty($_GET["section_id"]) && isset($_GET["confirmed_action"]) && $_GET["confirmed_action"] == "true") {
    $section_id = intval($_GET["section_id"]);
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
        // Fetch the section details to get the image paths (if needed)
        $section = $storeSection->read(["id" => $section_id]);

        if ($section) {
            // Delete associated images (assuming you store images in an array)
            $imageArray = json_decode($section['section_image'], true);  // Assuming images are stored in JSON format
            
            if (is_array($imageArray)) {
                foreach ($imageArray as $imagePath) {
                    $fullImagePath = __DIR__ . '/' . $imagePath;
                    if (file_exists($fullImagePath)) {
                        unlink($fullImagePath);  // Delete the image file from the server
                    }
                }
            }

            // Now delete the section from the database
            $isDeleted = $storeSection->delete(["id" => $section_id]);

            if ($isDeleted) {
                header('Location: /storesection');
                exit;
            } else {
                echo "Error deleting the section from the database.";
            }
        } else {
            echo "Section not found.";
        }
    }else{
        header("Location: /storesection");
    }

}



require "app/views/views.storesections.php";