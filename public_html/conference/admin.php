<?php
// Файл: admin.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
require_once 'stats_helper.php'; // Для обновления хитов на этой странице

// Проверка авторизации
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Проверка времени бездействия
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT_DURATION) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1'); // Перенаправляем с сообщением о таймауте
    exit;
}
$_SESSION['last_activity'] = time(); // Обновляем время последней активности

record_visit(); // Считаем посещение админ-страницы

$form_submitted_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {
    // Здесь могла бы быть обработка формы заявки
    // Например, сохранение в базу данных, отправка email и т.д.
    // Для примера просто выведем сообщение
    $name = htmlspecialchars($_POST['participant_name'] ?? 'Аноним');
    $form_submitted_message = "Заявка от '$name' успешно принята (демонстрация)!";
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Административная Панель - Заявки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Административная Панель</h1>
        </header>

        <nav>
            <a href="index.php">Главная</a>
            <a href="admin.php" class="active">Админ. Панель</a>
            <a href="logout.php" class="btn-logout">Выход (<?php echo ADMIN_USERNAME; ?>)</a>
        </nav>

        <h2>Форма Заявки на Участие в Конференции</h2>
        <p>Эта страница доступна только администраторам.</p>

        <?php if ($form_submitted_message): ?>
            <p class="success-message"><?php echo $form_submitted_message; ?></p>
        <?php endif; ?>

        <form method="POST" action="admin.php">
            <div class="form-group">
                <label for="participant_name">Имя участника:</label>
                <input type="text" id="participant_name" name="participant_name" required>
            </div>
            <div class="form-group">
                <label for="participant_email">Email участника:</label>
                <input type="email" id="participant_email" name="participant_email" required>
            </div>
            <div class="form-group">
                <label for="topic">Тема доклада (если есть):</label>
                <input type="text" id="topic" name="topic">
            </div>
            <div class="form-group">
                <label for="comments">Комментарии:</label>
                <textarea id="comments" name="comments"></textarea>
            </div>
            <button type="submit" name="submit_application" class="btn">Отправить Заявку</button>
        </form>

        <section>
            <h2>Статистика посещений страницы заявок (этой страницы):</h2>
             <p><em>Примечание: Глобальная статистика на главной странице включает и эти посещения.</em></p>
             <p>Текущее время сервера: <?php echo date('Y-m-d H:i:s', time()); ?></p>
             <p>Последняя активность: <?php echo date('Y-m-d H:i:s', $_SESSION['last_activity']); ?></p>
             <p>Сессия истечет через (сек): <?php echo SESSION_TIMEOUT_DURATION - (time() - $_SESSION['last_activity']); ?></p>
        </section>


        <footer>
            <p>© <?php echo date('Y'); ?> Организаторы Конференции</p>
        </footer>
    </div>
</body>
</html>