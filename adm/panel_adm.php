<?php
require_once('../includes/functions.php');

// Проверка авторизации админа
if (!isset($_SESSION['my_inside']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: auth_adm.php");
    exit;
}

$admin_name = $_SESSION['current_user'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель — ТехноЛаб</title>
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
        .admin-header a:hover { text-decoration: underline; }
        .admin-table { width: 100%; background: #fff; border: 1px solid #ddd; border-radius: 8px; border-collapse: collapse; overflow: hidden; }
        .admin-table th { background: #1E40AF; color: #fff; padding: 12px 15px; text-align: left; font-weight: 600; }
        .admin-table td { padding: 12px 15px; border-bottom: 1px solid #eee; }
        .admin-table tr:hover { background: #f9f9f9; }
        .btn-edit { padding: 6px 12px; background: #1E40AF; color: #fff; text-decoration: none; border-radius: 4px; font-size: 13px; }
        .btn-delete { padding: 6px 12px; background: #e00; color: #fff; text-decoration: none; border-radius: 4px; font-size: 13px; margin-left: 5px; }
        .btn-save { padding: 6px 12px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; }
        .filter-links { margin-bottom: 20px; display: flex; gap: 15px; }
        .filter-links a { padding: 8px 16px; background: #fff; border: 1px solid #ddd; border-radius: 4px; color: #333; text-decoration: none; }
        .filter-links a.active { background: #1E40AF; color: #fff; border-color: #1E40AF; }
        .form-add { background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 30px; }
        .form-add h3 { margin-bottom: 15px; color: #333; }
        .form-add input, .form-add select, .form-add textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 10px; }
        .form-add button { padding: 10px 20px; background: #1E40AF; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .order-actions { display: flex; gap: 10px; }
        .btn-confirm { padding: 6px 12px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .btn-cancel { padding: 6px 12px; background: #e00; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container header__container">
            <div class="logo-group">
                <?php include('../top/logo.php'); ?>
            </div>
            <?php include('../top/menu.php'); ?>
        </div>
    </header>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <a href="panel_adm.php" class="active">Главная</a>
            <a href="admin_products.php">Товары</a>
            <a href="admin_categories.php">Категории</a>
            <a href="admin_orders.php">Заказы</a>
        </aside>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Панель администратора</h1>
                <a href="../includes/logout.php">Выйти (<?php echo htmlspecialchars($admin_name); ?>)</a>
            </div>
            
            <p style="font-size: 16px; color: #666;">Добро пожаловать в админ-панель. Выберите раздел в меню слева.</p>
        </div>
    </div>
</body>
</html>