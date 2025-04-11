<?php

// 1. Получение расширения файла
function getFileExtension($filename) {
    return preg_match('/\.([^.]+)$/', $filename, $matches) ? $matches[1] : '';
}

// 2. Проверка типа файла
function checkFileType($filename) {
    $ext = strtolower(getFileExtension($filename));
    $types = [
        'archive' => ['zip', 'rar', '7z'],
        'audio'   => ['mp3', 'wav', 'ogg'],
        'video'   => ['mp4', 'avi', 'mkv'],
        'image'   => ['jpg', 'png', 'gif']
    ];
    
    foreach ($types as $type => $extensions) {
        if (in_array($ext, $extensions)) return $type;
    }
    return 'unknown';
}

// 3. Получение содержимого <title>
function extractTitle($html) {
    return preg_match('/<title\b[^>]*>(.*?)<\/title>/is', $html, $matches) 
           ? $matches[1] 
           : '';
}

// 4. Поиск всех ссылок в <a>
function extractLinks($html) {
    preg_match_all('/<a\b[^>]*href\s*=\s*["\']?([^"\'\s>]+)/i', $html, $matches);
    return $matches[1] ?? [];
}

// 5. Поиск изображений в <img>
function extractImages($html) {
    preg_match_all('/<img\b[^>]*src\s*=\s*["\']?([^"\'\s>]+)/i', $html, $matches);
    return $matches[1] ?? [];
}

// 6. Подсветка строки
function highlightText($text, $search) {
    $pattern = '/' . preg_quote($search, '/') . '/i';
    return preg_replace($pattern, '<strong>$0</strong>', $text);
}

// 7. Замена смайлов на картинки
function replaceSmiles($text) {
    $smiles = [
        ':)' => 'smile.png',
        ';)' => 'wink.png',
        ':(' => 'sad.png'
    ];
    
    return preg_replace_callback('/(?::\)|;\)|:\()/i', function($m) use ($smiles) {
        $smile = $m[0];
        $src = $smiles[strtolower($smile)] ?? 'default.png';
        return "<img src=\"$src\" alt=\"$smile\">";
    }, $text);
}

// 8. Удаление повторяющихся пробелов
function cleanSpaces($text) {
    return preg_replace('/[ ]{2,}/', ' ', $text);
}

// Примеры использования:
echo getFileExtension("photo.jpg"); // jpg
echo checkFileType("archive.zip"); // archive
echo extractTitle("<html><title>Test Page</title></html>"); // Test Page
print_r(extractLinks('<a href="/page1">Link1</a> <a href="https://example.com">Link2</a>'));
print_r(extractImages('<img src="image1.jpg"><img src="photo.png">'));
echo highlightText("Hello world", "world"); // Hello <strong>world</strong>
echo replaceSmiles("Hello :) How are you? :("); // Вставит соответствующие img
echo cleanSpaces("This   is   a   test"); // This is a test
?>