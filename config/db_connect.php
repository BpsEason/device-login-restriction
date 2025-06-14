<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=device_login_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>