<?php
require_once('../includes/functions.php');

if (!isset($_SESSION['my_inside']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: auth_adm.php");
    exit;
}

// Удаление товара
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    dbquery("DELETE FROM products WHERE id = $id");
    header("Location: admin_products.php");
    exit;
}

// Обновление количества на складе
if (isset($_POST['update_stock'])) {
    $id = intval($_POST['p_id']);
    $new_stock = intval($_POST['stock']);
    dbquery("UPDATE products SET stock = $new_stock WHERE id = $id");
}

// Добавление нового товара
if (isset($_POST['add_product'])) {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = intval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    
    dbquery("INSERT INTO products (name, description, price, stock, category_id) VALUES ('$name', '$description', $price, $stock, $category_id)");
    header("Location: admin_products.php");
    exit;
}

// Получение списка товаров
$products = dbquery("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");

// Получение категорий для выпадающего списка
$categories = dbquery("SELECT * FROM categories");
$categories_list = [];
while ($c = dbfetcha($categories)) $categories_list[] = $c;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление товарами — Админ-панель</title>
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
        .form-add { background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 30px; }
        .form-add h3 { margin-bottom: 15px; color: #333; }
        .form-add input, .form-add select, .form-add textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 10px; }
        .form-add button { padding: 10px 20px; background: #1E40AF; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
        .admin-table { width: 100%; background: #fff; border: 1px solid #ddd; border-radius: 8px; border-collapse: collapse; overflow: hidden; }
        .admin-table th { background: #1E40AF; color: #fff; padding: 12px 15px; text-align: left; font-weight: 600; }
        .admin-table td { padding: 12px 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .admin-table tr:hover { background: #f9f9f9; }
        .btn-edit { padding: 6px 12px; background: #1E40AF; color: #fff; text-decoration: none; border-radius: 4px; font-size: 13px; }
        .btn-delete { padding: 6px 12px; background: #e00; color: #fff; text-decoration: none; border-radius: 4px; font-size: 13px; margin-left: 5px; }
        .btn-save { padding: 6px 12px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .stock-input { width: 60px; padding: 5px; border: 1px solid #ddd; border-radius: 4px; }
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
            <a href="admin_products.php" class="active">Товары</a>
            <a href="admin_categories.php">Категории</a>
            <a href="admin_orders.php">Заказы</a>
        </aside>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Управление товарами</h1>
                <a href="../includes/logout.php">Выйти</a>
            </div>
            
            <div class="form-add">
                <h3>Добавить новый товар</h3>
                <form method="POST">
                    <input type="text" name="name" placeholder="Название товара" required>
                    <textarea name="description" placeholder="Описание товара" rows="2"></textarea>
                    <input type="number" name="price" placeholder="Цена (₽)" required>
                    <input type="number" name="stock" placeholder="Количество на складе" required>
                    <select name="category_id">
                        <option value="">Выберите категорию</option>
                        <?php foreach ($categories_list as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="add_product">Добавить товар</button>
                </form>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Склад</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($p = dbfetcha($products)): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td><?php echo $p['cat_name'] ? htmlspecialchars($p['cat_name']) : 'Без категории'; ?></td>
                        <td><?php echo number_format($p['price'], 0, '', ' '); ?> ₽</td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="p_id" value="<?php echo $p['id']; ?>">
                                <input type="number" name="stock" value="<?php echo $p['stock']; ?>" class="stock-input">
                                <button type="submit" name="update_stock" class="btn-save">💾</button>
                            </form>
                        </td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn-edit">Редактировать</a>
                            <a href="?delete=<?php echo $p['id']; ?>" class="btn-delete" onclick="return confirm('Удалить товар?')">Удалить</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>