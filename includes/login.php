<?php
// Подключаем функции для работы с БД
require_once('functions.php'); 

// Проверяем, что данные пришли из формы входа
if (isset($_POST['login']) && isset($_POST['password'])) {
    // Очищаем входные данные от лишних пробелов
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    // Защита от SQL-инъекций
    $safe_login = mysqli_real_escape_string($db, $login);
    // Ищем пользователя в БД по логину
    $sql = "SELECT username, password FROM users2 WHERE username='$safe_login' LIMIT 1";
    $result = dbquery($sql);

    // Если пользователь найден
    if ($result && dbrows($result) > 0) {
        $data = dbfetcha($result);
        
        // Сравниваем пароли
        if (password_verify($password, $data['password'])) {
            // Получаем ID пользователя
            $id_sql = "SELECT id FROM users2 WHERE username='$safe_login' LIMIT 1";
            $id_result = dbquery($id_sql);
            $user_data = dbfetcha($id_result);
            
            // Успех: записываем данные в сессию
            $_SESSION['my_inside'] = 1; 
            $_SESSION['current_user'] = $data['username'];
            $_SESSION['user_id'] = $user_data['id'];
            
            // Добавляем первую страницу в историю
            add_to_history();
            
            // Перенаправляем на главную
            header('Location: ../index.php'); 
            exit;
        } else {
            // Неверный пароль
            header('Location: ../login_page.php?error=no_user');
            exit;
        }
    } else {
        // Пользователь не найден
        header('Location: ../login_page.php?error=wrong_pass');
        exit;
    }
} else {
    // Если з��шли напрямую без формы - редирект на страницу входа
    header('Location: ../login_page.php');
    exit;
}
?>