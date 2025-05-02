<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (!isAdminLoggedIn()) {
    // Показываем форму входа
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Вход в админ-панель</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="login-form">
            <h2>Вход в админ-панель</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="login">Логин:</label>
                    <input type="text" id="login" name="login" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit">Войти</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Получаем статистику
$stats = getStatistics($conn);

// Получаем заявки
$applications = [];
$result = $conn->query("SELECT * FROM applications ORDER BY created_at DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Админ-панель конференции</h1>
        <a href="logout.php" class="logout">Выйти</a>
    </header>
    
    <main>
        <section class="statistics">
            <h2>Статистика посещений</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Уникальные посетители</h3>
                    <p><?= $stats['unique_ips'] ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Посещения (сессии)</h3>
                    <p><?= $stats['unique_sessions'] ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Всего просмотров</h3>
                    <p><?= $stats['total_hits'] ?></p>
                </div>
            </div>
        </section>
        
        <section class="applications">
            <h2>Заявки на участие</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>Тема</th>
                        <th>Дата подачи</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><?= $app['id'] ?></td>
                        <td><?= htmlspecialchars($app['name']) ?></td>
                        <td><?= htmlspecialchars($app['email']) ?></td>
                        <td><?= htmlspecialchars($app['phone']) ?></td>
                        <td><?= htmlspecialchars($app['topic']) ?></td>
                        <td><?= $app['created_at'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>