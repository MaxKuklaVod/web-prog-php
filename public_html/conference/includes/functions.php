<?php
function isAdminLoggedIn() {
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        // Проверяем время бездействия
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
            session_unset();
            session_destroy();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
    }
    return false;
}

// Функция для подсчета статистики
function trackStatistics($conn) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $session_id = session_id();
    
    // Проверяем уникальность IP
    $stmt = $conn->prepare("INSERT INTO statistics (ip_address, session_id) VALUES (?, ?) 
                           ON DUPLICATE KEY UPDATE page_views = page_views + 1, last_activity = NOW()");
    $stmt->bind_param("ss", $ip, $session_id);
    $stmt->execute();
    $stmt->close();
}

// Получение статистики
function getStatistics($conn) {
    $stats = [];
    
    // Уникальные IP
    $result = $conn->query("SELECT COUNT(DISTINCT ip_address) as unique_ips FROM statistics");
    $stats['unique_ips'] = $result->fetch_assoc()['unique_ips'];
    
    // Уникальные сессии
    $result = $conn->query("SELECT COUNT(DISTINCT session_id) as unique_sessions FROM statistics");
    $stats['unique_sessions'] = $result->fetch_assoc()['unique_sessions'];
    
    // Все хиты
    $result = $conn->query("SELECT SUM(page_views) as total_hits FROM statistics");
    $stats['total_hits'] = $result->fetch_assoc()['total_hits'];
    
    return $stats;
}

?>