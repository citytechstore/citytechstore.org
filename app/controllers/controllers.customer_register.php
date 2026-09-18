<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'app/models/Database.php';
    require_once 'app/models/Customer.php';
    require_once 'app/models/lib.php';

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Your session expired. Please try again.";
        require "app/views/views.customer_register.php";
        exit;
    }

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($first_name === '' || $last_name === '' || $email === '' || $password === '' || $confirm_password === '') {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        try {
            $customer = $CustomerModel->register([
                'first_name'   => $first_name,
                'last_name'    => $last_name,
                'email'        => $email,
                'password'     => $password,
                'phone_number' => $phone_number,
            ]);

            unset($customer['password']);
            $_SESSION['customer_loggedin'] = true;
            $_SESSION['customer_session'] = $customer;

            $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
            header('Location: ' . $basePath . '/');
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

require "app/views/views.customer_register.php";
