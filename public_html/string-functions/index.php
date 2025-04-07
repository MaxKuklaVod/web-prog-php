<?php
// Включаем файл с логикой обработки формы
require_once 'script/process_form.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявка на участие в конференции</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Заявка на участие в конференции</h1>
    
    <?php if ($success): ?>
        <div class="success">Ваша заявка успешно принята! Спасибо за регистрацию.</div>
    <?php else: ?>
        <?php if (isset($errors['general'])): ?>
            <div class="error"><?php echo $errors['general']; ?></div>
        <?php endif; ?>
        
        <form id="conference-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label for="firstname">Имя* (только буквы):</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" 
                       pattern="[A-Za-zА-Яа-яЁё]+" title="Пожалуйста, используйте только буквы">
                <?php if (isset($errors['firstname'])): ?>
                    <div class="error"><?php echo $errors['firstname']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="lastname">Фамилия* (только буквы):</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>"
                       pattern="[A-Za-zА-Яа-яЁё]+" title="Пожалуйста, используйте только буквы">
                <?php if (isset($errors['lastname'])): ?>
                    <div class="error"><?php echo $errors['lastname']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Электронная почта*:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="phone">Телефон для связи* (только цифры):</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>"
                       pattern="[0-9]+" title="Пожалуйста, используйте только цифры">
                <?php if (isset($errors['phone'])): ?>
                    <div class="error"><?php echo $errors['phone']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label>Интересующая тематика конференции*:</label>
                <div>
                    <input type="radio" id="topic-business" name="topic" value="Бизнес" <?php echo ($topic === 'Бизнес') ? 'checked' : ''; ?>>
                    <label for="topic-business" style="display:inline">Бизнес</label>
                </div>
                <div>
                    <input type="radio" id="topic-tech" name="topic" value="Технологии" <?php echo ($topic === 'Технологии') ? 'checked' : ''; ?>>
                    <label for="topic-tech" style="display:inline">Технологии</label>
                </div>
                <div>
                    <input type="radio" id="topic-marketing" name="topic" value="Реклама и Маркетинг" <?php echo ($topic === 'Реклама и Маркетинг') ? 'checked' : ''; ?>>
                    <label for="topic-marketing" style="display:inline">Реклама и Маркетинг</label>
                </div>
                <?php if (isset($errors['topic'])): ?>
                    <div class="error"><?php echo $errors['topic']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="payment">Предпочитаемый метод оплаты*:</label>
                <select id="payment" name="payment">
                    <option value="">Выберите метод оплаты</option>
                    <option value="WebMoney" <?php echo ($payment === 'WebMoney') ? 'selected' : ''; ?>>WebMoney</option>
                    <option value="Яндекс.Деньги" <?php echo ($payment === 'Яндекс.Деньги') ? 'selected' : ''; ?>>Яндекс.Деньги</option>
                    <option value="PayPal" <?php echo ($payment === 'PayPal') ? 'selected' : ''; ?>>PayPal</option>
                    <option value="Кредитная карта" <?php echo ($payment === 'Кредитная карта') ? 'selected' : ''; ?>>Кредитная карта</option>
                </select>
                <?php if (isset($errors['payment'])): ?>
                    <div class="error"><?php echo $errors['payment']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <div>
                    <input type="checkbox" id="newsletter" name="newsletter" value="yes" <?php echo $newsletter ? 'checked' : ''; ?>>
                    <label for="newsletter" style="display:inline">Хочу получать рассылку о конференции</label>
                </div>
            </div>
            
            <button type="submit">Отправить заявку</button>
        </form>
    <?php endif; ?>
    
    <div class="admin-link">
        <a href="php/admin.php">Панель администратора</a>
    </div>
</body>
</html>