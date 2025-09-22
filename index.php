<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login.php");
    exit();
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

include 'includes/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_data = $stmt->fetch(PDO::FETCH_ASSOC);
$cart_count = $cart_data['total_items'] ?? 0;

$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Online Store</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .cart-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .cart-icon {
            vertical-align: middle;
            width: 26px;
            height: 26px;
            position: relative;
        }
        .cart-badge {
            position: absolute;
            top: -6px;       
            left: 16px;     
            background: #dc3545;
            color: #fff;
            border-radius: 50%;
            padding: 1px 5px;
            font-size: 0.65em;
            font-weight: bold;
            min-width: 14px;
            height: 14px;
            line-height: 14px;
            text-align: center;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Welcome to RCB'S Store</h1>
            <nav>
                <a href="pages/login.php">Login</a>
                <a href="pages/register.php">Register</a>
                <a href="pages/cart.php" class="cart-link">
                    <img src="images/cart-icon.png" alt="Cart" class="cart-icon" />
                    Cart
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-badge"><?= $cart_count ?></span>
                    <?php endif; ?>
                </a>
                <a href="pages/logout.php" class="logout-button">Logout</a>
            </nav>
        </div>
    </header>
    <div class="main-container">
        <main>
            <h2>Products</h2>
            <div class="product-list">
                <?php if (empty($products)) : ?>
                    <p>No products available.</p>
                <?php else : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="product">
                            <h3><?= htmlspecialchars($product['name']); ?></h3>
                            <p>Price: $<?= number_format($product['price'], 2); ?></p>
                            <p><?= htmlspecialchars($product['description']); ?></p>
                            <?php if (!empty($product['image'])) : ?>
                                <img src="images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-image" />
                            <?php endif; ?>
                            <div class="product-buttons" style="display:flex; gap:10px;">
                                <a href="pages/product_view.php?id=<?= $product['id']; ?>" 
                                   class="view-button" 
                                   style="padding:10px 16px; background:#27d07e; color:#fff; border:none; border-radius:5px; text-decoration:none; display:flex; align-items:center; justify-content:center;">
                                  View
                                </a>
                                <form method="POST" action="pages/cart.php" style="margin: 0;">
                                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>" />
                                    <button type="submit" name="add_to_cart" class="add-to-cart-button" style="padding:10px 16px; background:#27d07e; color:#fff; border:none; border-radius:5px;">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <footer>
        <p>&copy; <?= date('Y'); ?> Online Store. All rights reserved.</p>
    </footer>
</body>
</html>
