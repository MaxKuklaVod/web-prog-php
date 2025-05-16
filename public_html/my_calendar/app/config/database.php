<?php
// app/config/database.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'm4xkukla22');
define('DB_USER', 'm4xkukla22');     
define('DB_PASS', 'URAIEJWX');          

function getDBConnection() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // В реальном приложении здесь должно быть логирование ошибки
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }
    return $pdo;
}
?>