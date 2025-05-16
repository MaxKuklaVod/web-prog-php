<?php
// public/index.php
session_start(); // Для сообщений пользователю

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/Controllers/TaskController.php';

// Простой маршрутизатор
$action = $_GET['action'] ?? 'index'; // По умолчанию - главная страница
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

$controller = new TaskController();

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'show':
        if ($id) {
            $controller->show($id);
        } else {
            // Обработка ошибки - ID не указан
            header("Location: index.php"); // или показать страницу ошибки
            exit;
        }
        break;
    case 'update': // Обработка POST-запроса на обновление
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update($id);
        } else {
             // Если не POST или нет ID, редирект или ошибка
            header("Location: index.php?action=show&id=" . $id);
            exit;
        }
        break;
    case 'complete':
        if ($id) {
            $controller->complete($id);
        } else {
            header("Location: index.php");
            exit;
        }
        break;
    // POST для 'create' обрабатывается внутри $controller->index()
    default:
        // Страница не найдена или действие по умолчанию
        $controller->index();
        break;
}
?>