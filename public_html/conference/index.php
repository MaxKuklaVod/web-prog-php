<?php
require_once 'config.php';
require_once 'includes/functions.php';

trackStatistics($conn);
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Конференция по веб-технологиям 2023</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Конференция по веб-технологиям 2023</h1>
        <p>Присоединяйтесь к ведущим экспертам отрасли</p>
    </header>
    
    <main>
        <section class="about">
            <h2>О конференции</h2>
            <p>Ежегодная конференция, посвященная последним тенденциям в веб-разработке, дизайне и цифровым технологиям.</p>
        </section>
        
        <section class="application-form">
            <h2>Подать заявку на участие</h2>
            <form action="submit.php" method="POST">
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="name">ФИО:</label>
                    <input type="text" id="name" name="name" required 
                        pattern="[А-Яа-яЁёA-Za-z\s]+" 
                        title="Только буквы и пробелы (без цифр и символов)">
                </div>

                <div class="form-group">
                    <label for="phone">Телефон:</label>
                    <input type="tel" id="phone" name="phone" required
                        pattern="[\d\+]+" 
                        title="Только цифры и знак +">
                </div>
                
                <div class="form-group">
                    <label for="topic">Тема доклада:</label>
                    <input type="text" id="topic" name="topic" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Дополнительная информация:</label>
                    <textarea id="message" name="message" rows="4"></textarea>
                </div>
                
                <button type="submit">Отправить заявку</button>
            </form>
        </section>
    </main>
    
    <footer>
        <p>&copy; Конференция по веб-технологиям 2023</p>
        <p>Администратор: <a href="admin.php">Вход</a></p>
    </footer>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-message">
            <?php
            switch ($_GET['error']) {
                case 'invalid_name':
                    echo "Ошибка: Имя содержит недопустимые символы";
                    break;
                case 'invalid_phone':
                    echo "Ошибка: Телефон должен содержать только цифры";
                    break;
                case 'db_error':
                    echo "Ошибка базы данных, попробуйте позже";
                    break;
                default:
                    echo "Произошла ошибка";
            }
            ?>
        </div>
    <?php endif; ?>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            
            // Проверка имени (только буквы и пробелы)
            if (!/^[А-Яа-яЁёA-Za-z\s]+$/.test(name)) {
                alert('Имя может содержать только буквы и пробелы');
                e.preventDefault();
                return;
            }
            
            // Проверка телефона (только цифры и +)
            if (!/^[\d\+]+$/.test(phone)) {
                alert('Телефон может содержать только цифры и знак +');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>