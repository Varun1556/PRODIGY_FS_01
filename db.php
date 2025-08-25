<?php
$dsn = 'mysql:host=127.0.0.1;dbname=form_db;charset=utf8mb4';
$user = 'root';      // WAMP default
$pass = '';          // WAMP default is empty; change if you set one

try {
  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (PDOException $e) {
  exit('DB connection failed: ' . htmlspecialchars($e->getMessage()));
}
