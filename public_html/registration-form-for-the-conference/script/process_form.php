<?php
// Инициализация переменных
$errors = [];
$success = false;
$firstname = $lastname = $email = $phone = $topic = $payment = '';
$newsletter = false;

// Обработка формы при отправке
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $topic = isset($_POST['topic']) ? $_POST['topic'] : '';
    $payment = isset($_POST['payment']) ? $_POST['payment'] : '';
    $newsletter = isset($_POST['newsletter']) ? true : false;
    
    // Валидация полей
    if (empty($firstname)) {
        $errors['firstname'] = 'Пожалуйста, введите ваше имя';
    } elseif (!preg_match('/^[A-Za-zА-Яа-яЁё]+$/', $firstname)) {
        $errors['firstname'] = 'Имя должно содержать только буквы';
    }
    
    if (empty($lastname)) {
        $errors['lastname'] = 'Пожалуйста, введите вашу фамилию';
    } elseif (!preg_match('/^[A-Za-zА-Яа-яЁё]+$/', $lastname)) {
        $errors['lastname'] = 'Фамилия должна содержать только буквы';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Пожалуйста, введите электронную почту';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Пожалуйста, введите корректный email';
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Пожалуйста, введите телефон для связи';
    } elseif (!preg_match('/^[0-9]+$/', $phone)) {
        $errors['phone'] = 'Телефон должен содержать только цифры';
    }
    
    if (empty($topic)) {
        $errors['topic'] = 'Пожалуйста, выберите тематику';
    }
    
    if (empty($payment)) {
        $errors['payment'] = 'Пожалуйста, выберите метод оплаты';
    }
    
    // Если ошибок нет, сохраняем данные в файл
    if (empty($errors)) {
        // Текущая дата и время
        $datetime = date('Y-m-d H:i:s');
        
        // Создание уникального имени файла
        $filename = 'applications/application_' . date('Ymd_His') . '_' . uniqid() . '.txt';
        
        // Создание директории, если она не существует
        if (!is_dir('applications')) {
            mkdir('applications', 0777, true);
        }
        
        // Формирование содержимого файла
        $content = "Дата заявки: $datetime\n";
        $content .= "Имя: $firstname\n";
        $content .= "Фамилия: $lastname\n";
        $content .= "Email: $email\n";
        $content .= "Телефон: $phone\n";
        $content .= "Тематика: $topic\n";
        $content .= "Метод оплаты: $payment\n";
        $content .= "Подписка на рассылку: " . ($newsletter ? 'Да' : 'Нет') . "\n";
        
        // Сохранение данных в файл
        if (file_put_contents($filename, $content)) {
            $success = true;
        } else {
            $errors['general'] = 'Ошибка при сохранении данных';
        }
    }
}
?>