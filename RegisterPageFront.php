<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Register - HealNest</title>
  <link rel="stylesheet" href="RegisterPage.css">
</head>
<body>

  <div class="register-container">
    <div class="logo-container">
      <img src="Assets/logo.png" alt="HealNest Logo" class="logo">
      <div class="tagline">Create a user account to manage your pet’s care 🐶</div>
    </div>

    <form action="register-user-back.php" method="post">
      <?php
session_start();
if (isset($_SESSION['register_error'])) {
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
    '>" . $_SESSION['register_error'] . "</div>";
    unset($_SESSION['register_error']);
}
?>


      <div class="form-group">
        <label for="username">Full Name</label>
        <input type="text" id="username" name="name" class="form-control" required />
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" class="form-control" required />
      </div>

      <div class="form-group">
        <label for="Phone">Phone number</label>
        <input type="text" id="Phone" name="Phone" class="form-control" required />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" required />
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required />
      </div>

      <button type="submit" class="btn" name="register">
        <span class="paw-icon">🐾</span>
        Register
      </button>

      <div style="text-align: center; margin-top: 15px;">
        Already have an account? <a href="index.php" style="color: var(--secondary-color); text-decoration: underline;">Login here</a>
      </div>
     </div>
    </form>
    
   
  

</body>
</html>
