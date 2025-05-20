<?php
// Файл: logout.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Уничтожаем все переменные сессии.
$_SESSION = array();

// Если требуется уничтожить сессию полностью, удалите также cookie сессии.
// Замечание: Это уничтожит сессию, а не только данные сессии!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Наконец, уничтожаем сессию.
session_destroy();

// Перенаправляем на страницу входа с сообщением
header('Location: login.php?logged_out=1');
exit;
?>