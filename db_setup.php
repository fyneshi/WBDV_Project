<?php
/**
 * Database Setup & Connection
 * 
 * This file creates the MySQL connection and auto-creates the
 * `station_finds` database and required tables if they don't exist.
 * 
 * Default XAMPP credentials are used (root, no password).
 */

// ── Database Configuration ──────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');           // Default XAMPP: no password
define('DB_NAME', 'station_finds');

// ── Connect to MySQL Server ─────────────────────────────────────
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]));
}

// ── Create Database if it doesn't exist ─────────────────────────
$conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db(DB_NAME);

// ── Create Users Table if it doesn't exist ──────────────────────
$createTable = "CREATE TABLE IF NOT EXISTS `users` (
    `id`              INT AUTO_INCREMENT PRIMARY KEY,
    `username`        VARCHAR(50)  NOT NULL UNIQUE,
    `email`           VARCHAR(255) NOT NULL UNIQUE,
    `password`        VARCHAR(255) NOT NULL,
    `role`            VARCHAR(20)  NOT NULL DEFAULT 'user',
    `reset_token`     VARCHAR(255) DEFAULT NULL,
    `reset_expires`   DATETIME     DEFAULT NULL,
    `created_at`      TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (!$conn->query($createTable)) {
    die(json_encode([
        'success' => false,
        'message' => 'Table creation failed: ' . $conn->error
    ]));
}

// Check if role column exists in users table (in case table was created earlier without it)
$checkRoleCol = $conn->query("SHOW COLUMNS FROM `users` LIKE 'role'");
if ($checkRoleCol && $checkRoleCol->num_rows === 0) {
    $conn->query("ALTER TABLE `users` ADD COLUMN `role` VARCHAR(20) NOT NULL DEFAULT 'user' AFTER `password`");
}

// ── Seed Default Admin User if not exists ───────────────────────
$adminEmail = 'admin@stationfinds.com';
$checkAdmin = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = 'Admin User' OR role = 'admin'");
$checkAdmin->bind_param("s", $adminEmail);
$checkAdmin->execute();
$adminRes = $checkAdmin->get_result();

if ($adminRes->num_rows === 0) {
    $adminUser = 'Admin User';
    $adminPass = 'admin123'; // Default admin password
    $adminRole = 'admin';
    $insertAdmin = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $insertAdmin->bind_param("ssss", $adminUser, $adminEmail, $adminPass, $adminRole);
    $insertAdmin->execute();
    $insertAdmin->close();
}
$checkAdmin->close();


