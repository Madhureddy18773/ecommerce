<?php
include('../includes/db.php');  
session_start();
if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $role = 'user'; 
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $error_message = "Email is already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $role]);
        header("Location: login.php?registered=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(to bottom, #e0f7fa, #ffffff);
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .register-container, .login-container {
        background-color: #ffffff;
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    h2 {
        color: #333333;
        margin-bottom: 25px;
        font-size: 28px;
    }
    label {
        font-size: 1.1em;
        margin-bottom: 5px;
        display: block;
        text-align: left;
    }
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 1em;
        transition: border 0.3s;
    }
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #80d0ff;
        outline: none;
    }
    .main-btn {
        width: 100%;
        padding: 14px;
        background-color: #4965c0; 
        color: white;
        font-size: 1.1em;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
        margin-top: 10px;
        text-decoration: none;
        display: inline-block;
    }
    .main-btn:hover {
        background-color: #3b5399; 
        color: #fff;
        transform: translateY(-2px);
    }
    .error-message {
        color: #e74c3c;
        font-size: 1em;
        text-align: center;
        margin-top: 15px;
    }
</style>
</head>
<body>
    <div class="register-container">
        <h2>Create Account</h2>
        <form method="POST">
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit" name="register" class="main-btn">Register</button>
        </form>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
    </div>
    <div class="login-container">
        <a href="login.php" class="main-btn">Back to Login</a>
    </div>
</body>
</html>
