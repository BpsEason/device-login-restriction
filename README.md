# Device Login Restriction

A PHP-based web application that restricts users to logging in on a maximum of two devices simultaneously.

## Features
- User registration and login system.
- Restricts users to a maximum of two concurrent device logins.
- Device identification using User-Agent and IP address.
- Session management with expiration.
- Secure password hashing.
- Simple dashboard for logged-in users.

## Requirements
- PHP >= 7.4
- MySQL >= 5.7
- Composer
- Web server (e.g., Apache, Nginx)

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/BpsEason/device-login-restriction.git
   cd device-login-restriction
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Create a MySQL database and import the SQL file:
   ```bash
   mysql -u your_username -p < sql/init.sql
   ```
4. Configure the database connection in `config/db_connect.php`:
   ```php
   $pdo = new PDO("mysql:host=localhost;dbname=device_login_db", "your_username", "your_password");
   ```
5. Set up your web server to point to the `public/` directory.
6. Access the application in your browser (e.g., `http://localhost`).

## Usage
- Default user: `testuser` / `password123`
- Log in with the credentials above to access the dashboard.
- Try logging in from a third device to see the oldest session terminated.

## Directory Structure
- `config/`: Database connection settings.
- `sql/`: Database schema and initial data.
- `public/`: Publicly accessible PHP files and web assets.
- `src/`: Application logic (e.g., session management).

## Notes
- The device fingerprint is generated using User-Agent and IP address, which may not be 100% reliable. Consider advanced device fingerprinting for production.
- Sessions expire after 1 hour by default. Adjust the expiration time in `SessionManager.php` if needed.
- Use HTTPS in production to secure session data.

## License
MIT License