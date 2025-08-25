<?php
// Application Configuration
// App base URL (adjust if needed, e.g., when you deploy online)
define('BASE_URL', 'http://localhost/secure-auth/');
define('SMTP_HOST', 'smtp-relay.brevo.com');
define('SMTP_PORT', 587);                    
define('SMTP_SECURE', 'tls');                

// USE THE LOGIN FROM YOUR BREVO DASHBOARD (not your email)
define('SMTP_USER', '9577d5001@smtp-brevo.com');  // Your Brevo SMTP login

// USE THE SMTP KEY FROM YOUR BREVO DASHBOARD
define('SMTP_PASS', 'xsmtpsib-2dd9596296964f06f9ebe06c59a2197bd59a46e2c9bbb02c61deb7343914092a-nGDQMLUtHj4fVTCa');  // Brevo SMTP key

// This can be your actual email address
define('SMTP_FROM', 'vs2715450@gmail.com');  // Sender email
define('SMTP_FROM_NAME', 'Secure Auth App From Varun_Git'); // Sender name shown in email

//Security Settings
define('CSRF_TOKEN_NAME', '_token');  // Form hidden CSRF field name

//Debug Mode
define('DEBUG_MODE', true); // Set to false in production

// Enable error reporting if in debug mode
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}