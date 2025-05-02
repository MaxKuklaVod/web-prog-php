<?php
require_once 'config.php';
require_once 'includes/functions.php';

trackStatistics($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Валидация имени
    $name = $_POST['name'];
    if (!preg_match('/^[А-Яа-яЁёA-Za-z\s]+$/u', $name)) {
        header("Location: index.php?error=invalid_name");
        exit;
    }
    
    // Валидация телефона
    $phone = $_POST['phone'];
    if (!preg_match('/^[\d\+]+$/', $phone)) {
        header("Location: index.php?error=invalid_phone");
        exit;
    }
    
    // Остальные поля
    $email = $conn->real_escape_string($_POST['email']);
    $topic = $conn->real_escape_string($_POST['topic']);
    $message = $conn->real_escape_string($_POST['message']);
    
    // Экранирование проверенных полей
    $name = $conn->real_escape_string($name);
    $phone = $conn->real_escape_string($phone);
    
    $sql = "INSERT INTO applications (name, email, phone, topic, message) 
            VALUES ('$name', '$email', '$phone', '$topic', '$message')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?success=1");
    } else {
        header("Location: index.php?error=db_error");
    }
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>