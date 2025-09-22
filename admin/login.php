<?php
include '../includes/db.php';
session_start();
$error_message = ""; 
$login_attempted = false; 
if (isset($_POST['login'])) {
    $login_attempted = true; 
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']); 
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error_message = "Invalid credentials.";
    } else {
        if ($user['role'] !== 'admin') {
            $error_message = "Not an admin.";
        } else {
            if (password_verify($password, $user['password'])) {
                $_SESSION['admin_id'] = $user['id'];
                if ($remember) {
                    setcookie('admin_email', $email, time() + (86400 * 30), "/"); 
                } else {
                    setcookie('admin_email', '', time() - 3600, "/");
                }
                header("Location: dashboard.php");
                exit();
            } else {
                $error_message = "Invalid credentials.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Login</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #dc142c, #101c3e);
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #333;
    }
    .login-container {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: 3px solid #d4af37; 
    }
    .top-error-message {
        color: #e74c3c;
        font-weight: bold;
        text-align: center;
        margin-bottom: 15px;
        font-size: 1em;
    }
    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }
    input {
        width: 100%;
        padding: 10px;
        margin: 10px 0 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .checkbox-container {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    .checkbox-container input[type="checkbox"] {
        margin-right: 8px;
        width: 16px;
        height: 16px;
    }
    button {
        width: 100%;
        padding: 10px;
        background-color: #dc142c;
        border: none;
        color: white;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #a81322;
    }
    .forgot-password {
        display: block;
        text-align: center;
        margin-top: 15px;
        text-decoration: underline;
        color: #101c3e;
    }
    .forgot-password:hover {
        color: #dc142c;
    }
</style>
</head>
<body>

<div class="login-container">
    <!-- Show error only if form submitted and there's an error -->
    <?php if ($login_attempted && $error_message): ?>
        <div class="top-error-message"><?= htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <h2>Admin Login</h2>
    <form method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <div class="checkbox-container">
            <input type="checkbox" id="show-password">
            <label for="show-password" style="margin:0; font-weight:normal; font-size:0.9em;">Show Password</label>
        </div>

        <div class="checkbox-container">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" style="margin:0; font-weight:normal; font-size:0.9em;">Remember me</label>
        </div>

        <button type="submit" name="login">Login</button>

        <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
    </form>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const showPasswordCheckbox = document.getElementById('show-password');

    showPasswordCheckbox.addEventListener('change', function() {
        passwordInput.type = this.checked ? 'text' : 'password';
    });
</script>

</body>
</html>
