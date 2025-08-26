🔐 Secure User Authentication System
A modern, secure authentication system built with PHP and MySQL featuring OTP verification, role-based access control, and a sleek glassmorphism UI.

## ✨ Features

- 🔐 **Secure Registration** with email OTP verification
- 👥 **Role-based Access Control** (User & Admin roles)
- 🎨 **Modern Glassmorphism UI** with smooth animations
- 📧 **Brevo SMTP Integration** for reliable email delivery
- 🛡️ **CSRF Protection** and secure session management
- 📱 **Fully Responsive** design for all devices
- 🔒 **Password Strength Validation** with security requirements
- ⚡ **Lightning Fast** and optimized performance

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Wamp Server (Open Source Web Server)
- Brevo account for SMTP services

🎯 Usage
|| For Users ||
Register: Visit /register.php, fill in details, and choose account type

Verify Email: Check your inbox for the 6-digit OTP code

Login: Access your dashboard after verification

Manage Account: View your profile and access features based on your role

|| For Administrators ||
Admin Access: Login with admin credentials

Admin Panel: Access /admin.php for administrative features

User Management: Manage users and system settings (extendable)

|| Default Admin Account ||

Email: admin@example.com
Password: password

#### Setup Your Database
Create a MySQL database called form_db and run this:

sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  is_verified TINYINT(1) NOT NULL DEFAULT 0,
  otp_code VARCHAR(6),
  otp_expires DATETIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

##### Configure Your Settings
Rename the example config file:

bash
cp config.example.php config.php
Then edit config.php with your database info and email settings. You'll need a free Brevo account for the email part.


🎯 Default Admin Account
If you want to test admin features right away, add this to your database:

sql
INSERT INTO users (name, email, password_hash, role, is_verified)
VALUES ('Admin', 'admin@example.com', 
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin', 1);


🛡️ Security Features
Password Hashing: bcrypt algorithm with cost factor 10

CSRF Protection: Token-based form validation

Session Security: HttpOnly, Secure, and SameSite cookies

Input Validation: Comprehensive server-side sanitization

OTP Expiry: Time-based one-time passwords (10-minute validity)

Role-based Access: Strict permission controls

📧 Email Integration
This system uses Brevo (formerly Sendinblue) for SMTP services:

Create a free account at brevo.com

Get your SMTP credentials from the dashboard

Configure in config.php with your API key

Test email functionality with test_email.php

🚀 Performance Optimization
Minified Assets: Compressed CSS and JavaScript

Database Indexing: Optimized queries with proper indexes

Caching Strategies: Session and output caching

CDN Ready: Structured for content delivery networks


📝 How It Works
Sign Up - Users create an account and choose if they want to be regular users or admins

Verify Email - The system sends a 6-digit code to their email

Login - After verification, they can access their dashboard

Different Access - Regular users see basic features, admins get extra controls










