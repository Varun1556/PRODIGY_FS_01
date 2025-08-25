<?php
require_once __DIR__ . '/functions.php';

function require_login() {
  if (empty($_SESSION['user'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
  }
  if (empty($_SESSION['user']['is_verified'])) {
    header('Location: ' . BASE_URL . 'verify.php?msg=verify_first');
    exit;
  }
}

function require_admin() {
  require_login();
  if (($_SESSION['user']['role'] ?? 'user') !== 'admin') {
    http_response_code(403);
    exit('Forbidden: Admins only.');
  }
}
