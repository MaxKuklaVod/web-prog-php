<?php
// Включаем файл с логикой администратора
require_once '../script/admin_logic.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора - Заявки на конференцию</title>
    <link rel="stylesheet" href="../css/admin_styles.css">
</head>
<body>
    <h1>Панель администратора - Заявки на конференцию</h1>
    
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] === 'true'): ?>
        <div class="success">Выбранные заявки успешно удалены.</div>
    <?php endif; ?>
    
    <?php if (empty($applications)): ?>
        <div class="no-applications">Заявок пока нет.</div>
    <?php else: ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="select-all">
                <input type="checkbox" id="select-all">
                <label for="select-all">Выбрать все</label>
            </div>
            
            <div class="action-buttons">
                <button type="submit" name="delete" onclick="return confirm('Вы уверены, что хотите удалить выбранные заявки?')">Удалить выбранные</button>
            </div>
            
            <?php foreach ($applications as $app): ?>
                <div class="application">
                    <div>
                        <input type="checkbox" name="applications[]" value="<?php echo htmlspecialchars($app['filename']); ?>" id="app-<?php echo md5($app['filename']); ?>">
                        <label for="app-<?php echo md5($app['filename']); ?>">
                            <strong><?php echo htmlspecialchars($app['filename']); ?></strong>
                        </label>
                    </div>
                    <pre><?php echo htmlspecialchars($app['content']); ?></pre>
                </div>
            <?php endforeach; ?>
            
            <div class="action-buttons">
                <button type="submit" name="delete" onclick="return confirm('Вы уверены, что хотите удалить выбранные заявки?')">Удалить выбранные</button>
            </div>
        </form>
    <?php endif; ?>
    
    <div class="home-link">
        <a href="../index.php">Вернуться на страницу заявки</a>
    </div>
    
    <script src="../script/admin_script.js"></script>
</body>
</html>