<?php
// app/Controllers/TaskController.php
require_once __DIR__ . '/../Models/Task.php';

class TaskController {
    private $taskModel;

    public function __construct() {
        $this->taskModel = new Task();
    }

    private function view($viewName, $data = []) {
        extract($data); // Делает ключи массива доступными как переменные
        $content = __DIR__ . '/../Views/' . $viewName . '.php';
        require __DIR__ . '/../Views/layout.php';
    }
    
    // Главная страница и создание задачи
    public function index() {
        $message = $_SESSION['message'] ?? null;
        unset($_SESSION['message']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_task') {
            // Простая валидация
            if (empty($_POST['title']) || empty($_POST['event_datetime']) || empty($_POST['duration_minutes'])) {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Заполните все обязательные поля!'];
            } else {
                $data = [
                    'title' => trim($_POST['title']),
                    'type' => $_POST['type'],
                    'location' => trim($_POST['location']),
                    'event_datetime' => $_POST['event_datetime'],
                    'duration_minutes' => (int)$_POST['duration_minutes'],
                    'comment' => trim($_POST['comment'])
                ];
                if ($this->taskModel->create($data)) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Задача успешно добавлена!'];
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Ошибка при добавлении задачи.'];
                }
            }
            header("Location: index.php"); // Перенаправление для избежания повторной отправки формы
            exit;
        }

        // По умолчанию показываем текущие (не просроченные, ожидающие) задачи
        $filter = $_GET['filter'] ?? 'current';
        $date_filter = null;
        if ($filter === 'date' && !empty($_GET['date_filter'])) {
            $date_filter = $_GET['date_filter'];
        }
        
        $tasks = $this->taskModel->getAll($filter, $date_filter);
        $this->view('home', ['tasks' => $tasks, 'current_filter' => $filter, 'date_filter_value' => $date_filter, 'message' => $message]);
    }

    // Показ карточки задачи для редактирования
    public function show($id) {
        $message = $_SESSION['message'] ?? null;
        unset($_SESSION['message']);

        $task = $this->taskModel->getById($id);
        if (!$task) {
            // Обработка случая, когда задача не найдена
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Задача не найдена.'];
            header("Location: index.php");
            exit;
        }
        $this->view('task_detail', ['task' => $task, 'message' => $message]);
    }

    // Обновление задачи
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             // Простая валидация
            if (empty($_POST['title']) || empty($_POST['event_datetime']) || empty($_POST['duration_minutes'])) {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Заполните все обязательные поля!'];
            } else {
                $data = [
                    'title' => trim($_POST['title']),
                    'type' => $_POST['type'],
                    'location' => trim($_POST['location']),
                    'event_datetime' => $_POST['event_datetime'],
                    'duration_minutes' => (int)$_POST['duration_minutes'],
                    'comment' => trim($_POST['comment']),
                    'status' => $_POST['status'] ?? 'pending' // Важно для отметки о выполнении
                ];
                if ($this->taskModel->update($id, $data)) {
                     $_SESSION['message'] = ['type' => 'success', 'text' => 'Задача успешно обновлена!'];
                } else {
                     $_SESSION['message'] = ['type' => 'error', 'text' => 'Ошибка при обновлении задачи.'];
                }
            }
            header("Location: index.php?action=show&id=" . $id); // Возврат на карточку задачи
            exit;
        }
        // Если не POST, перенаправить куда-нибудь или показать ошибку
        header("Location: index.php?action=show&id=" . $id);
        exit;
    }
    
    public function complete($id) {
        if ($this->taskModel->markCompleted($id)) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Задача отмечена как выполненная!'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Ошибка при отметке задачи.'];
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php'; // Вернуться на предыдущую страницу
        header("Location: " . $referer);
        exit;
    }
}
?>