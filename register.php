<?php
require_once __DIR__ . '/functions.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf();
  $name = clean($_POST['name'] ?? '');
  $email = clean($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm'] ?? '';
  $role = clean($_POST['role'] ?? 'user'); // Get role from form

  if ($name === '') $errors[] = 'Name is required.';
  if (!valid_email($email)) $errors[] = 'Valid email is required.';
  if (!strong_password($password)) $errors[] = 'Password must be 8+ chars with upper, lower, digit & symbol.';
  if ($password !== $confirm) $errors[] = 'Passwords do not match.';
  if (!in_array($role, ['user', 'admin'])) $errors[] = 'Invalid role selected.';

  if (!$errors) {
    // Check if email exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
      $errors[] = 'This email is already registered.';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $otp = generate_otp();
      $expires = otp_expiry_dt(10);

      $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, is_verified, otp_code, otp_expires) VALUES (?,?,?,?,0,?,?)');
      $stmt->execute([$name, $email, $hash, $role, $otp, $expires]); // Use $role instead of 'user'

      // Send OTP mail
      $html = "<p>Hi {$name},</p>
               <p>Your verification code is <strong style='font-size:20px'>{$otp}</strong></p>
               <p>This code expires in 10 minutes.</p>";
      
      $mailResult = send_mail($email, $name, 'Your verification code', $html);
      
      if ($mailResult === true) {
        $_SESSION['pending_email'] = $email;
        header('Location: ' . BASE_URL . 'verify.php?msg=otp_sent');
        exit;
      } else {
        // More detailed error message in debug mode
        if (DEBUG_MODE && is_string($mailResult)) {
            $errors[] = 'Could not send OTP email. Error: ' . $mailResult;
        } else {
            $errors[] = 'Could not send OTP email. Please try again.';
        }
      }
    }
  }
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Register</title>

  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="container">
  <div class="nav">
    <div class="logo"><span class="dot"></span> Secure Authentication</div>
    <div><a href="index.php">Home</a><a href="login.php">Login</a></div>
  </div>

  <div class="card">
    <div class="form-title"><h1>Create account</h1><span class="badge">OTP email verification</span></div>
    <?php foreach ($errors as $e): ?>
      <div class="alert error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>
    <?php if ($success): ?><div class="alert info"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="post" novalidate>
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
      <label>Name</label>
      <input class="input" type="text" name="name" placeholder="Your name" required value="<?= isset($name) ? htmlspecialchars($name) : '' ?>">

      <label style="margin-top:10px">Email</label>
      <input class="input" type="email" name="email" placeholder="you@example.com" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">

      <label style="margin-top:10px">Account Type</label>
      <select class="input" name="role" required>
        <option value="user" <?= (isset($role) && $role === 'user') ? 'selected' : '' ?>>Regular User</option>
        <option value="admin" <?= (isset($role) && $role === 'admin') ? 'selected' : '' ?>>Administrator</option>
      </select>

      <div class="row" style="margin-top:10px">
        <div style="flex:1">
          <label>Password</label>
          <div class="row">
            <input class="input" id="pwd" type="password" name="password" placeholder="Strong password" required>
            <button type="button" class="nav" data-toggle="pwd" data-target="pwd">Show</button>
          </div>
        </div>
        <div style="flex:1">
          <label>Confirm Password</label>
          <input class="input" type="password" name="confirm" placeholder="Confirm password" required>
        </div>
      </div>

      <button class="btn" style="margin-top:16px">Register</button>
      <a class="link" href="login.php">Already have an account? Login</a>
    </form>
  </div>
  <div class="footer">We never share your email.</div>
</div>
<script src="assets/app.js"></script>
</body>
</html>