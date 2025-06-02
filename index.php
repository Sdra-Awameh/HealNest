<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealNest - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="LoginPageStyle.css">
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="Assets/logo.png" alt="HealNest Logo" class="logo">
           
            <p class="tagline">Veterinary Clinic Management System</p>
        </div>
        
         <form method="post" id="login-form" action="Login-user-back.php">
          <?php
session_start();
if (isset($_SESSION['login_error'])) {
    echo "<div style='
        background-color: #ffe0e0;
        color: #a94442;
        padding: 12px 16px;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        font-size: 15px;
        margin-bottom: 15px;
        text-align: center;
        font-family: Roboto, sans-serif;
    '>" . $_SESSION['login_error'] . "</div>";
    unset($_SESSION['login_error']); 
}
?>


            <div class="form-group">
                <label for="email">ُEmail</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
         
            <button type="submit" class="btn" name="login">Login</button>
            <div style="text-align: center; margin-top: 15px;">
                Don't have an account? <a href="RegisterPageFront.php" style="color: var(--secondary-color); text-decoration: underline;">Register here</a>
              </div>
         </form>
       
        
    </div>

   
</body>
</html>