<?php
// Файл: stats_helper.php

require_once 'config.php';

function get_stats() {
    if (!file_exists(STATS_FILE)) {
        return ['hits' => 0, 'unique_ips' => [], 'sessions_started' => 0];
    }
    $data = json_decode(file_get_contents(STATS_FILE), true);
    return is_array($data) ? $data : ['hits' => 0, 'unique_ips' => [], 'sessions_started' => 0];
}

function update_stats($stats) {
    file_put_contents(STATS_FILE, json_encode($stats, JSON_PRETTY_PRINT));
}

function record_visit() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $stats = get_stats();

    // 1. Подсчет загрузок страницы (хитов)
    $stats['hits']++;

    // 2. Подсчет уникальных IP
    $current_ip = $_SERVER['REMOTE_ADDR'];
    if (!in_array($current_ip, $stats['unique_ips'])) {
        $stats['unique_ips'][] = $current_ip;
    }

    // 3. Подсчет пользователей по сессиям (новых сессий)
    if (!isset($_SESSION['session_counted'])) {
        $stats['sessions_started']++;
        $_SESSION['session_counted'] = true; // Помечаем, что эта сессия уже посчитана
    }

    update_stats($stats);
}

function get_unique_ip_count() {
    $stats = get_stats();
    return count($stats['unique_ips']);
}

function get_total_hits() {
    $stats = get_stats();
    return $stats['hits'];
}

function get_sessions_started_count() {
    $stats = get_stats();
    return $stats['sessions_started'];
}
?>