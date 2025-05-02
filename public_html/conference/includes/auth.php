<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/functions.php'; // Подключаем перед использованием функций

// Проверка авторизации при доступе к админке
if (basename($_SERVER['PHP_SELF']) != 'admin.php' && !isAdminLoggedIn()) {
    header("Location: admin.php");
    exit;
}

// Обработка входа
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($login === ADMIN_LOGIN && sha1($password) === ADMIN_PASSWORD_HASH) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['last_activity'] = time();
        header("Location: admin.php");
        exit;
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>