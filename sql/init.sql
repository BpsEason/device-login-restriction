CREATE DATABASE IF NOT EXISTS device_login_db;
USE device_login_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL,
    device_info VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    UNIQUE KEY unique_session (user_id, session_token),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at)
);

-- Insert a sample user (password: 'password123' hashed with password_hash)
INSERT INTO users (username, password) VALUES (
    'testuser',
    '$2y$10$z3Q8j9k2Y5p7v8m0n4x2ue8j9k2Y5p7v8m0n4x2u3Q8j9k2Y5p7v'
);