<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'app/models/Database.php';
    require_once 'app/models/Users.php';

    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Instantiate database and user model
    // $db = new Database($database_connection); // Replace $database_connection with your actual connection
    // $users = new Users($db);

    try {
        $user = $UsersModel->loginUser($username, $password);

        // Check if the role matches
        if ($user['role'] === $role) {
            $_SESSION['user_session'] = $user;
            $_SESSION['loggedin'] = true;
            header('Location: /dashboard?role='.$user['role'].'&id='.$user['id']);
            exit;
        } else {
            $error = "Invalid role selected.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

require "app/views/views.login.php";