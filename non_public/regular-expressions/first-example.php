<?php

// 1. Целое число
$pattern = '/^-?\d+$/';
var_dump(preg_match($pattern, "0"));         // 1 (ноль)
var_dump(preg_match($pattern, "+123"));      // 0 (знак + не поддерживается)
var_dump(preg_match($pattern, "0123"));      // 1 (ведущие нули разрешены)
var_dump(preg_match($pattern, "12.3"));      // 0 (дробное)
var_dump(preg_match($pattern, " 123 "));     // 0 (пробелы)

// 2. Набор букв и цифр (латиница)
$pattern = '/^[a-zA-Z0-9]+$/';
var_dump(preg_match($pattern, "A1b2C3"));    // 1
var_dump(preg_match($pattern, "a_b_c"));     // 0 (символ подчеркивания)
var_dump(preg_match($pattern, "Абв123"));    // 0 (кириллица)

// 3. Набор букв и цифр (латиница + кириллица)
$pattern = '/^[a-zA-Z0-9а-яА-Я]+$/u';
var_dump(preg_match($pattern, "Текст123"));  // 1
var_dump(preg_match($pattern, "Text_123"));  // 0 (символ подчеркивания)
var_dump(preg_match($pattern, "ДлинныйТекстСоСпецСимволами!@")); // 0

// 4. Домен
$pattern = '/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
var_dump(preg_match($pattern, "google.com"));       // 1
var_dump(preg_match($pattern, "sub.domain.co.uk")); // 1
var_dump(preg_match($pattern, "invalid_domain"));   // 0 (без точки)
var_dump(preg_match($pattern, "domain..com"));      // 0 (две точки)

// 5. Имя пользователя
$pattern = '/^[a-zA-Z][a-zA-Z0-9]{2,24}$/';
var_dump(preg_match($pattern, "A"));          // 0 (минимум 3 символа)
var_dump(preg_match($pattern, "Abcdefghijklmnopqrstuvwxyz123456")); // 0 (>25)
var_dump(preg_match($pattern, "User_123"));   // 0 (символ подчеркивания)

// 6. Пароль (буквы + цифры)
$pattern = '/^(?=.*[a-zA-Z])(?=.*\d).{8,}$/';
var_dump(preg_match($pattern, "Pass1234"));   // 1
var_dump(preg_match($pattern, "password"));   // 0 (без цифр)
var_dump(preg_match($pattern, "12345678"));   // 0 (без букв)
var_dump(preg_match($pattern, "Short1"));     // 0 (меньше 8 символов)

// 7. Пароль с спецсимволами
$pattern = '/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/';
var_dump(preg_match($pattern, "Pass123!"));   // 1
var_dump(preg_match($pattern, "Pass1234"));   // 0 (нет спецсимвола)
var_dump(preg_match($pattern, "pass123!"));   // 0 (нет заглавной буквы)

// 8. Дата YYYY-MM-DD
$pattern = '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/';
var_dump(preg_match($pattern, "2020-02-29")); // 1 (високосный год)
var_dump(preg_match($pattern, "2023-02-30")); // 0 (28/29 дней в феврале)
var_dump(preg_match($pattern, "2023-13-01")); // 0 (месяц >12)

// 9. Дата DD/MM/YYYY
$pattern = '/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/';
var_dump(preg_match($pattern, "31/12/2023")); // 1
var_dump(preg_match($pattern, "31/02/2023")); // 0 (февраль)
var_dump(preg_match($pattern, "00/12/2023")); // 0 (ноль дней)

// 10. Время HH:MM:SS
$pattern = '/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/';
var_dump(preg_match($pattern, "00:00:00"));   // 1
var_dump(preg_match($pattern, "23:59:59"));   // 1
var_dump(preg_match($pattern, "24:00:00"));   // 0 (час >23)
var_dump(preg_match($pattern, "12:60:00"));   // 0 (минута >59)

