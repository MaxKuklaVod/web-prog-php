<?php
// Путь к директории с заявками
$applicationDir = '../applications';

// Обработка удаления заявок
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && !empty($_POST['applications'])) {
    foreach ($_POST['applications'] as $filename) {
        // Проверка на инъекции пути
        $filename = basename($filename);
        $filePath = $applicationDir . '/' . $filename;
        
        // Проверка существования файла и его удаление
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
    // Перенаправление для сброса POST-данных
    header('Location: ' . $_SERVER['PHP_SELF'] . '?deleted=true');
    exit;
}

// Получение списка всех заявок
$applications = [];
if (is_dir($applicationDir)) {
    $files = scandir($applicationDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
            $applications[] = [
                'filename' => $file,
                'path' => $applicationDir . '/' . $file,
                'content' => file_get_contents($applicationDir . '/' . $file),
                'time' => filemtime($applicationDir . '/' . $file)
            ];
        }
    }
    
    // Сортировка заявок по времени (новые сверху)
    usort($applications, function($a, $b) {
        return $b['time'] - $a['time'];
    });
}
?>