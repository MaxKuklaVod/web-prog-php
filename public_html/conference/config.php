<?php
// Файл: config.php

// Учетные данные администратора
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', md5('sEcREt_PasSwoRD')); // Используем md5, как указано

// Время бездействия для автоматического выхода (в секундах)
define('SESSION_TIMEOUT_DURATION', 5 * 60); // 5 минут

// Файлы для хранения статистики
define('STATS_FILE', __DIR__ . '/data/statistics.json');

// Инициализация файла статистики, если он не существует
if (!file_exists(STATS_FILE)) {
    $initial_data = [
        'hits' => 0,
        'unique_ips' => [],
        'sessions_started' => 0
    ];
    if (!is_dir(__DIR__ . '/data')) {
        mkdir(__DIR__ . '/data', 0755, true);
    }
    file_put_contents(STATS_FILE, json_encode($initial_data, JSON_PRETTY_PRINT));
}
?>