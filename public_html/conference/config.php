<?php
// Конфигурация базы данных
define('DB_HOST', 'localhost');
define('DB_USER', 'm4xkukla22');
define('DB_PASS', 'URAIEJWX');
define('DB_NAME', 'm4xkukla22');

// Настройки администратора
define('ADMIN_LOGIN', 'admin');
define('ADMIN_PASSWORD_HASH', sha1('cegthgfhjkm')); // Хеш пароля

// Настройки сессии
define('SESSION_TIMEOUT', 300); // 5 минут в секундах

// Создаем подключение к базе данных
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Создаем таблицы, если их нет
$sql = "CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    topic VARCHAR(100) NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$conn->query($sql);

$sql = "CREATE TABLE IF NOT EXISTS statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    session_id VARCHAR(32) NOT NULL,
    page_views INT DEFAULT 1,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_ip (ip_address),
    UNIQUE KEY unique_session (session_id)
)";

$conn->query($sql);

session_start();
?>