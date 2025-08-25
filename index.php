<?php require_once __DIR__.'/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Secure Auth</title>
  
  <link rel="stylesheet" href="/secure-auth/assets/styles.css">
</head>
<body>
  <div class="container">
    <div class="nav">
      <div class="logo"><span class="dot"></span> Secure Authentication</div>
      <div>
        <?php if (!empty($_SESSION['user'])): ?>
          <a href="dashboard.php">Dashboard</a>
          <a href="logout.php">Logout</a>
        <?php else: ?>
          <a href="register.php">Register</a>
          <a href="login.php">Login</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="card grid grid-2">
      <div>
        <h1>Modern, Secure, Authentication System</h1>
        <p class="muted">Register with email OTP verification, login with hashed passwords, and role-based access control.</p>
        <div class="row">
          <a class="nav" href="register.php">Create Account →</a>
          <a class="nav" href="login.php">Login →</a>
        </div>
      </div>
      <div>
        <div class="badge">Features</div>
        <p>• Email OTP verification</p>
        <p>• CSRF protection</p>
        <p>• Sessions hardened</p>
        <p>• Admin-only pages</p>
      </div>
    </div>

    <div class="footer">Built with PHPMailer, PHP & MySQL Database on WAMP.</div>
  </div>
  <script src="assets/app.js"></script>
</body>
</html>
