<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/**
 * -----------------------------
 * PHPMailer Manual Loading
 * -----------------------------
 */
// Load PHPMailer classes manually
require_once __DIR__ . '/vendor/src/PHPMailer.php';
require_once __DIR__ . '/vendor/src/SMTP.php';
require_once __DIR__ . '/vendor/src/Exception.php';

// Use aliases to avoid naming conflicts
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

/**
 * -----------------------------
 * Secure Session Initialization
 * -----------------------------
 */
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => !empty($_SERVER['HTTPS']), // true if using HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * -----------------------------
 * CSRF Utilities
 * -----------------------------
 */
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verify_csrf() {
    $key = CSRF_TOKEN_NAME;
    if (!isset($_POST[$key]) || !hash_equals($_SESSION['csrf'] ?? '', $_POST[$key])) {
        http_response_code(400);
        exit('Invalid CSRF token');
    }
}

/**
 * -----------------------------
 * Input Sanitizers
 * -----------------------------
 */
function clean($v) {
    return trim($v ?? '');
}

function valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function strong_password($pwd) {
    // Min 8, at least 1 upper, 1 lower, 1 digit, 1 symbol
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $pwd);
}

/**
 * -----------------------------
 * OTP Helpers
 * -----------------------------
 */
function generate_otp() {
    return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

function otp_expiry_dt($minutes = 10) {
    return date('Y-m-d H:i:s', time() + $minutes * 60);
}

/**
 * -----------------------------
 * Mailer Helper (PHPMailer)
 * -----------------------------
 */
function send_mail($toEmail, $toName, $subject, $html) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port       = SMTP_PORT;
        
        // Enable debugging if in debug mode
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
            $mail->Debugoutput = function($str, $level) {
                error_log("PHPMailer: $str");
            };
        }

        // Sender and recipient
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($toEmail, $toName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html;
        $mail->AltBody = strip_tags($html); // Plain text version

        $result = $mail->send();
        
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            error_log("Email sent successfully to: $toEmail");
        }
        
        return $result;
    } catch (PHPMailerException $e) {
        error_log('Mail error: ' . $mail->ErrorInfo);
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            // Return detailed error in debug mode
            return "Mailer Error: " . $mail->ErrorInfo;
        }
        return false;
    }
}