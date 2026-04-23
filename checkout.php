<?php 
require_once('includes/functions.php');

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$order_placed = false;

if (isset($_POST['checkout_submit'])) {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    
    $selected_items = $_POST['selected_items'] ?? [];
    
    if (!empty($selected_items)) {
        $customer_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 'NULL';
        
        if ($customer_id === 'NULL') {
            $sql = "INSERT INTO orders (user_id, name, phone, email, status, created_at) 
                    VALUES (NULL, '$name', '$phone', '$email', 'Новый', NOW())";
        } else {
            $sql = "INSERT INTO orders (user_id, name, phone, email, status, created_at) 
                    VALUES ($customer_id, '$name', '$phone', '$email', 'Новый', NOW())";
        }
        dbquery($sql);
        $order_id = mysqli_insert_id($db);
        
        $total = 0;
        foreach ($selected_items as $product_id) {
            $product_id = intval($product_id);
            $qty = intval($_POST['qty_' . $product_id] ?? 1);
            
            $product = dbfetcha(dbquery("SELECT price FROM products WHERE id = $product_id"));
            if ($product) {
                $price = intval($product['price']);
                $subtotal = $price * $qty;
                $total += $subtotal;
                
                dbquery("INSERT INTO order_items (order_id, product_id, quantity, price) 
                        VALUES ($order_id, $product_id, $qty, $price)");
            }
        }
        
        dbquery("UPDATE orders SET total_price = $total WHERE id = $order_id");
        
        $_SESSION['cart'] = [];
        $order_placed = true;
    }
}

if ($order_placed) {
    header("Location: checkout.php?success=1");
    exit;
}

$success = isset($_GET['success']);

$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $ids_string = implode(',', $product_ids);
    $result = dbquery("SELECT * FROM products WHERE id IN ($ids_string)");
    while ($p = dbfetcha($result)) {
        $p['quantity'] = $_SESSION['cart'][$p['id']];
        $p['subtotal'] = $p['price'] * $p['quantity'];
        $cart_items[] = $p;
    }
}

$user = null;
if (isset($_SESSION['user_id'])) {
    $user = dbfetcha(dbquery("SELECT * FROM users2 WHERE id = " . intval($_SESSION['user_id'])));
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа — ТехноЛаб</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/catalog.css">
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

<main class="checkout-page">
    <div class="container">
        <h1 class="checkout-title">Оформление заказа</h1>
        
        <?php if ($success): ?>
        <div style="background: #d4edda; color: #155724; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            Заказ оформлен!
        </div>
        <?php endif; ?>
        
        <form method="post" class="checkout-form">
            <div class="checkout-grid">
                <div class="checkout-info">
                    <div class="checkout-section">
                        <h3>Контактные данные</h3>
                        <div class="form-group">
                            <label>Имя *</label>
                            <input type="text" name="name" value="<?php echo $user['username'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Телефон *</label>
                            <input type="tel" name="phone" value="<?php echo $user['phone'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo $user['email'] ?? ''; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="checkout-items">
                    <h3>Товары в заказе</h3>
                    <div class="checkout-products">
                        <?php foreach ($cart_items as $item): ?>
                        <div class="checkout-product">
                            <label class="product-checkbox">
                                <input type="checkbox" name="selected_items[]" value="<?php echo $item['id']; ?>" checked>
                                <img src="<?php echo !empty($item['image']) ? 'images/products/' . $item['image'] : 'images/no-product.png'; ?>" alt="">
                                <div class="product-details">
                                    <span class="product-name"><?php echo htmlspecialchars($item['name']); ?></span>
                                    <span class="product-price"><?php echo number_format($item['price'], 0, '', ' '); ?> ₽</span>
                                </div>
                            </label>
                            <div class="product-qty">
                                <label>Кол-во:</label>
                                <input type="number" name="qty_<?php echo $item['id']; ?>" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>">
                            </div>
                            <div class="product-subtotal"><?php echo number_format($item['subtotal'], 0, '', ' '); ?> ₽</div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="checkout-total">
                        <span>Итого:</span>
                        <span class="total-sum"><?php echo number_format(array_sum(array_column($cart_items, 'subtotal')), 0, '', ' '); ?> ₽</span>
                    </div>
                    
                    <button type="submit" name="checkout_submit" class="btn-checkout">Оформить заказ</button>
                </div>
            </div>
        </form>
    </div>
</main>

</body>
</html>