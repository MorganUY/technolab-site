<?php 
require_once('includes/functions.php');

if (isset($_POST['add_to_favorites']) && isset($_POST['product_id'])) {
    if (!isset($_SESSION['my_inside'])) {
        echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $product_id = intval($_POST['product_id']);
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
    if ($user_id == 0) {
        $user = $_SESSION['current_user'];
        $uid_result = dbquery("SELECT id FROM users2 WHERE username = '$user' LIMIT 1");
        $uid_data = dbfetcha($uid_result);
        $user_id = intval($uid_data['id']);
    }
    
    $check_sql = "SELECT id FROM favorites WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = dbquery($check_sql);
    $existing = dbfetcha($check_result);
    
    if (!$existing) {
        $add_sql = "INSERT INTO favorites (user_id, product_id, created_at) VALUES ($user_id, $product_id, NOW())";
        dbquery($add_sql);
    }
    
    echo json_encode(['success' => true]);
    exit;
}

if (isset($_POST['remove_from_favorites']) && isset($_POST['product_id'])) {
    if (!isset($_SESSION['my_inside'])) {
        echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $product_id = intval($_POST['product_id']);
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
    if ($user_id == 0) {
        $user = $_SESSION['current_user'];
        $uid_result = dbquery("SELECT id FROM users2 WHERE username = '$user' LIMIT 1");
        $uid_data = dbfetcha($uid_result);
        $user_id = intval($uid_data['id']);
    }
    
    $remove_sql = "DELETE FROM favorites WHERE user_id = $user_id AND product_id = $product_id";
    dbquery($remove_sql);
    
    echo json_encode(['success' => true]);
    exit;
}

$favorites_items = [];

if (isset($_SESSION['my_inside'])) {
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
    if ($user_id == 0) {
        $user = $_SESSION['current_user'];
        $uid_result = dbquery("SELECT id FROM users2 WHERE username = '$user' LIMIT 1");
        $uid_data = dbfetcha($uid_result);
        $user_id = intval($uid_data['id']);
    }
    
    $result = dbquery("
        SELECT p.*, f.created_at as favorited_at 
        FROM favorites f 
        JOIN products p ON f.product_id = p.id 
        WHERE f.user_id = $user_id 
        ORDER BY f.created_at DESC
    ");
    
    while ($product = dbfetcha($result)) {
        $favorites_items[] = $product;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Избранное — ТехноЛаб</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/catalog.css">
    <link rel="stylesheet" href="css/favorites.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

<main class="favorites-page">
    <div class="container">
        <h1 class="catalog-title">Избранное</h1>
        
        <?php if (!isset($_SESSION['my_inside'])): ?>
            <div class="favorites-auth">
                <p>Для просмотра избранного необходимо <a href="login_page.php">войти</a> или <a href="register_page.php">зарегистрироваться</a></p>
            </div>
        <?php elseif (empty($favorites_items)): ?>
            <div class="favorites-empty">
                <p>Избранное пусто</p>
                <a href="catalog.php">Перейти в каталог</a>
            </div>
        <?php else: ?>
            <div class="catalog-grid">
                <?php foreach ($favorites_items as $item): 
                    $image_path = !empty($item['image']) ? 'images/products/' . $item['image'] : 'images/no-product.png';
                ?>
                <div class="product-card">
                    <a href="product.php?id=<?php echo $item['id']; ?>" class="product-link">
                        <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-image">
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="product-price"><?php echo number_format($item['price'], 0, '', ' '); ?> ₽</p>
                            <?php if ($item['stock'] > 0): ?>
                                <span class="product-stock in-stock">В наличии: <?php echo $item['stock']; ?></span>
                            <?php else: ?>
                                <span class="product-stock out-stock">Нет в наличии</span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="product-actions">
                        <form method="post" class="add-cart-form">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <?php if ($item['stock'] > 0): ?>
                                <button type="submit" name="add_to_cart" class="btn-add-cart">В корзину</button>
                            <?php else: ?>
                                <button type="button" class="btn-add-cart disabled" disabled>Нет в наличии</button>
                            <?php endif; ?>
                        </form>
                        <button type="button" class="btn-remove-favorites" data-id="<?php echo $item['id']; ?>">Удалить</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
$(document).ready(function() {
    $('.btn-add-cart').on('click', function(e) {
        e.preventDefault();
        var $form = $(this).closest('.add-cart-form');
        var productId = $form.find('input[name="product_id"]').val();
        var $btn = $(this);
        
        $.ajax({
            url: 'cart.php',
            type: 'POST',
            data: {
                add_to_cart: true,
                product_id: productId
            },
            success: function() {
                var currentCount = parseInt($('.cart-count').text()) || 0;
                $('.cart-count').text(currentCount + 1);
                alert('Товар добавлен в корзину');
            }
        });
    });
    
    $('.btn-remove-favorites').on('click', function() {
        var productId = $(this).data('id');
        var $card = $(this).closest('.product-card');
        
        if (confirm('Удалить товар из избранного?')) {
            $.ajax({
                url: 'favorites.php',
                type: 'POST',
                data: {
                    remove_from_favorites: true,
                    product_id: productId
                },
                success: function() {
                    $card.fadeOut(300, function() { location.reload(); });
                }
            });
        }
    });
});
</script>

</body>
</html>