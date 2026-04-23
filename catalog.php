<?php 
require_once('includes/functions.php');

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$category_id = isset($_GET['cat']) ? intval($_GET['cat']) : 0;

// Логика избранного
if (isset($_POST['toggle_favorite']) && isset($_POST['product_id'])) {
    $product_id_to_fav = intval($_POST['product_id']);
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
    
    if ($user_id == 0 && isset($_SESSION['current_user'])) {
        $user = $_SESSION['current_user'];
        $uid_result = dbquery("SELECT id FROM users2 WHERE username = '$user' LIMIT 1");
        $uid_data = dbfetcha($uid_result);
        $user_id = intval($uid_data['id']);
    }
    
    if ($user_id > 0) {
        $check = dbquery("SELECT id FROM favorites WHERE user_id = $user_id AND product_id = $product_id_to_fav");
        if (dbfetcha($check)) {
            dbquery("DELETE FROM favorites WHERE user_id = $user_id AND product_id = $product_id_to_fav");
        } else {
            dbquery("INSERT INTO favorites (user_id, product_id, created_at) VALUES ($user_id, $product_id_to_fav, NOW())");
        }
    }
    
    $redirect_url = "catalog.php";
    if ($category_id > 0) $redirect_url .= "?cat=" . $category_id;
    header("Location: $redirect_url");
    exit;
}

// Получение товаров
if ($category_id > 0) {
    $products_result = dbquery("SELECT * FROM products WHERE category_id = $category_id");
    $current_cat_res = dbquery("SELECT name FROM categories WHERE id = $category_id");
    $current_cat = dbfetcha($current_cat_res);
} else {
    $products_result = dbquery("SELECT * FROM products");
    $current_cat = null;
}

$categories_list = [];
$cats = dbquery("SELECT * FROM categories");
while ($c = dbfetcha($cats)) $categories_list[] = $c;

$user_favorites = [];
if (isset($_SESSION['my_inside'])) {
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
    if ($user_id == 0) {
        $user = $_SESSION['current_user'];
        $uid_result = dbquery("SELECT id FROM users2 WHERE username = '$user' LIMIT 1");
        $uid_data = dbfetcha($uid_result);
        $user_id = intval($uid_data['id']);
    }
    if ($user_id > 0) {
        $favs = dbquery("SELECT product_id FROM favorites WHERE user_id = $user_id");
        while ($f = dbfetcha($favs)) $user_favorites[] = $f['product_id'];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог — ТехноЛаб</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/catalog.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<header class="header">
    <div class="container header__container">
        <div class="logo-group">
            <?php include('top/logo.php'); ?>
        </div>
        <?php include('top/menu.php'); ?>
    </div>
</header>

<main class="catalog-page">
    <div class="container">
        <h1 class="section-title">
            Каталог<?php if ($current_cat): ?> — <?php echo htmlspecialchars($current_cat['name']); endif; ?>
        </h1>

        <div class="catalog-layout">
            <aside class="catalog-sidebar">
                <h3 class="sidebar-title">Категории</h3>
                <nav class="category-nav">
                    <a href="catalog.php" class="category-item <?php echo !$category_id ? 'active' : ''; ?>">Все товары</a>
                    <?php foreach($categories_list as $cat): ?>
                    <a href="catalog.php?cat=<?php echo $cat['id']; ?>" 
                       class="category-item <?php echo $category_id == $cat['id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </nav>
            </aside>

            <div class="catalog-content">
                <?php if ($product_id > 0): 
                    /* --- СТРАНИЦА ТОВАРА (ПОДРОБНО) --- */
                    $item = dbfetcha(dbquery("SELECT * FROM products WHERE id = $product_id"));
                    $img = !empty($item['image']) ? 'images/products/' . $item['image'] : 'images/no-product.png';
                ?>
                <div class="product-view">
                    <a href="catalog.php<?php echo $category_id ? '?cat=' . $category_id : ''; ?>" class="back-link">← Вернуться в каталог</a>
                    <div class="product-view__grid">
                        <div class="product-view__image">
                            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-view__img">
                        </div>
                        <div class="product-view__info">
                            <h2 class="product-view__title"><?php echo htmlspecialchars($item['name']); ?></h2>
                            <p class="product-view__desc"><?php echo htmlspecialchars($item['description']); ?></p>
                            <p class="product-view__price"><?php echo number_format($item['price'], 0, '', ' '); ?> ₽</p>
                            <p class="product-view__stock <?php echo $item['stock'] < 3 ? 'low' : ''; ?>">
                                Осталось: <strong><?php echo $item['stock']; ?> шт.</strong>
                            </p>
                            <form method="post" action="cart.php">
                                <input type="hidden" name="add_to_cart" value="1">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn-primary">Добавить в корзину</button>
                            </form>
                        </div>
                    </div>
                </div>

                <?php else: ?>
                
                
                <div class="products-grid">
                    <?php while($p = dbfetcha($products_result)): 
                        $fav = isset($_SESSION['my_inside']) && in_array($p['id'], $user_favorites);
                        $img = !empty($p['image']) ? 'images/products/' . $p['image'] : 'images/no-product.png';
                    ?>
                    <div class="product-card">
                        <div class="product-card-top">
                            <?php if (isset($_SESSION['my_inside'])): ?>
                            <form method="post" class="product-fav">
                                <input type="hidden" name="toggle_favorite" value="1">
                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                <button type="submit" class="<?php echo $fav ? 'active' : ''; ?>"><?php echo $fav ? '❤️' : '🤍'; ?></button>
                            </form>
                            <?php endif; ?>
                        </div>
                        <a href="catalog.php?id=<?php echo $p['id']; ?>" class="product-card__img-link">
                            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="product-card__img">
                        </a>
                        <div class="product-card__body">
                            <a href="catalog.php?id=<?php echo $p['id']; ?>" class="product-card__title">
                                <?php echo htmlspecialchars($p['name']); ?>
                            </a>
                            
                            <p class="product-card__price"><?php echo number_format($p['price'], 0, '', ' '); ?> ₽</p>
                            
                            <p class="product-card__stock <?php echo $p['stock'] < 3 ? 'low' : ''; ?>">
                                <?php echo ($p['stock'] > 0) ? "В наличии: " . $p['stock'] . " шт." : "Нет в наличии"; ?>
                            </p>
                            
                            <div class="product-card__buttons">
                                <a href="catalog.php?id=<?php echo $p['id']; ?>" class="btn-details">Подробнее</a>
                                <form method="post" action="cart.php">
                                    <input type="hidden" name="add_to_cart" value="1">
                                    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                    <button type="submit" class="btn-add">В корзину</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

</body>
</html>