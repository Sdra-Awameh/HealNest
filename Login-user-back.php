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
    $_SESSION['login_error'] = 'Database connection failed';
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    try {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Use prepared statement for security
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_id'] = $user['id']; // Also store the user ID if needed

                // Redirect to the dashboard or home page
                header("Location: public/HomePage.html");
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
    } catch (PDOException $e) {
        $_SESSION['login_error'] = 'Database error occurred';
        $_SESSION['active_form'] = 'login';
        header("Location: index.php");
        exit();
}
}
?>