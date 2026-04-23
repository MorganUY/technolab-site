<?php
// Запускаем сессию для хранения данных пользователя
session_start(); 

// Устанавливаем куку с последним визитом (на 1 час)
$time_now = date("d.m.Y H:i:s");
setcookie('last_visit', $time_now, time() + (60 * 60), "/");

// Подключаем файл с настройками БД
require_once('db.php'); 

// Функция выполнения SQL-запроса
function dbquery($sql) {
    global $db;
    return mysqli_query($db, $sql);
}

// Функция подсчёта строк в результате запроса
function dbrows($result) {
    return mysqli_num_rows($result);
}

// Функция получения одной строки из результата в виде массива
function dbfetcha($result) {
    return mysqli_fetch_assoc($result);
}

// Функция проверки авторизации пользователя
function check_user() {
    // Если сессия не содержит метку входа - пользователь гость
    if (!isset($_SESSION['my_inside'])) {
        return '';
    } else {
        // Иначе возвращаем логин пользователя
        return $_SESSION['current_user'];
    }
}

// Функция добавления страницы в историю посещений
function add_to_history() {
    // Проверяем, авторизован ли пользователь
    if (!isset($_SESSION['my_inside'])) {
        return;
    }
    
    // Получаем имя текущей страницы и время
    $page_name = basename($_SERVER['PHP_SELF']);
    $visit_time = date("d.m.Y H:i:s");

    // Если массива истории нет - создаём
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = array();
    }

    // Добавляем страницу со временем, если она последняя в истории
    $last = end($_SESSION['history']);
    $last_page = is_array($last) ? $last['page'] : $last;
    if (empty($_SESSION['history']) || $last_page !== $page_name) {
        $_SESSION['history'][] = array('page' => $page_name, 'time' => $visit_time);
    }

    // Ограничиваем историю 10 последними страницами
    if (count($_SESSION['history']) > 10) {
        array_shift($_SESSION['history']);
    }
}
?>