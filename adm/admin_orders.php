<?php
require_once('../includes/functions.php');

if (!isset($_SESSION['my_inside']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: auth_adm.php");
    exit;
}

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Подтверждение заказа
if (isset($_GET['confirm'])) {
    $id = intval($_GET['confirm']);
    dbquery("UPDATE orders SET status = 'Подтвержден' WHERE id = $id");
    header("Location: admin_orders.php" . ($status_filter ? "?status=$status_filter" : ""));
    exit;
}

// Отмена заказа
if (isset($_POST['cancel_order'])) {
    $id = intval($_POST['order_id']);
    $reason = htmlspecialchars($_POST['reason']);
    dbquery("UPDATE orders SET status = 'Отменен', comment = '$reason' WHERE id = $id");
    header("Location: admin_orders.php" . ($status_filter ? "?status=$status_filter" : ""));
    exit;
}

if ($status_filter) {
    $orders = dbquery("SELECT o.*, u.username FROM orders o LEFT JOIN users2 u ON o.user_id = u.id WHERE o.status = '$status_filter' ORDER BY o.created_at DESC");
} else {
    $orders = dbquery("SELECT o.*, u.username FROM orders o LEFT JOIN users2 u ON o.user_id = u.id ORDER BY o.created_at DESC");
}

// Подсчет количества товаров в заказе
function getOrderItemsCount($order_id) {
    $res = dbquery("SELECT SUM(quantity) as total FROM order_items WHERE order_id = $order_id");
    $row = dbfetcha($res);
    return $row['total'] ?: 0;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление заказами — Админ-панель</title>
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
        .filter-links { margin-bottom: 20px; display: flex; gap: 10px; }
        .filter-links a { padding: 8px 16px; background: #fff; border: 1px solid #ddd; border-radius: 4px; color: #333; text-decoration: none; }
        .filter-links a.active { background: #1E40AF; color: #fff; border-color: #1E40AF; }
        .admin-table { width: 100%; background: #fff; border: 1px solid #ddd; border-radius: 8px; border-collapse: collapse; overflow: hidden; }
        .admin-table th { background: #1E40AF; color: #fff; padding: 12px 15px; text-align: left; font-weight: 600; }
        .admin-table td { padding: 12px 15px; border-bottom: 1px solid #eee; }
        .status-new { color: #1E40AF; font-weight: 600; }
        .status-confirm { color: #28a745; font-weight: 600; }
        .status-cancel { color: #e00; font-weight: 600; }
        .order-actions { display: flex; gap: 8px; }
        .btn-confirm { padding: 6px 12px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn-cancel { padding: 6px 12px; background: #e00; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .cancel-form { display: inline; }
        .cancel-form input { width: 150px; padding: 5px; border: 1px solid #ddd; border-radius: 4px; }
        .order-details { font-size: 13px; color: #666; }
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
            <div class="admin-header">
                <h1>Управление заказами</h1>
                <a href="../includes/logout.php">Выйти</a>
            </div>
            
            <div class="filter-links">
                <a href="admin_orders.php" class="<?php echo !$status_filter ? 'active' : ''; ?>">Все заказы</a>
                <a href="?status=Новый" class="<?php echo $status_filter == 'Новый' ? 'active' : ''; ?>">Новые</a>
                <a href="?status=Подтвержден" class="<?php echo $status_filter == 'Подтвержден' ? 'active' : ''; ?>">Подтвержденные</a>
                <a href="?status=Отменен" class="<?php echo $status_filter == 'Отменен' ? 'active' : ''; ?>">Отмененные</a>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Дата</th>
                        <th>Заказчик</th>
                        <th>Товаров</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Детали</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = dbfetcha($orders)): 
                        $items_count = getOrderItemsCount($order['id']);
                        $status_class = $order['status'] == 'Новый' ? 'status-new' : ($order['status'] == 'Подтвержден' ? 'status-confirm' : 'status-cancel');
                    ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></td>
                        <td>
                            <?php echo $order['name'] ? htmlspecialchars($order['name']) : ($order['username'] ? htmlspecialchars($order['username']) : 'Гость'); ?><br>
                            <span class="order-details">Тел: <?php echo $order['phone'] ? htmlspecialchars($order['phone']) : '—'; ?></span>
                        </td>
                        <td><?php echo $items_count; ?> шт.</td>
                        <td><?php echo number_format($order['total_price'], 0, '', ' '); ?> ₽</td>
                        <td class="<?php echo $status_class; ?>"><?php echo $order['status']; ?></td>
                        <td>
                            <a href="order_details.php?id=<?php echo $order['id']; ?>" style="color: #1E40AF; text-decoration: none; font-weight: 600;">Просмотр</a>
                        </td>
                        <td>
                            <div class="order-actions">
                                <?php if ($order['status'] == 'Новый'): ?>
                                <a href="?confirm=<?php echo $order['id']; ?>" class="btn-confirm">Подтвердить</a>
                                <form method="POST" class="cancel-form">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <input type="text" name="reason" placeholder="Причина отказа" required>
                                    <button type="submit" name="cancel_order" class="btn-cancel">Отменить</button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>