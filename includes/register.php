<?php
// Подключаем функции для работы с БД
require_once('functions.php'); 

// Проверяем, что поля заполнены
if (isset($_POST['reg_login']) && isset($_POST['reg_password']) && !empty($_POST['reg_login']) && !empty($_POST['reg_password'])) {
    // Валидация телефона - только цифры, + - ( )
    $phone = trim($_POST['reg_phone']);
    if (!preg_match('/^[\d\+\-\s\(\)]{3,}$/', $phone)) {
        header('Location: ../register_page.php?error=phone_invalid');
        exit;
    }
    
    // Очищаем данные и шифруем пароль
    $username = mysqli_real_escape_string($db, trim($_POST['reg_login']));
    $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);
    $email = mysqli_real_escape_string($db, trim($_POST['email']));
    $zip = mysqli_real_escape_string($db, trim($_POST['zip']));
    $phone = mysqli_real_escape_string($db, $phone);
    $address = mysqli_real_escape_string($db, trim($_POST['reg_address']));

    // Проверяем, не занят ли логин
    $check_sql = "SELECT id FROM users2 WHERE username = '$username'";
    $res = dbquery($check_sql);

    // Если логин уже есть
    if (dbrows($res) > 0) {
        header('Location: ../register_page.php?error=user_exists');
        exit;
    } else {
        // Добавляем нового пользователя в БД
        $insert_sql = "INSERT INTO users2 (username, password, email, zip, phone, address) VALUES ('$username', '$password', '$email', '$zip', '$phone', '$address')";
        dbquery($insert_sql);

        // Автоматически авторизуем нового пользователя
        $_SESSION['my_inside'] = 1;
        $_SESSION['current_user'] = $username;

        // Перенаправляем в профиль
        header('Location: ../profile.php?reg_success=1');
        exit;
    }
} else {
    // Если поля пустые
    header('Location: ../register_page.php?error=empty');
    exit;
}
?>