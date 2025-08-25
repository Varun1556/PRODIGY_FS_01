<?php
require_once __DIR__ . '/functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf();
  $email = clean($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  if (!valid_email($email)) $errors[] = 'Valid email required.';
  if ($password === '') $errors[] = 'Password required.';

  if (!$errors) {
    $stmt = $pdo->prepare('SELECT id,name,email,password_hash,role,is_verified FROM users WHERE email=? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
      $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
        'is_verified' => (int)$user['is_verified']
      ];
      session_regenerate_id(true);
      header('Location: ' . BASE_URL . 'dashboard.php');
      exit;
    } else {
      $errors[] = 'Invalid email or password.';
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login</title>
  
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="container">
  <div class="nav">
    <div class="logo"><span class="dot"></span> Secure Authentication</div>
    <div><a href="index.php">Home</a><a href="register.php">Register</a></div>
  </div>

  <div class="card">
    <h1> Login Form </h1>
    <?php foreach ($errors as $e): ?><div class="alert error"><?= htmlspecialchars($e) ?></div><?php endforeach; ?>

    <form method="post" novalidate>
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
      <label>Email</label>
      <input class="input" type="email" name="email" placeholder="you@example.com" required>
      <label style="margin-top:10px">Password</label>
      <div class="row">
        <input class="input" id="loginpwd" type="password" name="password" required>
        <button type="button" class="nav" data-toggle="pwd" data-target="loginpwd">Show</button>
      </div>
      <button class="btn" style="margin-top:16px">Login</button>
    </form>
  </div>
</div>
<script src="assets/app.js"></script>
</body>
</html>
