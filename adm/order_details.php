<?php
require_once('../includes/functions.php');

if (!isset($_SESSION['my_inside']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: auth_adm.php");
    exit;
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id == 0) {
    header("Location: admin_orders.php");
    exit;
}

$order_result = dbquery("SELECT o.*, u.username FROM orders o LEFT JOIN users2 u ON o.user_id = u.id WHERE o.id = $order_id");
$order = dbfetcha($order_result);

if (!$order) {
    header("Location: admin_orders.php");
    exit;
}

$order_items_result = dbquery("SELECT oi.quantity, oi.price, p.name, p.description, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $order_id");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Детали заказа #<?php echo $order_id; ?> — Админ-панель</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/catalog.css">
    <style>
        .admin-layout { display: flex; min-height: calc(100vh - 110px); }
        .admin-sidebar { width: 250px; background: #1E40AF; padding: 20px; }
        .admin-sidebar a { display: block; color: #fff; padding: 12px 15px; text-decoration: none; border-radius: 6px; margin-bottom: 8px; transition: 0.2s; }
        .admin-sidebar a:hover, .admin-sidebar a.active { background: #163a8a; }
        .admin-content { flex: 1; padding: 30px; background: #f5f5f5; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .admin-header h1 { font-size: 28px; color: #333; }
        .admin-header a { color: #e00; text-decoration: none; }
        .order-info { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .order-info h3 { margin-top: 0; }
        .order-items { background: #fff; padding: 20px; border-radius: 8px; }
        .order-items table { width: 100%; border-collapse: collapse; }
        .order-items th { background: #1E40AF; color: #fff; padding: 12px; text-align: left; }
        .order-items td { padding: 12px; border-bottom: 1px solid #eee; }
        .product-image { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; }
        .back-link { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #1E40AF; color: #fff; text-decoration: none; border-radius: 4px; }
        .back-link:hover { background: #163a8a; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container header__container">
            <div class="logo-group"><?php include('../top/logo.php'); ?></div>
            <?php include('../top/menu.php'); ?>
        </div>
    </header>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <a href="panel_adm.php">Главная</a>
            <a href="admin_products.php">Товары</a>
            <a href="admin_categories.php">Категории</a>
            <a href="admin_orders.php" class="active">Заказы</a>
        </aside>

        <div class="admin-content">
            <a href="admin_orders.php" class="back-link">← Назад к заказам</a>

            <div class="admin-header">
                <h1>Детали заказа #<?php echo $order_id; ?></h1>
            </div>

            <div class="order-info">
                <h3>Информация о заказе</h3>
                <p><strong>Дата:</strong> <?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></p>
                <p><strong>Заказчик:</strong> <?php echo $order['name'] ? htmlspecialchars($order['name']) : ($order['username'] ? htmlspecialchars($order['username']) : 'Гость'); ?></p>
                <p><strong>Телефон:</strong> <?php echo $order['phone'] ? htmlspecialchars($order['phone']) : '—'; ?></p>
                <p><strong>Email:</strong> <?php echo $order['email'] ? htmlspecialchars($order['email']) : '—'; ?></p>
                <p><strong>Статус:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                <p><strong>Общая сумма:</strong> <?php echo number_format($order['total_price'], 0, '', ' '); ?> ₽</p>
                <?php if ($order['comment']): ?>
                <p><strong>Комментарий:</strong> <?php echo htmlspecialchars($order['comment']); ?></p>
                <?php endif; ?>
            </div>

            <div class="order-items">
                <h3>Товары в заказе</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Фото</th>
                            <th>Название</th>
                            <th>Описание</th>
                            <th>Количество</th>
                            <th>Цена за шт.</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = dbfetcha($order_items_result)): ?>
                        <tr>
                            <td>
                                <img src="<?php echo !empty($item['image']) ? '../images/products/' . $item['image'] : '../images/no-product.png'; ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-image">
                            </td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['description']); ?></td>
                            <td><?php echo $item['quantity']; ?> шт.</td>
                            <td><?php echo number_format($item['price'], 0, '', ' '); ?> ₽</td>
                            <td><?php echo number_format($item['quantity'] * $item['price'], 0, '', ' '); ?> ₽</td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>