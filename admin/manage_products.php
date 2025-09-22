<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
include '../includes/db.php';
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f8fafc, #e3e7f7, #f6f9fc, #e0f7fa); 
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(60, 80, 120, 0.10);
            border: 2px solid #e3e7f7;
        }
        h2 {
            text-align: center;
            color: #4460aa; 
            font-weight: 700;
            letter-spacing: 1px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4460aa; 
            color: white;
        }
        td img {
            width: 50px;
            height: auto;
        }
        .actions {
            vertical-align: middle;
            text-align: center;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }
        .action-buttons a {
            margin: 0;
            padding: 5px 10px;
            color: #4460aa;
            text-decoration: none;
            border: 1px solid #4460aa;
            border-radius: 4px;
            font-weight: 600;
            transition: background-color 0.3s, color 0.3s;
        }
        .action-buttons a:hover {
            background-color: #4460aa;
            color: white;
        }
        .btn-back {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #4460aa;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s;
            text-align: center;
        }
        .btn-back:hover {
            background-color: #223269;
        }
        @media (max-width: 900px) {
            .container {
                width: 98%;
                padding: 10px;
            }
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Products</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?= $product['id']; ?></td>
                <td><?= htmlspecialchars($product['name']); ?></td>
                <td>$<?= number_format($product['price'], 2); ?></td>
                <td><?= htmlspecialchars($product['description']); ?></td>
                <td><img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="Product Image"></td>
                <td class="actions">
                    <div class="action-buttons">
                        <a href="edit_product.php?id=<?= $product['id']; ?>">Edit</a>
                        <a href="delete_product.php?id=<?= $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
</div>

</body>
</html>
