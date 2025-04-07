<?php require '../script/admin_logic.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора - Заявки на конференцию</title>
    <style>
        <?= file_get_contents('../css/admin_styles.css') ?>
        /* Стили для таблицы с прокруткой внутри ячеек */
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin: 20px 0;
        }
        
        table.applications {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        table.applications th,
        table.applications td {
            padding: 12px;
            border: 1px solid #ddd;
            vertical-align: top;
            position: relative;
        }
        
        /* Стили для прокручиваемых ячеек */
        td.scrollable {
            max-width: 250px;
            overflow: hidden;
            white-space: nowrap;
        }
        
        td.scrollable:hover {
            overflow-x: auto;
            white-space: normal;
        }
        
        /* Кастомизация полосы прокрутки */
        td.scrollable::-webkit-scrollbar {
            height: 8px;
        }
        
        td.scrollable::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        td.scrollable::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        td.scrollable::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Стили для конкретных столбцов */
        td.first-name-col { max-width: 150px; }
        td.last-name-col { max-width: 150px; }
        td.email-col { max-width: 200px; }
        td.phone-col { max-width: 150px; }
        td.topic-col { max-width: 180px; }
        td.ip-col { max-width: 150px; }
        
        /* Фиксированный заголовок */
        thead th {
            position: sticky;
            top: 0;
            background: #f9f9f9;
            z-index: 1;
        }
    </style>
</head>
<body>
    <h1>Панель администратора - Заявки на конференцию</h1>
    
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] === 'true'): ?>
        <div class="success">Выбранные заявки успешно удалены.</div>
    <?php endif; ?>
    
    <?php if (empty($applications)): ?>
        <div class="no-applications">Заявок пока нет.</div>
    <?php else: ?>
        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <div class="select-all">
                <input type="checkbox" id="select-all">
                <label for="select-all">Выбрать все</label>
            </div>
            
            <div class="table-container">
                <table class="applications">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Дата</th>
                            <th>Имя</th>
                            <th>Фамилия</th>
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Тематика</th>
                            <th>Оплата</th>
                            <th>Рассылка</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="applications[]" value="<?= $app['id'] ?>" id="app-<?= $app['id'] ?>">
                                    <label for="app-<?= $app['id'] ?>" class="visually-hidden">Выбрать заявку</label>
                                </td>
                                <td><?= htmlspecialchars($app['datetime']) ?></td>
                                <td class="scrollable first-name-col"><?= htmlspecialchars($app['firstname']) ?></td>
                                <td class="scrollable last-name-col"><?= htmlspecialchars($app['lastname']) ?></td>
                                <td class="scrollable email-col"><?= htmlspecialchars($app['email']) ?></td>
                                <td class="scrollable phone-col"><?= htmlspecialchars($app['phone']) ?></td>
                                <td class="scrollable topic-col"><?= htmlspecialchars($app['topic']) ?></td>
                                <td><?= htmlspecialchars($app['payment']) ?></td>
                                <td><?= $app['newsletter'] ? 'Да' : 'Нет' ?></td>
                                <td class="scrollable ip-col"><?= htmlspecialchars($app['ip']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="action-buttons">
                <button type="submit" name="delete" onclick="return confirm('Вы уверены, что хотите удалить выбранные заявки?')">Удалить выбранные</button>
            </div>
        </form>
    <?php endif; ?>
    
    <div class="home-link">
        <a href="../index.php">Вернуться на страницу заявки</a>
    </div>
    
    <script>
        // Выделение всех чекбоксов
        document.getElementById('select-all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>