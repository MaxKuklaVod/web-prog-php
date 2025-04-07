<?php
// admin_logic.php

define('DATA_FILE', '../applications.txt');
define('DELIMITER', '**');
define('STATUS_ACTIVE', '1');
define('STATUS_DELETED', '0');

// Чтение активных заявок
function readApplications() {
    $applications = [];
    
    if (!file_exists(DATA_FILE)) return $applications;
    
    $lines = file(DATA_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        $data = explode(DELIMITER, $line);
        
        // Пропускаем некорректные и удаленные заявки
        if (count($data) < 9 || end($data) == STATUS_DELETED) continue;
        
        // Парсим данные
        $applications[] = [
            'id' => md5($line), // Уникальный идентификатор
            'datetime' => $data[0],
            'firstname' => $data[1],
            'lastname' => $data[2],
            'email' => $data[3],
            'phone' => $data[4],
            'topic' => $data[5],
            'payment' => $data[6],
            'newsletter' => $data[7] == 'Да',
            'ip' => $data[8],
            'raw_line' => $line // Сохраняем исходную строку для обновления
        ];
    }
    
    return $applications;
}

// Пометка заявок как удаленные
function markAsDeleted($ids) {
    if (!file_exists(DATA_FILE) || empty($ids)) return false;
    
    $lines = file(DATA_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $updated = false;
    
    foreach ($lines as &$line) {
        $hash = md5($line);
        if (in_array($hash, $ids)) {
            $data = explode(DELIMITER, $line);
            
            // Добавляем/обновляем статус
            if (count($data) == 9) {
                $data[] = STATUS_DELETED;
            } else {
                $data[count($data)-1] = STATUS_DELETED;
            }
            
            $line = implode(DELIMITER, $data);
            $updated = true;
        }
    }
    
    if ($updated) {
        file_put_contents(DATA_FILE, implode(PHP_EOL, $lines));
    }
    
    return $updated;
}

// Обработка удаления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $ids = $_POST['applications'] ?? [];
    if (markAsDeleted($ids)) {
        header('Location: admin.php?deleted=1');
        exit;
    }
}

$applications = readApplications();
?>