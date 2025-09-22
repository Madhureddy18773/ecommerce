<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f8fafc, #e3e7f7, #f6f9fc, #e0f7fa); 
            min-height: 100vh;
            margin: 0;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 16px rgba(60, 80, 120, 0.10);
            border-radius: 12px;
            border: 2px solid #e3e7f7;
        }
        h2 {
            text-align: center;
            color: #4460aa;
            font-weight: 700;
            letter-spacing: 1px;
        }
        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        nav a {
            text-decoration: none;
            padding: 12px 25px;
            background-color: #4460aa;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            transition: background-color 0.3s ease, box-shadow 0.3s;
            box-shadow: 0 2px 8px rgba(68, 96, 170, 0.08);
        }
        nav a:hover {
            background-color: #223269;
            box-shadow: 0 4px 12px rgba(68, 96, 170, 0.15);
        }
        .logout {
            background-color: #f44336;
        }
        .logout:hover {
            background-color: #e53935;
        }
        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
            color: #777;
        }
        @media (max-width: 700px) {
            .container {
                width: 95%;
                padding: 16px;
            }
            nav a {
                font-size: 15px;
                padding: 10px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <nav>
            <a href="add_product.php">Add Product</a>
            <a href="manage_products.php">Manage Products</a>
            <a href="logout.php" class="logout">Logout</a>
        </nav>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Admin Dashboard</p>
    </footer>
</body>
</html>
