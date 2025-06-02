<?php
session_start();
require_once 'config.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

    
            header("Location: HomePage.html");
            exit();
        } else {
            
            $_SESSION['login_error'] = 'Incorrect password';
        }
    } else {
        
        $_SESSION['login_error'] = 'Email not found';
    }

    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>
