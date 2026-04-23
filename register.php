<?php
require_once('includes/db.php'); 

if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $login = mysqli_real_escape_string($db, $_POST['username']);
    // Хэш пароля, 
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $address = mysqli_real_escape_string($db, $_POST['address']);

    // Запрос к users2
    $sql = "INSERT INTO users2 (username, password, phone, address, role) 
            VALUES ('$login', '$pass', '$phone', '$address', 0)";
    
    if (mysqli_query($db, $sql)) {
        header('Location: login_page.php?reg_success=1');
    } else {
        echo "Ошибка: " . mysqli_error($db);
    }
}
?>

<script src="js/validation.js"></script>

<form name="regForm" action="register.php" method="POST" onsubmit="return validateRegistration();">
    <input type="text" name="reg_login" placeholder="Логин">
    <input type="password" name="reg_password" placeholder="Пароль">
    
    <input type="text" name="email" placeholder="Email (example@mail.com)">
    <input type="text" name="zip" placeholder="Почтовый индекс (цифры)">
    
    <button type="submit">Зарегистрироваться</button>
</form>