// 11. URL
$pattern = '/^https?:\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}\/?$/';
var_dump(preg_match($pattern, "http://example.com"));    // 1
var_dump(preg_match($pattern, "https://sub.domain.org")); // 1
var_dump(preg_match($pattern, "ftp://example.com"));     // 0 (не http/https)
var_dump(preg_match($pattern, "http://-invalid.com"));   // 0 (недопустимый символ)

// 12. E-mail
$pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
var_dump(preg_match($pattern, "user+tag@mail.com"));     // 1
var_dump(preg_match($pattern, "user@sub.domain.co.uk")); // 1
var_dump(preg_match($pattern, "user@.com"));             // 0 (пустой домен)
var_dump(preg_match($pattern, "user@domain"));           // 0 (без TLD)

// 13. IPv4
$pattern = '/^((25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)\.){3}(25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)$/';
var_dump(preg_match($pattern, "192.168.1.1"));   // 1
var_dump(preg_match($pattern, "255.255.255.255")); // 1
var_dump(preg_match($pattern, "256.0.0.0"));     // 0 (октет >255)
var_dump(preg_match($pattern, "192.168.1"));     // 0 (неполный адрес)

// 14. IPv6
$pattern = '/^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$/';
var_dump(preg_match($pattern, "2001:0db8:85a3:0000:0000:8a2e:0370:7334")); // 1
var_dump(preg_match($pattern, "::1"));            // 0 (сокращенная запись не поддерживается)
var_dump(preg_match($pattern, "2001:0db8:85a3:0:0:8a2e:0370:7334")); // 0 (недостаточно групп)

// 15. MAC-адрес
$pattern = '/^([0-9a-fA-F]{2}:){5}[0-9a-fA-F]{2}$/';
var_dump(preg_match($pattern, "00:1A:2B:3C:4D:5E")); // 1
var_dump(preg_match($pattern, "00-1A-2B-3C-4D-5E")); // 0 (дефис вместо двоеточия)
var_dump(preg_match($pattern, "00:1A:2B:3C:4D"));    // 0 (неполный адрес)

// 16. Российский мобильный номер
$pattern = '/^\+7\d{10}$/';
var_dump(preg_match($pattern, "+79021234567")); // 1
var_dump(preg_match($pattern, "+7(902)1234567")); // 0 (формат с скобками)
var_dump(preg_match($pattern, "89021234567"));   // 0 (без +7)

// 17. Номер кредитной карты
$pattern = '/^\d{4}( \d{4}){3}$/';
var_dump(preg_match($pattern, "4048 4323 9889 3301")); // 1
var_dump(preg_match($pattern, "4048-4323-9889-3301")); // 0 (разделитель дефис)
var_dump(preg_match($pattern, "4048432398893301"));    // 0 (без пробелов)

// 18. ИНН
$pattern = '/^\d{10}$|^\d{12}$/';
var_dump(preg_match($pattern, "1234567890"));    // 1 (10 цифр)
var_dump(preg_match($pattern, "123456789012"));  // 1 (12 цифр)
var_dump(preg_match($pattern, "123456789"));     // 0 (9 цифр)
var_dump(preg_match($pattern, "12345678901"));   // 0 (11 цифр)

// 19. Почтовый индекс
$pattern = '/^\d{6}$/';
var_dump(preg_dump(preg_match($pattern, "123456"));    // 1
var_dump(preg_match($pattern, "12345"));       // 0 (5 цифр)
var_dump(preg_match($pattern, "1234567"));     // 0 (7 цифр)
var_dump(preg_match($pattern, "123 456"));     // 0 (пробел)

// 20. Цена в рублях
$pattern = '/^\d+,\d{2} руб\.$/';
var_dump(preg_match($pattern, "123,45 руб.")); // 1
var_dump(preg_match($pattern, "123 руб."));     // 0 (нет копеек)
var_dump(preg_match($pattern, "123.45 руб."));  // 0 (точка вместо запятой)

// 21. Цена в долларах
$pattern = '/^\$\d+\.\d{2}$/';
var_dump(preg_match($pattern, "$100.00")); // 1
var_dump(preg_match($pattern, "$100"));     // 0 (нет центов)
var_dump(preg_match($pattern, "$100,00"));  // 0 (запятая вместо точки)