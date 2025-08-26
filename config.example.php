<?php
/**
 * -----------------------------
 * Application Configuration
 * -----------------------------
 */

// Rename this file to config.php and update with your values

define('BASE_URL', 'http://localhost/secure-auth/');

/**
 * -----------------------------
 * SMTP Settings (PHPMailer + Brevo)
 * -----------------------------
 */
define('SMTP_HOST', 'smtp-relay.brevo.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USER', 'your_brevo_login@smtp-brevo.com');
define('SMTP_PASS', 'your_brevo_smtp_key_here');
define('SMTP_FROM', 'your_email@example.com');
define('SMTP_FROM_NAME', 'Your App Name');

/**
 * -----------------------------
 * Security Settings
 * -----------------------------
 */
define('CSRF_TOKEN_NAME', '_token');

/**
 * -----------------------------
 * Debug Mode
 * -----------------------------
 */
define('DEBUG_MODE', true);

// Enable error reporting if in debug mode
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}