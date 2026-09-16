<?php
session_start();

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_session']) || $_SESSION['loggedin'] != true || empty($_SESSION['user_session'])) {
    header("Location: /login");
}

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();
    session_destroy();
    header("Location: /login");
    exit();
}

require_once('app/models/Database.php'); // Database Model
require_once('app/models/Users.php'); // Users Model
require_once('app/models/lib.php'); // function lib

if (isset($_POST['manageruser-add-user']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $_SESSION['addUserError'] = [false, ''];

    $formData = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'email' => $_POST['email'],
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'phone_number' => $_POST['phone_number'],
        'role' => $_POST['role']
    ];

    foreach ($formData as $key => $value) {
        if (empty($value)) {
            $_SESSION['addUserError'] = [true, ucfirst(str_replace('_', ' ', $key)) . ' is required.'];
            break;
        }
    }

    $formData = [
        'username' => htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'),
        'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
        'firstname' => htmlspecialchars($_POST['firstname'], ENT_QUOTES, 'UTF-8'),
        'lastname' => htmlspecialchars($_POST['lastname'], ENT_QUOTES, 'UTF-8'),
        'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
        'phone_number' => htmlspecialchars($_POST['phone_number'], ENT_QUOTES, 'UTF-8'),
        'role' => htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8'),
    ];

    // check if username/email with same role already exists with same email
    // $confirmUserAndRole = $UsersModel->checkUserExistsWithSameRole($formData['username'], $formData['email'], $formData['role']);
    // if ($confirmUserAndRole == false) {
    //     if($UsersModel->createUser($formData)){
    //         $_SESSION['addUserSuccess'] = true;
    //     }else{
    //         $_SESSION['addUserError'] = [true, 'Unable to add user right now, please try again later.'];
    //     }
    // } else if ($confirmUserAndRole == true) {
    //     $_SESSION['addUserError'] = [true, 'Username or email already exists with the same role.'];
    // } else if ($confirmUserAndRole == null) {
    //     $_SESSION['addUserError'] = [true, 'Slow internet connection detected while adding user to database!'];
    // }

    // Check if username/email with the same role already exists with the same email
    $confirmUserAndRole = $UsersModel->checkUserExistsWithSameRole($formData['username'], $formData['email'], $formData['role']);
    if ($confirmUserAndRole == false) {
        $UsersModel->createUser($formData);
        $_SESSION['addUserSuccess'] = true;
    } else if ($confirmUserAndRole == true) {
        $_SESSION['addUserError'] = [true, 'Username or email already exists with the same role.'];
    } else if ($confirmUserAndRole == null) {
        $_SESSION['addUserError'] = [true, 'Unable to add user right now, please try again later.'];
    }
}


if (isset($_GET['confirmation']) && isset($_GET['action']) && isset($_GET['id']) && !(empty($_GET['confirmation']) || empty($_GET['action']) || empty($_GET['id'])) && $_GET['confirmation'] == true){
$UsersModel->deleteUser(user_input_sanitize($_GET['id']));
header('Location: /manage?type=users');
}

require "app/views/views.adduser.php";
