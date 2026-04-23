<?php 
// Подключаем функции (сессия, куки, работа с БД)
require_once('includes/functions.php'); 

// Если пользователь не авторизован - редирект на страницу входа
if (check_user() == '') {
    header('Location: login_page.php');
    exit;
}

// Обработка сброса пароля
if (isset($_POST['reset_request'])) {
    $user = $_SESSION['current_user'];
    $token = bin2hex(random_bytes(16));
    $temp_pass = str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789');
    
    $hashed_pass = password_hash($temp_pass, PASSWORD_DEFAULT);
    
    $sql = "UPDATE users2 SET password = '$hashed_pass', reset_token = '$token' WHERE username = '$user'";
    dbquery($sql);
    
    $_SESSION['temp_pass'] = $temp_pass;
}

// Добавляем страницу в историю посещений
add_to_history();

// Обработка формы обратного звонка
$callback_message = "";
if (isset($_POST['send_callback'])) {
    $c_name = mysqli_real_escape_string($db, $_POST['c_name']);
    $c_phone = mysqli_real_escape_string($db, $_POST['c_phone']);
    $c_comment = mysqli_real_escape_string($db, $_POST['c_comment']);
    
    if (!empty($c_name) && !empty($c_phone)) {
        $user = $_SESSION['current_user'];
        $sql = "SELECT id FROM users2 WHERE username = '$user'";
        $result = dbquery($sql);
        $user_data = dbfetcha($result);
        $uid = $user_data['id'];
        
        $sql = "INSERT INTO callback_requests (user_id, customer_name, phone_number, status, comment) 
               VALUES ('$uid', '$c_name', '$c_phone', 'Новая', '$c_comment')";
        if (mysqli_query($db, $sql)) {
            $callback_message = "<p style='color:green;'>Заявка отправлена! Мы свяжемся с вами.</p>";
        } else {
            $callback_message = "<p style='color:red;'>Ошибка: " . mysqli_error($db) . "</p>";
        }
    } else {
        $callback_message = "<p style='color:red;'>Заполните имя и телефон!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет — ТехноЛаб</title>
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
        <section class="user-info" style="background: #f4f4f4; padding: 30px; border-radius: 12px;">
            <h2>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['current_user']); ?>!</h2>
            <br>
            
            <?php
            $user = $_SESSION['current_user'];
            $sql = "SELECT username, phone, address FROM users2 WHERE username = '$user'";
            $result = dbquery($sql);
            if ($result && dbrows($result) > 0) {
                $data = dbfetcha($result);
                ?>
                <p>Логин: <?php echo htmlspecialchars($data['username']); ?></p>
                <p>Телефон: <?php echo htmlspecialchars($data['phone']); ?></p>
                <p>Адрес: <?php echo htmlspecialchars($data['address']); ?></p>
                <?php
            }
            
            // Форма сброса пароля
            ?>
            <form method="post" style="margin-top: 20px;">
                <input type="submit" name="reset_request" value="Сбросить пароль" style="padding: 8px 15px; cursor: pointer;">
            </form>
            
            <div style="margin-top: 30px; border-top: 1px solid #ccc; padding-top: 20px;">
                <h3>Заказать обратный звонок</h3>
                <?php echo $callback_message; ?>
                <form method="post">
                    <input type="text" name="c_name" placeholder="Ваше имя" required style="width:100%; padding:8px; margin-bottom:10px;">
                    <input type="text" name="c_phone" placeholder="Телефон" required style="width:100%; padding:8px; margin-bottom:10px;">
                    <textarea name="c_comment" placeholder="Примечание (удобное время и т.д.)" rows="3" style="width:100%; padding:8px; margin-bottom:10px;"></textarea>
                    <button type="submit" name="send_callback" style="background:#007bff; color:white; border:none; padding:10px 20px; border-radius:4px; cursor:pointer;">Отправить заявку</button>
                </form>
            </div>
            
            <?php
            // Вывод временного пароля
            if (isset($_SESSION['temp_pass'])) {
                echo "<p style='color: red; font-weight: bold;'>Временный пароль: " . htmlspecialchars($_SESSION['temp_pass']) . "</p>";
                echo "<p><a href='change_password.php'>Сменить пароль</a></p>";
                unset($_SESSION['temp_pass']);
            }
            ?>
            <br>

            <div class="task-block" style="margin-top: 30px; border-top: 1px solid #ccc; padding-top: 20px;">
                <h3>История ваших перемещений</h3>
                <ul style="list-style: none; padding: 0;">
                    <?php
                    // Выводим историю посещений из сессии
                    if (isset($_SESSION['history']) && !empty($_SESSION['history'])) {
                        foreach ($_SESSION['history'] as $index => $entry) {
                            $page = is_array($entry) ? $entry['page'] : $entry;
                            $time = is_array($entry) ? $entry['time'] : '';
                            echo "<li style='margin-bottom: 5px;'>";
                            echo "<span style='color: #888;'>" . ($index + 1) . ".</span> ";
                            echo "<strong>" . htmlspecialchars($page) . "</strong>";
                            if ($time) echo " <span style='color:#888; font-size:12px;'>($time)</span>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li>История посещений пуста.</li>";
                    }
                    ?>
                </ul>

                <a href="includes/clear_history.php" style="font-size: 11px; color: red; text-decoration: none;">Очистить историю</a>
            </div>

            <div class="orders-block" style="margin-top: 30px; border-top: 1px solid #ccc; padding-top: 20px;">
                <h3>История заказов</h3>
                <?php
                $user = $_SESSION['current_user'];
                $user_result = dbquery("SELECT id FROM users2 WHERE username = '$user' LIMIT 1");
                $user_data = dbfetcha($user_result);
                $user_id = intval($user_data['id']);

                $orders_result = dbquery("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
                if (dbrows($orders_result) > 0) {
                    while ($order = dbfetcha($orders_result)) {
                        echo "<div style='border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 8px;'>";
                        echo "<h4>Заказ #" . $order['id'] . " от " . date('d.m.Y H:i', strtotime($order['created_at'])) . "</h4>";
                        echo "<p><strong>Статус:</strong> " . htmlspecialchars($order['status']) . "</p>";
                        echo "<p><strong>Общая сумма:</strong> " . number_format($order['total_price'], 0, '', ' ') . " ₽</p>";

                        // Получаем товары заказа
                        $order_items_result = dbquery("SELECT oi.quantity, oi.price, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = " . $order['id']);
                        if (dbrows($order_items_result) > 0) {
                            echo "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>";
                            echo "<thead><tr style='background: #f0f0f0;'><th style='padding: 8px; border: 1px solid #ddd;'>Фото</th><th style='padding: 8px; border: 1px solid #ddd;'>Товар</th><th style='padding: 8px; border: 1px solid #ddd;'>Кол-во</th><th style='padding: 8px; border: 1px solid #ddd;'>Цена</th><th style='padding: 8px; border: 1px solid #ddd;'>Сумма</th></tr></thead>";
                            echo "<tbody>";
                            while ($item = dbfetcha($order_items_result)) {
                                $img = !empty($item['image']) ? 'images/products/' . $item['image'] : 'images/no-product.png';
                                echo "<tr>";
                                echo "<td style='padding: 8px; border: 1px solid #ddd;'><img src='" . $img . "' alt='' style='width: 50px; height: 50px; object-fit: cover;'></td>";
                                echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($item['name']) . "</td>";
                                echo "<td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>" . $item['quantity'] . "</td>";
                                echo "<td style='padding: 8px; border: 1px solid #ddd; text-align: right;'>" . number_format($item['price'], 0, '', ' ') . " ₽</td>";
                                echo "<td style='padding: 8px; border: 1px solid #ddd; text-align: right;'>" . number_format($item['quantity'] * $item['price'], 0, '', ' ') . " ₽</td>";
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p>Товары не найдены.</p>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "<p>У вас нет заказов.</p>";
                }
                ?>
            </div>
        </section>
    </main>
</body>
</html>