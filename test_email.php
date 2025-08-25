<?php
require_once __DIR__ . '/functions.php';

echo "<h2>Testing Email Configuration</h2>";

// Test basic PHPMailer loading (simple approach)
if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "✅ PHPMailer loaded successfully<br>";
} else {
    echo "❌ PHPMailer failed to load<br>";
    exit;
}

// Test SMTP configuration
echo "SMTP Host: " . SMTP_HOST . "<br>";
echo "SMTP Port: " . SMTP_PORT . "<br>";
echo "SMTP User: " . SMTP_USER . "<br>";
echo "SMTP From: " . SMTP_FROM . "<br>";
echo "Debug Mode: " . (defined('DEBUG_MODE') && DEBUG_MODE ? 'ON' : 'OFF') . "<br>";

// Test sending an email
$result = send_mail("vs2715450@gmail.com", "Varun Test", "Test Email", 
    "<h3>Test Email</h3><p>This is a test email from your authentication system.</p>");

if ($result === true) {
    echo "✅ Test email sent successfully!<br>";
} else {
    echo "❌ Failed to send test email: " . $result . "<br>";
    
    // Additional debugging info
    echo "Debug info:<br>";
    echo "Make sure your Brevo SMTP key is correct<br>";
    echo "Check that your Brevo account is activated<br>";
    echo "Verify that outgoing port 587 is not blocked<br>";
}