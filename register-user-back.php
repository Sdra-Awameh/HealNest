<?php
session_start();

// Load configuration
$config = require_once __DIR__ . '/config.php';

try {
    // Create PDO connection using config
    $dsn = "mysql:host={$config['db']['host']};port={$config['db']['port']};dbname={$config['db']['dbname']};charset={$config['db']['charset']}";
    $pdo = new PDO($dsn, $config['db']['username'], $config['db']['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    $_SESSION['register_error'] = 'Database connection failed';
    header("Location: RegisterPageFront.php");
    exit();
}

if (isset($_POST['register'])) {
    try {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['Phone']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        if ($password !== $confirm_password) {
            $_SESSION['register_error'] = 'Passwords do not match';
            $_SESSION['active_form'] = 'register';
            header("Location: RegisterPageFront.php"); 
            exit();
        }

        // Check if email already exists using prepared statement
        $checkStmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
        $checkStmt->execute(['email' => $email]);
        
        if ($checkStmt->fetch()) {
            $_SESSION['register_error'] = 'Email is already registered';
            $_SESSION['active_form'] = 'register';
            header("Location: RegisterPageFront.php"); 
            exit();
        }

        // Insert new user using prepared statement
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (:name, :email, :phone, :password)");        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $hashedPassword
        ]);

        $_SESSION['register_success'] = "Account created successfully. Please log in.";
        $_SESSION['notification'] = [
            'message' => "Welcome " . $name . "! Your account has been created successfully.",
            'type' => 'success'
        ];
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['register_error'] = 'Registration failed: ' . $e->getMessage();
        $_SESSION['active_form'] = 'register';
        header("Location: RegisterPageFront.php");
        exit();
}
}
?>