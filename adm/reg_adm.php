<?php
require_once('../includes/functions.php'); // Внимание на путь ../

if (isset($_POST['reg_admin'])) {
    $login = mysqli_real_escape_string($db, $_POST['login']);
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    
    // Автоматически ставим роль 1 (Администратор)
    $sql = "INSERT INTO users2 (username, password, role, phone, address) VALUES ('$login', '$pass', 1, '', '')";
    
    if (mysqli_query($db, $sql)) {
        header("Location: panel_adm.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация администратора</title>
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
            <h2 style="text-align:center; margin-bottom:20px;">Регистрация Администратора</h2>
            <?php if(isset($_POST['reg_admin']) && mysqli_query($db, $sql)) { ?>
                <p style='color:green; text-align:center;'>Администратор зарегистрирован!</p>
            <?php } ?>
            <form method="post">
                <input type="text" name="login" placeholder="Логин админа" required style="width:100%; padding:12px; margin:10px 0; border:1px solid #ddd; border-radius:6px;"><br>
                <input type="password" name="pass" placeholder="Пароль" required style="width:100%; padding:12px; margin:10px 0; border:1px solid #ddd; border-radius:6px;"><br>
                <button type="submit" name="reg_admin" style="width:100%; margin-top:10px; padding:10px; background:#1E40AF; color:white; border:none; border-radius:4px; cursor:pointer;">Создать админа</button>
            </form>
            <p style="text-align:center; margin-top:20px;"><a href="../index.php">На главную</a> | <a href="auth_adm.php">Вход для админа</a></p>
        </div>
    </main>
</body>
</html>