<?php
session_start();

// Only clear the customer session keys — a staff session (loggedin /
// user_session) in the same browser must be left untouched.
unset($_SESSION['customer_loggedin']);
unset($_SESSION['customer_session']);

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
header('Location: ' . $basePath . '/');
exit;
