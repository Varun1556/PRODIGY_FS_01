<?php
require_once __DIR__ . '/functions.php';

$errors = [];
$info = '';

$email = $_SESSION['pending_email'] ?? '';
if (isset($_GET['msg']) && $_GET['msg']==='otp_sent') {
  $info = 'We sent a 6-digit code to your email.';
}
if (isset($_GET['msg']) && $_GET['msg']==='verify_first') {
  $info = 'Please verify your email to continue.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf();
  $email = clean($_POST['email'] ?? '');
  $code  = clean($_POST['code'] ?? '');

  if (!valid_email($email)) $errors[] = 'Valid email required.';
  if (!preg_match('/^\d{6}$/', $code)) $errors[] = 'Enter the 6-digit code.';

  if (!$errors) {
    $stmt = $pdo->prepare('SELECT id, name, role, is_verified, otp_code, otp_expires FROM users WHERE email=? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
      $errors[] = 'No account found for this email.';
    } elseif ($user['is_verified']) {
      $info = 'Email already verified. You can log in.';
    } else {
      if ($user['otp_code'] === $code && strtotime($user['otp_expires']) > time()) {
        // mark verified & clear OTP
        $upd = $pdo->prepare('UPDATE users SET is_verified=1, otp_code=NULL, otp_expires=NULL WHERE id=?');
        $upd->execute([$user['id']]);
        $info = 'Email verified! You can login now.';
        unset($_SESSION['pending_email']);
      } else {
        $errors[] = 'Invalid or expired code.';
      }
    }
  }
}

// resend OTP
if (isset($_GET['resend']) && valid_email($email)) {
  $stmt = $pdo->prepare('SELECT id,name FROM users WHERE email=? LIMIT 1');
  $stmt->execute([$email]);
  if ($u = $stmt->fetch()) {
    $otp = generate_otp();
    $exp = otp_expiry_dt(10);
    $pdo->prepare('UPDATE users SET otp_code=?, otp_expires=? WHERE id=?')->execute([$otp,$exp,$u['id']]);
    $html = "<p>Hi {$u['name']},</p><p>Your new verification code is <strong>{$otp}</strong> (10 min).</p>";
    if (send_mail($email, $u['name'], 'New verification code', $html)) {
      $info = 'New code sent.';
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Verify Email</title>
  
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="container">
  <div class="nav">
    <div class="logo"><span class="dot"></span> Secure Authentication</div>
    <div><a href="index.php">Home</a><a href="login.php">Login</a></div>
  </div>

  <div class="card">
    <h1>Verify your email</h1>
    <?php if ($info): ?><div class="alert info"><?= htmlspecialchars($info) ?></div><?php endif; ?>
    <?php foreach ($errors as $e): ?><div class="alert error"><?= htmlspecialchars($e) ?></div><?php endforeach; ?>

    <form method="post" novalidate>
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
      <label>Email</label>
      <input class="input" type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
      <label style="margin-top:10px">6-digit code</label>
      <input class="input" type="text" name="code" maxlength="6" placeholder="123456" required>
      <button class="btn" style="margin-top:16px">Verify</button>
    </form>
    <a class="link" href="verify.php?resend=1">Resend code</a>
  </div>
</div>
</body>
</html>
