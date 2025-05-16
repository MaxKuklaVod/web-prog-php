<?php // app/Views/layout.php ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой Календарь</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Мой Календарь</h1>
        <nav>
            <a href="index.php">Главная (Форма)</a>
            <a href="index.php?filter=current">Текущие</a>
            <a href="index.php?filter=overdue">Просроченные</a>
            <a href="index.php?filter=completed">Выполненные</a>
            <form action="index.php" method="get" style="display: inline-block;">
                <input type="hidden" name="filter" value="date">
                <label for="date_filter_nav">Задачи на дату:</label>
                <input type="date" id="date_filter_nav" name="date_filter" value="<?= htmlspecialchars($date_filter_value ?? '') ?>" onchange="this.form.submit()">
            </form>
        </nav>
    </header>

    <main>
        <?php if (isset($message) && $message): ?>
            <div class="message <?= htmlspecialchars($message['type']) ?>">
                <?= htmlspecialchars($message['text']) ?>
            </div>
        <?php endif; ?>

        <?php include $content; // Подключаем основной контент страницы ?>
    </main>

    <footer>
        <p>© <?= date('Y') ?> Мой Календарь</p>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>