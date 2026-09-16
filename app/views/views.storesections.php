<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title><?php echo APP_NAME; ?> Products Section</title>
</head>

<body>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        require_once('includes/loggedin_header.php');
    } else {
        require_once('includes/header.php');
    }
    ?>
    <?php if(!isset($_GET["render"]) || $_GET["render"] != "delete"): ?>
    <div class="container mt-5">
        <div class="alert alert-warning" role="alert">
            Spare parts cannot be returned. Please check and verify your order before making payment!
        </div>
    </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && !isset($_GET["render"])): //&& isset($_GET["render"]) && $_GET["render"] != "sectionview" && $_GET["render"] != "edit" 
    ?>
        <div class="container mt-5 pt-3 text-center">

            <div class="row align-items-center">
                <div class="col"><a href="/storesection?render=create" class="btn btn-primary">Create New Section</a></div>
                <!-- <div class="col"><a href="/storesection?render=edit&section_id=" class="btn btn-warning">Update Section</a></div> -->
                <!-- <div class="col"><a href="/storesection?render=delete" class="btn btn-danger">Delete Section</a></div> -->
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && isset($_GET["render"]) && $_GET["render"] == "create"): // create 
    ?>

        <div class="container mt-5">
            <h2>Create a New Section</h2>
            <form id="create-section-form" enctype="multipart/form-data" action="/storesection" method="POST">
                <div class="mb-3">
                    <label for="section_name" class="form-label">Section Name</label>
                    <input type="text" class="form-control" id="section_name" name="section_name" required>
                </div>
                <div class="mb-3">
                    <label for="section_image" class="form-label">Section Image</label>
                    <input type="file" name="section_images[]" multiple accept="image/*" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="section_description" class="form-label">Section Description</label>
                    <textarea class="form-control" id="section_description" name="section_description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="section_short_description" class="form-label">Short Description</label>
                    <input type="text" class="form-control" id="section_short_description" name="section_short_description" required>
                </div>
                <div class="mb-3">
                    <label for="section_tags_description" class="form-label">Tags</label>
                    <input type="text" class="form-control" id="section_tags_description" name="section_tags_description" required>
                </div>
                <button type="submit" class="btn btn-primary" name="addSectionForm">Create Section</button>
            </form>
        </div>
    <?php elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && isset($_GET["render"]) && $_GET["render"] == "edit" && isset($_GET["section_id"]) && !empty($_GET["section_id"])): // update 
    ?>
        <?php
        //make sure user is logged in as worker or admin, check if
        if ($section): ?>
            <div class="container mt-5">
                <h2>Edit Section</h2>
                <form id="edit-section-form" enctype="multipart/form-data" method="POST" action="/storesection">
                    <input type="hidden" id="section_id" name="section_id" value="<?php echo $section['id']; ?>" />
                    <div class="mb-3">
                        <label for="section_name" class="form-label">Section Name</label>
                        <input type="text" class="form-control" id="section_name" name="section_name" value="<?php echo $section['section_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="image_index">Select Image ID to Replace:</label>
                        <select class="form-select" aria-label="Select Image Number" name="image_index[]">
                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i ?>">Image <?= $i ?></option>
                            <?php endfor; ?>
                            </select>
                    </div>

                    <div class="mb-3">
                        <label for="section_image" class="form-label">Section Image</label>
                        <input type="file" class="form-control" name="section_image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="section_description" class="form-label">Section Description</label>
                        <textarea class="form-control" id="section_description" name="section_description" rows="3" required><?php echo $section['section_description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="section_short_description" class="form-label">Short Description</label>
                        <input type="text" class="form-control" id="section_short_description" name="section_short_description" value="<?php echo $section['section_short_description']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="section_tags_description" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="section_tags_description" name="section_tags_description" value="<?php echo $section['section_tags_description']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="updateSectionForm">Update Section</button>
                </form>
            </div>
        <?php else: ?>
            <p class="m-4 fs-3 text-muted text-center">Section not found!</p>
        <?php endif; ?>
    <?php elseif (isset($_GET["render"]) && $_GET["render"] == "delete" && isset($_GET["section_id"]) && !empty($_GET["section_id"])): ?>
        <!-- delete action goes here -->
 <div class="container text-center">

     <p class="fs-3">Are you sure you want to delete this section? </p>
     <a href='/storesection?action=delete&section_id=<?= $_GET["section_id"]; ?>&confirmed_action=true' class='btn btn-outline-danger'>Yes, delete</a>
     <a href='/storesection' class='btn btn-secondary'>Cancel</a>
 </div>

    <?php elseif (isUserAllowedToURI()): ?>
        <p class="m-3 text-center text-dark fs-3">You don't have the right to this page! Please login as an admin or worker first!</p>
    <?php elseif (isset($_GET["render"]) && $_GET["render"] == "sectionview" && isset($_GET["section_id"]) && !empty($_GET["section_id"])):
    ?>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
            <div class="container mt-5 pt-3 text-center mb-5">

                <div class="row align-items-center">
                    <!-- <div class="col"><a href="/storesection?render=create" class="btn btn-primary">Create Section</a></div> -->
                    <div class="col"><a href="/storesection?render=edit&section_id=<?php echo $_GET['section_id']; ?>" class="btn btn-warning">Update Section</a></div>
                    <div class="col"><a href="/storesection?render=delete&section_id=<?php echo $_GET['section_id']; ?>" class="btn btn-danger">Delete Section</a></div>
                </div>
            </div>
        <?php endif; ?>
        <?php echo $sectionView->generateSectionImageHTML($storeSection, ['id' => user_input_sanitize($_GET["section_id"])]); ?>
    <?php else: ?>
        <div class="container mt-5">
            <h2>All Sections</h2>
            <div class="row" id="sections-container">
                <?php echo $sectionView->generateHTMLView($all_sections); ?>
            </div>
        </div>
    <?php endif; ?>
</body>
<?php require_once('includes/cdn_footer.php'); ?>
<?php require_once('includes/footer.php'); ?>

</html>