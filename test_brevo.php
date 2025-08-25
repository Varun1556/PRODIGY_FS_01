<?php
require_once __DIR__ . '/config.php';

echo "<h2>Brevo SMTP Configuration Test</h2>";

echo "SMTP Host: " . SMTP_HOST . "<br>";
echo "SMTP Port: " . SMTP_PORT . "<br>";
echo "SMTP User: " . SMTP_USER . "<br>";
echo "SMTP Pass: " . (strlen(SMTP_PASS) > 0 ? "Set (" . strlen(SMTP_PASS) . " chars)" : "Not set") . "<br>";
echo "SMTP From: " . SMTP_FROM . "<br>";
echo "Debug Mode: " . (defined('DEBUG_MODE') && DEBUG_MODE ? 'ON' : 'OFF') . "<br>";

// Test connection
$socket = @fsockopen(SMTP_HOST, SMTP_PORT, $errno, $errstr, 10);
if ($socket) {
    echo "✅ Can connect to Brevo SMTP server<br>";
    fclose($socket);
} else {
    echo "❌ Cannot connect to Brevo SMTP server: $errstr ($errno)<br>";
}

// Test if we can send email (if password is set)
if (defined('SMTP_PASS') && !empty(SMTP_PASS)) {
    require_once __DIR__ . '/functions.php';
    
    $result = send_mail("vs2715450@gmail.com", "Varun Test", "Brevo Test Email", 
        "<h3>Test Email from Brevo</h3><p>This is a test email sent through Brevo SMTP.</p>");
    
    if ($result === true) {
        echo "✅ Test email sent successfully via Brevo!<br>";
    } else {
        echo "❌ Failed to send test email: " . $result . "<br>";
    }
} else {
    echo "⚠️ SMTP password not set. Please update config.php with your Brevo SMTP key.<br>";
}