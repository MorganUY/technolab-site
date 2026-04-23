<?php
require_once('includes/functions.php');

// Если пользователь не залогинен
if (!isset($_SESSION['my_inside'])) {
    header("Location: login_page.php");
    exit;
}

$message = "";

if (isset($_POST['change_pass'])) {
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass === $confirm_pass) {
        $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
        $username = $_SESSION['current_user'];
        
        // Получаем ID пользователя
        $sql = "SELECT id FROM users2 WHERE username = '$username'";
        $result = dbquery($sql);
        $data = dbfetcha($result);
        $uid = $data['id'];

        // Обновляем пароль в базе и очищаем токен
        $sql = "UPDATE users2 SET password = '$hashed_password', reset_token = NULL WHERE id = '$uid'";
        
        if (dbquery($sql)) {
            unset($_SESSION['temp_pass']);
            $message = "<p style='color:green;'>Пароль успешно изменен! <a href='profile.php'>Вернуться в профиль</a></p>";
        }
    } else {
        $message = "<p style='color:red;'>Пароли не совпадают!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Смена пароля</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container header__container">
            <div class="logo-group"><?php include('top/logo.php'); ?></div>
            <?php include('top/menu.php'); ?>
        </div>
    </header>
    
    <div class="container" style="margin-top: 100px; max-width: 400px;">
        <h2>Установка нового пароля</h2>
        <?php echo $message; ?>
        
        <form method="post">
            <p>Введите новый постоянный пароль:</p>
            <input type="password" name="new_password" placeholder="Новый пароль" required style="width:100%; padding:10px; margin-bottom:10px;">
            <input type="password" name="confirm_password" placeholder="Повторите пароль" required style="width:100%; padding:10px; margin-bottom:10px;">
            <button type="submit" name="change_pass" class="btn">Сохранить новый пароль</button>
        </form>
    </div>
</body>
</html>