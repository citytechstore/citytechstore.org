<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'app/models/Database.php';
    require_once 'app/models/Customer.php';

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "Please enter both email and password.";
    } else {
        try {
            $customer = $CustomerModel->login($email, $password);

            unset($customer['password']);
            $_SESSION['customer_loggedin'] = true;
            $_SESSION['customer_session'] = $customer;

            // Allowlist-only redirect target, to avoid an open-redirect via ?redirect=
            $allowedRedirects = ['checkout'];
            $redirectTarget = '/';
            if (isset($_GET['redirect']) && in_array($_GET['redirect'], $allowedRedirects, true)) {
                $redirectTarget = '/' . $_GET['redirect'];
            }

            $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
            header('Location: ' . $basePath . $redirectTarget);
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

require "app/views/views.customer_login.php";
