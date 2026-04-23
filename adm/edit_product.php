<?php
require_once('../includes/functions.php');

if (!isset($_SESSION['my_inside']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: auth_adm.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    header("Location: admin_products.php");
    exit;
}

// Обновление товара
if (isset($_POST['update_product'])) {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = intval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    
    dbquery("UPDATE products SET name = '$name', description = '$description', price = $price, stock = $stock, category_id = $category_id WHERE id = $id");
    header("Location: admin_products.php");
    exit;
}

$product = dbfetcha(dbquery("SELECT * FROM products WHERE id = $id"));

$categories = dbquery("SELECT * FROM categories");
$categories_list = [];
while ($c = dbfetcha($categories)) $categories_list[] = $c;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование товара — Админ-панель</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container { max-width: 600px; margin: 40px auto; padding: 30px; background: #fff; border: 1px solid #ddd; border-radius: 8px; }
        .admin-container h1 { font-size: 24px; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; color: #555; margin-bottom: 5px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; }
        .btn-save { padding: 12px 24px; background: #1E40AF; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .btn-back { display: inline-block; color: #1E40AF; text-decoration: none; margin-bottom: 20px; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container header__container">
            <div class="logo-group"><?php include('../top/logo.php'); ?></div>
        </div>
    </header>

    <div class="admin-container">
        <a href="admin_products.php" class="btn-back">← Назад к товарам</a>
        <h1>Редактирование товара</h1>
        
        <form method="POST">
            <div class="form-group">
                <label>Название товара</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Цена (₽)</label>
                <input type="number" name="price" value="<?php echo $product['price']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Количество на складе</label>
                <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Категория</label>
                <select name="category_id">
                    <option value="">Без категории</option>
                    <?php foreach ($categories_list as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" name="update_product" class="btn-save">Сохранить изменения</button>
        </form>
    </div>
</body>
</html>