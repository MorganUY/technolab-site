<?php 
require_once('includes/functions.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit;
}

if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    $result = dbquery("SELECT stock FROM products WHERE id = $product_id");
    $product = dbfetcha($result);
    
    if ($product) {
        $current_qty = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;
        $max_stock = intval($product['stock']);
        
        if ($current_qty < $max_stock) {
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]++;
            } else {
                $_SESSION['cart'][$product_id] = 1;
            }
        } else {
            echo "<script>alert('Недостаточно товара на складе! Максимум: $max_stock шт.');</script>";
        }
    }
}

if (isset($_POST['update_qty']) && isset($_POST['product_id']) && isset($_POST['qty'])) {
    $product_id = intval($_POST['product_id']);
    $qty = intval($_POST['qty']);
    
    $result = dbquery("SELECT stock FROM products WHERE id = $product_id");
    $product = dbfetcha($result);
    $max_stock = intval($product['stock']);
    
    if ($qty > $max_stock) {
        $qty = $max_stock;
    }
    
    if ($qty > 0) {
        $_SESSION['cart'][$product_id] = $qty;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }
    
    echo json_encode(['success' => true, 'qty' => $qty, 'max' => $max_stock]);
    exit;
}

if (isset($_POST['remove_item']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    unset($_SESSION['cart'][$product_id]);
    echo json_encode(['success' => true]);
    exit;
}

if (isset($_POST['checkout'])) {
    $customer_id = isset($_SESSION['my_inside']) ? intval($_SESSION['user_id']) : 0;
    $total = 0;
    
    $order_sql = "INSERT INTO orders (user_id, total_price, status, created_at) VALUES ($customer_id, 0, 'Новый', NOW())";
    dbquery($order_sql);
    $order_id = mysqli_insert_id($db);
    
    foreach ($_SESSION['cart'] as $product_id => $qty) {
        $product_id = intval($product_id);
        $qty = intval($qty);
        
        $product_result = dbquery("SELECT price FROM products WHERE id = $product_id");
        $product = dbfetcha($product_result);
        
        if ($product) {
            $price = intval($product['price']);
            $total += $price * $qty;
            
            $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $qty, $price)";
            dbquery($item_sql);
        }
    }
    
    dbquery("UPDATE orders SET total_price = $total WHERE id = $order_id");
    
    $_SESSION['cart'] = [];
    
    echo "<script>alert('Заказ успешно оформлен! Номер заказа: $order_id');</script>";
}

$cart_items = [];
$cart_total = 0;

if (!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $ids_string = implode(',', $product_ids);
    
    $result = dbquery("SELECT * FROM products WHERE id IN ($ids_string)");
    
    while ($product = dbfetcha($result)) {
        $product_id = $product['id'];
        $qty = $_SESSION['cart'][$product_id];
        $product['quantity'] = $qty;
        $product['subtotal'] = $product['price'] * $qty;
        $cart_items[] = $product;
        $cart_total += $product['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина — ТехноЛаб</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/catalog.css">
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

<main class="cart-page">
    <div class="container">
        <h1 class="cart-title">
            Корзина
            <?php if (!empty($cart_items)): ?>
                <a href="cart.php?clear=1" class="clear-cart">Очистить</a>
            <?php endif; ?>
        </h1>
        
        <?php if (empty($cart_items)): ?>
            <div class="cart-empty">
                <p>Корзина пуста</p>
                <a href="catalog.php">Перейти в каталог</a>
            </div>
        <?php else: ?>
            <form method="post" id="cart-form">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Сумма</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): 
                            $image_path = !empty($item['image']) ? 'images/products/' . $item['image'] : 'images/no-product.png';
                        ?>
                        <tr class="cart-item">
                            <td>
                                <div class="cart-product">
                                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <span class="cart-product-name"><?php echo htmlspecialchars($item['name']); ?></span>
                                </div>
                            </td>
                            <td class="cart-price"><?php echo number_format($item['price'], 0, '', ' '); ?> ₽</td>
                            <td>
                                <div class="qty-box">
                                    <button type="button" class="qty-btn minus-btn">−</button>
                                    <input type="number" name="quantity[<?php echo $item['id']; ?>]" 
                                           class="qty-input" 
                                           value="<?php echo $item['quantity']; ?>" 
                                           min="1" 
                                           max="<?php echo $item['stock']; ?>"
                                           data-product-id="<?php echo $item['id']; ?>"
                                           data-stock="<?php echo $item['stock']; ?>">
                                    <button type="button" class="qty-btn plus-btn">+</button>
                                </div>
                            </td>
                            <td class="cart-total"><?php echo number_format($item['subtotal'], 0, '', ' '); ?> ₽</td>
                            <td>
                                <button type="button" class="cart-remove" data-id="<?php echo $item['id']; ?>">✕</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="cart-summary">
                    <div class="cart-summary-total">
                        Итого: <span><?php echo number_format($cart_total, 0, '', ' '); ?> ₽</span>
                    </div>
                    <a href="checkout.php" class="btn-checkout">Оформить заказ</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<script>
$(document).ready(function() {
    $('.plus-btn').on('click', function() {
        var $input = $(this).siblings('.qty-input');
        var productId = $input.data('product-id');
        var stock = parseInt($input.data('stock'));
        var currentQty = parseInt($input.val());
        
        if (currentQty < stock) {
            var newQty = currentQty + 1;
            updateQuantity(productId, newQty, $input);
        } else {
            alert("Недостаточно товара на складе! Максимум: " + stock + " шт.");
        }
    });
    
    $('.minus-btn').on('click', function() {
        var $input = $(this).siblings('.qty-input');
        var productId = $input.data('product-id');
        var currentQty = parseInt($input.val());
        
        if (currentQty > 1) {
            var newQty = currentQty - 1;
            updateQuantity(productId, newQty, $input);
        }
    });
    
    $('.qty-input').on('change', function() {
        var productId = $(this).data('product-id');
        var stock = parseInt($(this).data('stock'));
        var val = parseInt($(this).val());
        
        if (val > stock) {
            alert("Недостаточно товара на складе! Максимум: " + stock);
            $(this).val(stock);
            val = stock;
        }
        if (val < 1) {
            $(this).val(1);
            val = 1;
        }
        
        updateQuantity(productId, val, $(this));
    });
    
    $('.cart-remove').on('click', function() {
        var productId = $(this).data('id');
        var $row = $(this).closest('tr');
        
        $.ajax({
            url: 'cart.php',
            type: 'POST',
            data: {
                remove_item: true,
                product_id: productId
            },
            success: function() {
                $row.fadeOut(300, function() { location.reload(); });
            }
        });
    });
    
    function updateQuantity(productId, quantity, $input) {
        $.ajax({
            url: 'cart.php',
            type: 'POST',
            data: {
                update_qty: true,
                product_id: productId,
                qty: quantity
            },
            success: function(response) {
                var data = JSON.parse(response);
                $input.val(data.qty);
                location.reload();
            }
        });
    }
});
</script>

</body>
</html>