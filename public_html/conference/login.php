<?php
// Файл: login.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
require_once 'stats_helper.php'; // Для обновления хитов на этой странице тоже

// Если уже залогинен, перенаправляем в админку
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');
    exit;
}

record_visit(); // Считаем посещение страницы логина

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USERNAME && md5($password) === ADMIN_PASSWORD_HASH) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['last_activity'] = time();
        header('Location: admin.php');
        exit;
    } else {
        $error_message = 'Неверное имя пользователя или пароль.';
    }
}

$timeout_message = '';
if (isset($_GET['timeout']) && $_GET['timeout'] == '1') {
    $timeout_message = 'Вы были автоматически разлогинены из-за неактивности.';
}
if (isset($_GET['logged_out']) && $_GET['logged_out'] == '1') {
    $timeout_message = 'Вы успешно вышли из системы.';
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход для Администратора</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Вход в Панель Администратора</h1>
        </header>

        <nav>
            <a href="index.php">Главная</a>
            <a href="login.php" class="active">Вход для Администратора</a>
        </nav>

        <?php if ($error_message): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <?php if ($timeout_message): ?>
            <p class="success-message"><?php echo htmlspecialchars($timeout_message); ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Имя пользователя:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Войти</button>
        </form>

        <footer>
            <p>© <?php echo date('Y'); ?> Организаторы Конференции</p>
        </footer>
    </div>
</body>
</html>