<?php
require_once('includes/functions.php');


if (!isset($_SESSION['my_inside'])) {
    header("Location: login_page.php");
    exit;
}

$username = $_SESSION['current_user'];
$message = "";

 
if (isset($_POST['send_callback'])) {
    // Получаем и фильтруем данные
    $c_name = mysqli_real_escape_string($db, $_POST['c_name']);
    $c_phone = mysqli_real_escape_string($db, $_POST['c_phone']);
    $status = "Новая"; // Поле "Статус" по заданию
    $comment = mysqli_real_escape_string($db, $_POST['c_comment']); // Поле "Примечание"

    if (!empty($c_name) && !empty($c_phone)) {
        // Получаем ID пользователя
        $sql = "SELECT id FROM users2 WHERE username = '$username'";
        $result = dbquery($sql);
        $data = dbfetcha($result);
        $uid = $data['id'];
        
        // таблица callback_requests по ID
        $sql = "INSERT INTO callback_requests (user_id, customer_name, phone_number, status, comment) 
                VALUES ('$uid', '$c_name', '$c_phone', '$status', '$comment')";
        
        if (mysqli_query($db, $sql)) {
            $message = "<div class='alert success'>Заявка успешно отправлена! Наши менеджеры свяжутся с вами.</div>";
        } else {
            $message = "<div class='alert error'>Ошибка базы данных: " . mysqli_error($db) . "</div>";
        }
    } else {
        $message = "<div class='alert error'>Пожалуйста, заполните обязательные поля (Имя и Телефон).</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ТехноЛаб - Обратный звонок</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .callback-container { max-width: 500px; margin: 50px auto; padding: 20px; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .success { background: #e7f5ea; color: #2e7d32; }
        .error { background: #fdecea; color: #d32f2f; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; padding: 12px 20px; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="callback-container">
    <h2> Заказать обратный звонок</h2>
    <p>Оставьте ваши контакты, и мы свяжемся с вами в ближайшее время.</p>
    
    <?php echo $message; ?>

    <form method="post">
        <label>Как к вам обращаться? *</label>
        <input type="text" name="c_name" placeholder="Иван Иванов" required>

        <label>Контактный телефон *</label>
        <input type="text" name="c_phone" placeholder="+7 (999) 000-00-00" required>

        <label>Примечание / Удобное время:</label>
        <textarea name="c_comment" rows="3" placeholder="Например: позвоните после 18:00"></textarea>

        <button type="submit" name="send_callback">Отправить запрос</button>
    </form>
    
    <div style="margin-top: 20px; text-align: center;">
        <a href="profile.php">Вернуться в Личный кабинет</a>
    </div>
</div>

</body>
</html>