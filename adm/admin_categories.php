<?php
require_once('../includes/functions.php');

if (!isset($_SESSION['my_inside']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: auth_adm.php");
    exit;
}

// Добавление новой категории
if (isset($_POST['add_category'])) {
    $name = htmlspecialchars($_POST['name']);
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
    dbquery("INSERT INTO categories (name, description) VALUES ('$name', '$description')");
    header("Location: admin_categories.php");
    exit;
}

// Обновление категории
if (isset($_POST['update_category'])) {
    $id = intval($_POST['cat_id']);
    $name = htmlspecialchars($_POST['name']);
    dbquery("UPDATE categories SET name = '$name' WHERE id = $id");
}

// Удаление категории
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    dbquery("DELETE FROM categories WHERE id = $id");
    header("Location: admin_categories.php");
    exit;
}

$categories = dbquery("SELECT * FROM categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление категориями — Админ-панель</title>
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
        .form-add input, .form-add textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 10px; }
        .form-add button { padding: 10px 20px; background: #1E40AF; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
        .admin-table { width: 100%; background: #fff; border: 1px solid #ddd; border-radius: 8px; border-collapse: collapse; overflow: hidden; }
        .admin-table th { background: #1E40AF; color: #fff; padding: 12px 15px; text-align: left; font-weight: 600; }
        .admin-table td { padding: 12px 15px; border-bottom: 1px solid #eee; }
        .btn-edit { padding: 6px 12px; background: #1E40AF; color: #fff; text-decoration: none; border-radius: 4px; font-size: 13px; }
        .btn-delete { padding: 6px 12px; background: #e00; color: #fff; text-decoration: none; border-radius: 4px; font-size: 13px; margin-left: 5px; }
        .btn-save { padding: 6px 12px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .cat-input { width: 200px; padding: 5px; border: 1px solid #ddd; border-radius: 4px; }
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
            <a href="admin_categories.php" class="active">Категории</a>
            <a href="admin_orders.php">Заказы</a>
        </aside>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Управление категориями</h1>
                <a href="../includes/logout.php">Выйти</a>
            </div>
            
            <div class="form-add">
                <h3>Добавить новую категорию</h3>
                <form method="POST">
                    <input type="text" name="name" placeholder="Название категории" required>
                    <textarea name="description" placeholder="Описание категории" rows="2"></textarea>
                    <button type="submit" name="add_category">Добавить категорию</button>
                </form>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($cat = dbfetcha($categories)): ?>
                    <tr>
                        <td><?php echo $cat['id']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="cat_id" value="<?php echo $cat['id']; ?>">
                                <input type="text" name="name" value="<?php echo htmlspecialchars($cat['name']); ?>" class="cat-input">
                                <button type="submit" name="update_category" class="btn-save">💾</button>
                            </form>
                        </td>
                        <td><?php echo array_key_exists('description', $cat) ? htmlspecialchars($cat['description']) : ''; ?></td>
                        <td>
                            <a href="?delete=<?php echo $cat['id']; ?>" class="btn-delete" onclick="return confirm('Удалить категорию?')">Удалить</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>