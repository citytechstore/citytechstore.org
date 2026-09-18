<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require_once ('includes/cdn_header.php'); ?>
    <title>Login - City Tech Store</title>
</head>
<body>
  <?php require_once ('includes/header.php'); ?>
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mt-5 mb-5">Login to City Tech Store</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form action="login" method="post" class="mt-4">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mt-5 btn-lg w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
  <?php require_once ('includes/footer.php'); ?>
  <?php require_once ('includes/cdn_footer.php'); ?>

</body>
</html>