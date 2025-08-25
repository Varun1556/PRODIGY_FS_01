-- Secure Auth System Database Setup
-- Create database
CREATE DATABASE IF NOT EXISTS form_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE form_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  is_verified TINYINT(1) NOT NULL DEFAULT 0,
  otp_code VARCHAR(6),
  otp_expires DATETIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: 'password')
INSERT INTO users (name, email, password_hash, role, is_verified)
VALUES ('Administrator', 'admin@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin', 1);

-- Show table structure
DESCRIBE users;

-- Show sample data
SELECT * FROM users;