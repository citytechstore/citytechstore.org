<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require_once ('includes/cdn_header.php'); ?>
    <title>Register - City Tech Store</title>
</head>
<body>
  <?php require_once ('includes/header.php'); ?>
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mt-5 mb-5">Create an Account</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form action="register" method="post" class="mt-4">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" required value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo htmlspecialchars($_POST['phone_number'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required minlength="8">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mt-5 btn-lg w-100">Register</button>
                </form>
                <div class="text-center text-muted my-3">or</div>
                <a href="auth/google/start" class="btn btn-outline-secondary btn-lg w-100">
                    <i class="fab fa-google"></i> Sign up with Google
                </a>
                <p class="text-center mt-3">Already have an account? <a href="customer-login">Login here</a></p>
            </div>
        </div>
    </div>
  <?php require_once ('includes/footer.php'); ?>
  <?php require_once ('includes/cdn_footer.php'); ?>

</body>
</html>
