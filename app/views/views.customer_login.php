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
                <h2 class="text-center mt-5 mb-5">Login to Your Account</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['message']) && $_GET['message'] !== ''): ?>
                    <div class="alert alert-info"><?php echo htmlspecialchars($_GET['message']); ?></div>
                <?php endif; ?>
                <?php
                    $allowedRedirects = ['checkout'];
                    $loginFormAction = 'customer-login';
                    if (isset($_GET['redirect']) && in_array($_GET['redirect'], $allowedRedirects, true)) {
                        $loginFormAction .= '?redirect=' . urlencode($_GET['redirect']);
                    }
                ?>
                <form action="<?php echo htmlspecialchars($loginFormAction); ?>" method="post" class="mt-4">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mt-5 btn-lg w-100">Login</button>
                </form>
                <p class="text-center mt-3">Don't have an account? <a href="register">Register here</a></p>
            </div>
        </div>
    </div>
  <?php require_once ('includes/footer.php'); ?>
  <?php require_once ('includes/cdn_footer.php'); ?>

</body>
</html>
