<?php
// Инициализация переменных
$errors = [];
$success = false;
$firstname = $lastname = $email = $phone = $topic = $payment = '';
$newsletter = false;
$delimiter = '**'; // Выбран двойной символ для минимизации коллизий

// Обработка формы при отправке
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение и очистка данных
    $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $topic = isset($_POST['topic']) ? $_POST['topic'] : '';
    $payment = isset($_POST['payment']) ? $_POST['payment'] : '';
    $newsletter = isset($_POST['newsletter']) ? true : false;
    
    // Функция очистки от разделителя
    $sanitize = function($value) use ($delimiter) {
        return str_replace($delimiter, '', $value);
    };
    
    // Валидация имени и фамилии
    if (empty($firstname)) {
        $errors['firstname'] = 'Введите имя';
    } elseif (!preg_match('/^[a-zA-Zа-яёА-ЯЁ]+$/u', $firstname)) { // Добавлен модификатор u
        $errors['firstname'] = 'Имя должно содержать только буквы';
    }

    if (empty($lastname)) {
        $errors['lastname'] = 'Введите фамилию';
    } elseif (!preg_match('/^[a-zA-Zа-яёА-ЯЁ]+$/u', $lastname)) { // Добавлен модификатор u
        $errors['lastname'] = 'Фамилия должна содержать только буквы';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Введите email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Введите телефон';
    } elseif (!preg_match('/^[0-9]+$/', $phone)) {
        $errors['phone'] = 'Телефон должен содержать только цифры';
    }
    
    if (empty($topic)) {
        $errors['topic'] = 'Выберите тематику';
    }
    
    if (empty($payment)) {
        $errors['payment'] = 'Выберите метод оплаты';
    }
    
    // Если ошибок нет - сохранение
    if (empty($errors)) {
        $datetime = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? getenv('REMOTE_ADDR');
        
        // Очистка данных от разделителя
        $firstname = $sanitize($firstname);
        $lastname = $sanitize($lastname);
        $email = $sanitize($email);
        $phone = $sanitize($phone);
        $topic = $sanitize($topic);
        $payment = $sanitize($payment);
        
        // Формирование строки данных
        $data = implode($delimiter, [
            $datetime,
            $firstname,
            $lastname,
            $email,
            $phone,
            $topic,
            $payment,
            $newsletter ? 'Да' : 'Нет',
            $ip
        ]) . PHP_EOL;
        
        // Сохранение в файл
        $file = 'applications.txt';
        if (file_put_contents($file, $data, FILE_APPEND | LOCK_EX)) {
            $success = true;
        } else {
            $errors['general'] = 'Ошибка сохранения данных';
        }
    }
}
?>