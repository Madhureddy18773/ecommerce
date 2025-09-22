<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php'; 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}
$product_id = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<p>Product not found.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        .single-product {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .single-product img {
            max-width: 100%;
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            padding: 8px 14px;
            border: 1px solid #007bff;
            border-radius: 6px;
        }
        .back-link:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="single-product">
        <h1><?= htmlspecialchars($product['name']); ?></h1>
        <p><strong>Price:</strong> $<?= number_format($product['price'], 2); ?></p>
        <p><?= nl2br(htmlspecialchars($product['description'])); ?></p>
        <?php if (!empty($product['image'])) : ?>
            <img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" />
        <?php endif; ?>
        <a href="../index.php" class="back-link">Back to Products</a>
    </div>
</body>
</html>
