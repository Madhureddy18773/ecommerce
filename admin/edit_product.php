<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}
$product_id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    header("Location: manage_products.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $description = $_POST['description'] ?? '';
    $image = $product['image'];
    $update = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
    $update->execute([$name, $price, $description, $product_id]);
    header("Location: manage_products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Product</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e3e7f7, #f6f9fc, #e0f7fa, #f8fafc);
            margin: 0;
            padding: 0;
        }
        .edit-container {
            background: #fff;
            max-width: 400px;
            margin: 60px auto 0 auto;
            padding: 32px 28px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(44, 62, 80, 0.16);
            border: 2px solid #e3e7f7;
        }
        h2 {
            text-align: center;
            color: #4460aa;
            margin-bottom: 28px;
            font-weight: 700;
        }
        label {
            font-weight: 500;
            color: #3a3a3a;
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 10px;
            border: 1px solid #b7c3cb;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.2s;
            box-sizing: border-box;
            resize: none;
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus {
            border: 1.5px solid #4460aa;
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px 0;
            color: #fff;
            background: #4460aa;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 15px;
            box-shadow: 0 3px 8px rgba(68,96,170,0.07);
            transition: background 0.18s;
            font-weight: 600;
        }
        button:hover {
            background: #223269;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 24px;
            color: #4460aa;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.18s;
        }
        .back-link:hover {
            color: #223269;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit Product</h2>
        <form method="POST" action="">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>

            <label>Price ($):</label>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']); ?>" required>

            <label>Description:</label>
            <textarea name="description" rows="4" required><?= htmlspecialchars($product['description']); ?></textarea>

            <button type="submit">Update Product</button>
        </form>
        <a class="back-link" href="manage_products.php">Back to Manage Products</a>
    </div>
</body>
</html>
