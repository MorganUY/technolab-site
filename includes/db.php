<?php
// Подключение к БД
$host = "MySQL-8.4";
$username = "root";
$password = "";
$database = "TexnoLab";
 
$db = mysqli_connect($host, $username, $password, $database);

// Проверка подключения
if (!$db) {
    die("Ошибка подключения к базе данных");
}
?>