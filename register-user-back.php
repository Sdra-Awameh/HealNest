<?php
session_start();
require_once 'config.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['Phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['register_error'] = 'Passwords do not match';
        $_SESSION['active_form'] = 'register';
        header("Location: RegisterPageFront.php"); 
        exit();
    }

    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered';
        $_SESSION['active_form'] = 'register';
        header("Location: RegisterPageFront.php"); 
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$hashedPassword')");

    $_SESSION['register_success'] = "Account created successfully. Please log in.";
    header("Location: index.php");
    exit();
}
?>
