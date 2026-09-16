<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>Add user - City Tech Store</title>
</head>

<body>
    <?php require_once('includes/header.php'); ?>

    <?php if (!isset($_GET['action'])): ?>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">

                    <?php if (isset($_SESSION['addUserSuccess']) && $_SESSION['addUserSuccess'] == true):  ?>
                        <div id="successAlert" class="alert alert-success" role="alert">
                            User has been successfully added. You can now login with your credientials.
                        </div>
                        <?php unset($_SESSION['addUserSuccess']); ?>
                    <?php elseif (isset($_SESSION['addUserError']) && $_SESSION['addUserError'][0] == true):  ?>
                        <div id="errorAlert" class="alert alert-danger" role="alert">
                            <?php echo $_SESSION['addUserError'][1]; ?>
                        </div>
                        <?php unset($_SESSION['addUserError']); ?>
                    <?php endif; ?>
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Sign Up for City Tech Store Role</h3>
                        </div>
                        <div class="card-body">
                            <form action="adduser" method="POST">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="admin">Admin</option>
                                        <option value="worker">Worker</option>
                                        <option value="developer">Developer</option>
                                    </select>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary" name="manageruser-add-user">Sign Up</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!isset($_GET['confirmation']) && isset($_GET['action']) && isset($_GET['id']) && !(empty($_GET['action']) || empty($_GET['id']))): ?>
        <div class="container">
            <?php $check_result = $UsersModel->getUserById(user_input_sanitize($_GET['id']));
            if ($check_result): ?>
                <div class="container mt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h3>ARE YOU SURE YOU WANT TO DELETE?</h3>
                                </div>
                                <div class="card-body">
                                    <form action="adduser" method="POST">
                                        <div class="mb-3">
                                            <p for="username">Username</p>
                                            <p class="text-end"><?php echo $check_result['username']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <p for="email">Email</p>
                                            <p class="text-end"><?php echo $check_result['email']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <p for="firstname">First Name</p>
                                            <p class="text-end"><?php echo $check_result['firstname']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <p for="lastname">Last Name</p>
                                            <p class="text-end"><?php echo $check_result['lastname']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <p for="phone_number">Phone Number</p>
                                            <p class="text-end"><?php echo $check_result['phone_number']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <p for="role">Role</p>
                                            <p class="text-end"><?php echo $check_result['role']; ?></p>
                                        </div>
                                        <div class="d-grid">
                                            <a href="adduser?action=delete&id=<?php echo $check_result['id']; ?>&confirmation=true" class="btn btn-danger">Permanently delete user</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="container mt-5 text-center">
                    <?php echo '<p class="fs-4">No user with id: ' . $_GET['id'] . '. Please check and try again</p>'; ?>
                </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php require_once('includes/footer.php'); ?>
<?php require_once('includes/cdn_footer.php'); ?>
</body>

</html>