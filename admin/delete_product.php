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
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'Yes') {
        $delete = $conn->prepare("DELETE FROM products WHERE id = ?");
        $delete->execute([$product_id]);
    }
    header("Location: manage_products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Confirm Delete</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #fff6f6;
            margin: 0;
            padding: 0;
        }
        .delete-container {
            background: #fff;
            max-width: 400px;
            margin: 80px auto 0 auto;
            padding: 30px 28px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(220, 53, 69, 0.3);
            border: 2px solid #dc3545;
            text-align: center;
        }
        h2 {
            color: #dc3545;
            margin-bottom: 20px;
        }
        p {
            color: #5a5a5a;
            font-size: 16px;
            margin-bottom: 32px;
        }
        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .buttons button {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }
        .btn-yes {
            background-color: #dc3545;
            color: white;
        }
        .btn-yes:hover {
            background-color: #b52b34;
        }
        .btn-no {
            background-color: #ccc;
            color: #333;
        }
        .btn-no:hover {
            background-color: #aaa;
        }
        a.back-link {
            display: block;
            margin-top: 24px;
            text-align: center;
            color: #dc3545;
            text-decoration: none;
            font-weight: 500;
        }
        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="delete-container">
        <h2>Confirm Delete</h2>
        <p>Are you sure you want to <strong>delete</strong> the product "<em><?= htmlspecialchars($product['name']); ?></em>"?</p>
        <form method="POST" action="">
            <div class="buttons">
                <button type="submit" name="confirm" value="Yes" class="btn-yes">Yes, Delete</button>
                <button type="submit" name="confirm" value="No" class="btn-no">No, Cancel</button>
            </div>
        </form>
        <a href="manage_products.php" class="back-link">Back to Manage Products</a>
    </div>
</body>
</html>
