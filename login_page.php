<?php 
// Подключаем функции (сессия, куки, работа с БД)
require_once('includes/functions.php'); 
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход — ТехноЛаб</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container header__container">
            <div class="logo-group"><?php include('top/logo.php'); ?></div>
            <?php include('top/menu.php'); ?>
        </div>
    </header>

    <main class="container" style="margin-top: 50px;">
        <h2 style="text-align: center;">Авторизация</h2>
        
        <?php 
        // Показываем ошибки, если они переданы через URL
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'wrong_pass') echo '<p style="color:red; text-align:center;">Неверный пароль!</p>';
            if ($_GET['error'] == 'no_user') echo '<p style="color:red; text-align:center;">Пользователь не найден!</p>';
        }
        ?>
        
        <form method="post" action="includes/login.php" style="max-width: 400px; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 8px;">
            <div style="margin-bottom: 15px;">
                <label>Логин:</label><br>
                <input type="text" name="login" style="width: 100%; padding: 8px;" required>
            </div>
            <div style="margin-bottom: 15px;">
                <label>Пароль:</label><br>
                <input type="password" name="password" style="width: 100%; padding: 8px;" required>
            </div>
            <div style="text-align: center;">
                <input type="submit" value="Войти" class="nav__link--btn" style="cursor: pointer; border: none; padding: 10px 20px;">
                <p>Нет аккаунта? <a href="register_page.php">Зарегистрируйтесь</a></p>
            </div>
        </form>
    </main>
</body>
</html>