<?php
require_once __DIR__ . '/middleware.php';
require_login();
$user = $_SESSION['user'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard</title>

  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="container">
  <div class="nav">
    <div class="logo"><span class="dot"></span> Secure Authentication</div>
    <div><a href="index.php">Home</a>
      <?php if ($user['role']==='admin'): ?><a href="admin.php">Admin</a><?php endif; ?>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="card grid grid-2">
    <div>
      <h1>Hello, <?= htmlspecialchars($user['name']) ?> 👋</h1>
      <p class="muted">Role: <strong><?= htmlspecialchars($user['role']) ?></strong></p>
      <p class="muted">Email: <?= htmlspecialchars($user['email']) ?></p>
      <div class="badge">You are logged in securely.</div>
    </div>
    <div>
      <h2>Quick links</h2>
      <p><a href="admin.php">Admin page</a> (admins only)</p>
      <p><a href="logout.php">Logout</a></p>
    </div>
  </div>
</div>
</body>
</html>
