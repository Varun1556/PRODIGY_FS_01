<?php
require_once __DIR__ . '/middleware.php';
require_admin();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin</title>
  
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="container">
  <div class="nav">
    <div class="logo"><span class="dot"></span> Secure Authentication</div>
    <div><a href="dashboard.php">Dashboard</a><a href="logout.php">Logout</a></div>
  </div>

  <div class="card">
    <h1>|| JAI SHRI RAM ||</h1> 
    <br>
    <h3>Administrator's Area</h3>
    <br>
    <p>  Only admins can see this page. Here you can add your management tools.</p>
  </div>
</div>
</body>
</html>
