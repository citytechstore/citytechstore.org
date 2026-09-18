<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'app/models/Database.php';
    require_once 'app/models/Users.php';
    require_once 'app/models/lib.php';

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Your session expired. Please try again.";
        require "app/views/views.login.php";
        exit;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $user = $UsersModel->loginUser($username, $password);

        $_SESSION['user_session'] = $user;
        $_SESSION['loggedin'] = true;
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        header('Location: ' . $basePath . '/dashboard?role='.$user['role'].'&id='.$user['id']);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

require "app/views/views.login.php";