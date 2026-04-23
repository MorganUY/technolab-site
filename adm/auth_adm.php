<?php
require_once('../includes/functions.php');

if (isset($_POST['login_admin'])) {
    $login = mysqli_real_escape_string($db, $_POST['login']);
    $pass = $_POST['pass'];

    $res = mysqli_query($db, "SELECT * FROM users2 WHERE username = '$login' AND role = 1");
    $admin = mysqli_fetch_assoc($res);

    if ($admin && password_verify($pass, $admin['password'])) {
        $_SESSION['my_inside'] = 1;
        $_SESSION['current_user'] = $admin['username'];
        $_SESSION['is_admin'] = 1;
        header("Location: panel_adm.php");
    } else {
        echo "Ошибка: Неверный логин или у вас нет прав доступа!";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход для администратора</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="header">
        <div class="container header__container">
            <div class="logo-group"><?php include('../top/logo.php'); ?></div>
        </div>
    </header>
    <main class="container">
        <div style="max-width:400px; margin:50px auto; padding:30px; background:#f9f9f9; border-radius:12px;">
            <h2 style="text-align:center; margin-bottom:20px;">Вход в Панель Управления</h2>
            <?php if(isset($_POST['login_admin']) && !isset($admin)) { ?>
                <p style='color:red; text-align:center;'>Ошибка: Неверный логин или пароль!</p>
            <?php } ?>
            <form method="post">
                <input type="text" name="login" required placeholder="Логин" style="width:100%; padding:12px; margin:10px 0; border:1px solid #ddd; border-radius:6px;"><br>
                <input type="password" name="pass" required placeholder="Пароль" style="width:100%; padding:12px; margin:10px 0; border:1px solid #ddd; border-radius:6px;"><br>
                <button type="submit" name="login_admin" style="width:100%; margin-top:10px; padding:10px; background:#1E40AF; color:white; border:none; border-radius:4px; cursor:pointer;">Войти как админ</button>
            </form>
            <p style="text-align:center; margin-top:20px;"><a href="../index.php">На главную</a> | <a href="reg_adm.php">Регистрация</a></p>
        </div>
    </main>
</body>
</html>