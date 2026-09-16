<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>Tutoritals</title>
</head>

<body>
    <?php require_once('includes/header.php'); ?>


    <div class="container my-4">
    <?php echo $navigationHtml; ?>
        <?php echo $errorMessage ? $errorMessage : $htmlContent; ?>
        <?php echo $navigationHtml; ?>
    </div>


    <?php require_once('includes/footer.php'); ?>
    <?php require_once('includes/cdn_footer.php'); ?>

</body>

</html>