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

// ── Create Password Requests / Approvals Table ──────────────────
$createPasswordRequests = "CREATE TABLE IF NOT EXISTS `password_requests` (
    `id`              INT AUTO_INCREMENT PRIMARY KEY,
    `user_id`         INT NOT NULL,
    `username`        VARCHAR(50) NOT NULL,
    `email`           VARCHAR(255) NOT NULL,
    `new_password`    VARCHAR(255) NOT NULL,
    `status`          ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `admin_feedback`  TEXT DEFAULT NULL,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `reviewed_at`     DATETIME DEFAULT NULL,
    INDEX (`user_id`),
    INDEX (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
$conn->query($createPasswordRequests);

// ── Create Activity Logs Table ──────────────────────────────────
$createActivityLogs = "CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id`              INT AUTO_INCREMENT PRIMARY KEY,
    `activity`        VARCHAR(255) NOT NULL,
    `user_identifier` VARCHAR(255) NOT NULL,
    `type`            VARCHAR(50) DEFAULT 'general',
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
$conn->query($createActivityLogs);

// Seed initial activity logs if table is empty (matching the screenshot)
$checkLogs = $conn->query("SELECT id FROM activity_logs LIMIT 1");
if ($checkLogs && $checkLogs->num_rows === 0) {
    $conn->query("INSERT INTO activity_logs (activity, user_identifier, type, created_at) VALUES
        ('New seller application submitted', 'Jordan Davis', 'seller_app', DATE_SUB(NOW(), INTERVAL 15 MINUTE)),
        ('Listing flagged for review', 'Vintage record player', 'flag', DATE_SUB(NOW(), INTERVAL 40 MINUTE)),
        ('Payout processed', 'Sarah Chen, ₱8,200', 'payout', DATE_SUB(NOW(), INTERVAL 1 HOUR)),
        ('New user registered', 'marius@email.com', 'registration', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
        ('Transaction disputed', 'Order #10432', 'dispute', DATE_SUB(NOW(), INTERVAL 3 HOUR))");
}

// ── Create Admin Notifications Table ────────────────────────────
$createNotifications = "CREATE TABLE IF NOT EXISTS `admin_notifications` (
    `id`              INT AUTO_INCREMENT PRIMARY KEY,
    `title`           VARCHAR(255) NOT NULL,
    `message`         TEXT NOT NULL,
    `type`            VARCHAR(50) DEFAULT 'password_change',
    `is_read`         TINYINT(1) DEFAULT 0,
    `reference_id`    INT DEFAULT NULL,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
$conn->query($createNotifications);

// If no password requests exist yet, let's seed a couple of sample pending & history records
$checkRequests = $conn->query("SELECT id FROM password_requests LIMIT 1");
if ($checkRequests && $checkRequests->num_rows === 0) {
    // Check if there are any regular users to link
    $regularUser = $conn->query("SELECT id, username, email FROM users WHERE role != 'admin' LIMIT 1");
    if ($regularUser && $regularUser->num_rows > 0) {
        $u = $regularUser->fetch_assoc();
        $conn->query("INSERT INTO password_requests (user_id, username, email, new_password, status, created_at) VALUES
            ({$u['id']}, '{$u['username']}', '{$u['email']}', 'newpassword123', 'pending', DATE_SUB(NOW(), INTERVAL 10 MINUTE))");
        $conn->query("INSERT INTO admin_notifications (title, message, type, is_read, created_at) VALUES
            ('Password Change Request', '{$u['username']} ({$u['email']}) requested to change their password.', 'password_change', 0, DATE_SUB(NOW(), INTERVAL 10 MINUTE))");
    } else {
        // Create a sample demo user if none exists
        $conn->query("INSERT INTO users (username, email, password, role) VALUES ('Jordan Davis', 'jordan.davis@email.com', 'password123', 'user')");
        $newUserId = $conn->insert_id;
        $conn->query("INSERT INTO password_requests (user_id, username, email, new_password, status, created_at) VALUES
            ({$newUserId}, 'Jordan Davis', 'jordan.davis@email.com', 'updatedpass2026', 'pending', DATE_SUB(NOW(), INTERVAL 10 MINUTE))");
        $conn->query("INSERT INTO admin_notifications (title, message, type, is_read, created_at) VALUES
            ('Password Change Request', 'Jordan Davis (jordan.davis@email.com) requested to change their password.', 'password_change', 0, DATE_SUB(NOW(), INTERVAL 10 MINUTE))");
    }
}
