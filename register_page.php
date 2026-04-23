<?php 
// Подключаем функции (сессия, куки, работа с БД)
require_once('includes/functions.php'); 
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация — ТехноЛаб</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/validation.js"></script>
</head>
<body>
    <header class="header">
        <div class="container header__container">
            <div class="logo-group"><?php include('top/logo.php'); ?></div>
            <?php include('top/menu.php'); ?>
        </div>
    </header>

    <main class="container" style="margin-top: 50px; max-width: 400px;">
        <form name="regForm" method="post" action="includes/register.php" style="background: #f4f4f4; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);" onsubmit="return validateRegistration();">
            <h2 style="text-align: center;">Новый аккаунт</h2>
            <br>
            
            <input type="text" name="reg_login" placeholder="Придумайте логин" required style="width: 100%; margin-bottom: 15px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            
            <input type="password" name="reg_password" placeholder="Придумайте пароль" required style="width: 100%; margin-bottom: 15px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            
            <input type="email" name="email" placeholder="Email" required style="width: 100%; margin-bottom: 15px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            
            <input type="text" name="zip" placeholder="Почтовый индекс" required pattern="[0-9]{6}" title="6 цифр" style="width: 100%; margin-bottom: 15px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            
            <input type="tel" name="reg_phone" placeholder="Телефон" pattern="[\d\+\-\s\(\)]{3,}" required title="Введите только цифры (мин 3)" style="width: 100%; margin-bottom: 15px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            
            <input type="text" name="reg_address" placeholder="Адрес" required style="width: 100%; margin-bottom: 15px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            
            <input type="submit" value="Создать профиль" class="nav__link--btn" style="width: 100%; padding: 10px; border: none; cursor: pointer; background: var(--brand-blue); color: white;">
            
            <?php 
            // Показываем ошибки, если они переданы через URL
            if(isset($_GET['error'])) {
                if($_GET['error'] == 'user_exists') echo "<p style='color:red; font-size:12px; margin-top:10px; text-align:center;'>Этот логин уже занят!</p>";
                if($_GET['error'] == 'empty') echo "<p style='color:red; font-size:12px; margin-top:10px; text-align:center;'>Заполните все поля!</p>";
                if($_GET['error'] == 'phone_invalid') echo "<p style='color:red; font-size:12px; margin-top:10px; text-align:center;'>Введите корректный телефон (только цифры)!</p>";
            }
            ?>
            
            <p style="text-align: center; margin-top: 15px; font-size: 14px;">
                Уже есть аккаунт? <a href="login_page.php" style="color: var(--brand-blue);">Войти</a>
            </p>
        </form>
    </main>
</body>
</html>