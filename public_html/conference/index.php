<?php
// Файл: index.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'stats_helper.php';

// Записываем посещение
record_visit();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика Посещений</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Статистика Посещений Сайта Конференции</h1>
        </header>

        <nav>
            <a href="index.php" class="active">Главная</a>
            <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                <a href="admin.php">Админ. Панель</a>
                <a href="logout.php">Выход</a>
            <?php else: ?>
                <a href="login.php">Вход для Администратора</a>
            <?php endif; ?>
        </nav>

        <h2>Общая Статистика</h2>
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Параметр</th>
                    <th>Значение</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Уникальных IP-адресов</td>
                    <td><?php echo get_unique_ip_count(); ?></td>
                </tr>
                <tr>
                    <td>Зарегистрировано сессий</td>
                    <td><?php echo get_sessions_started_count(); ?></td>
                </tr>
                <tr>
                    <td>Всего загрузок страниц (хитов)</td>
                    <td><?php echo get_total_hits(); ?></td>
                </tr>
            </tbody>
        </table>

        <section>
            <h2>О конференции</h2>
            <p>Добро пожаловать на страницу нашей ежегодной конференции! Здесь вы можете ознакомиться с программой, спикерами и другой полезной информацией. Для подачи заявки на участие, пожалуйста, войдите в административную панель (доступно только организаторам).</p>
        </section>

        <footer>
            <p>© <?php echo date('Y'); ?> Организаторы Конференции. Срок дедлайна: 25.04.2025</p>
        </footer>
    </div>
</body>
</html>