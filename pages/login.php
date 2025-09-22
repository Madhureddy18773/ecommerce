<?php
include('../includes/db.php');  
session_start();
$error_message = ""; 
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']); 
    $admin_email = "admin@gmail.com";  
    if (strcasecmp($email, $admin_email) === 0) { 
        $error_message = "Please login with user mail id.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            if ($remember) {
                setcookie('user_email', $email, time() + (86400 * 30), "/"); 
            } else {
                setcookie('user_email', '', time() - 3600, "/"); 
            }
            header("Location: ../index.php"); 
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login</title>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(135deg, #dc142c, #101c3e);
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #333;
    }
    .login-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: 3px solid #d4af37; 
        width: 100%;
        max-width: 400px;
    }
    .top-error-message {
        color: #e74c3c !important;
        font-weight: bold;
        text-align: center;
        margin-bottom: 15px;
        font-size: 1.1em;
    }
    h2 {
        text-align: center;
        color: #222;
        margin-bottom: 20px;
    }
    label {
        font-size: 1.1em;
        margin-bottom: 5px;
        display: block;
    }
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1em;
    }
    .checkbox-container {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    .checkbox-container input[type="checkbox"] {
        margin-right: 8px;
        width: 16px;
        height: 16px;
    }
    button {
        width: 100%;
        padding: 12px;
        background-color: #dc142c;  
        color: white;
        font-size: 1.1em;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #b01023;  
    }
    .error-message {
        color: #e74c3c;
        font-size: 1em;
        text-align: center;
        margin-top: 10px;
    }
    a {
        color: #101c3e;
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="login-container">

    <?php if ($error_message): ?>
        <div class="top-error-message"><?= htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <h2>Login</h2>
    <form method="POST">
       <label>Email:</label>
       <input type="email" name="email" required />
       <label>Password:</label>
       <input type="password" id="password" name="password" required />
       <div class="checkbox-container">
           <input type="checkbox" id="show-password" />
           <label for="show-password" style="margin:0; font-size:0.9em;">Show Password</label>
       </div>
       <div class="checkbox-container">
           <input type="checkbox" id="remember" name="remember" />
           <label for="remember" style="margin:0; font-size:0.9em;">Remember me</label>
       </div>
       <button type="submit" name="login">Login</button>
       <a href="forgot_password.php" style="display:block;text-align:center;margin-top:10px;">Forgot Password?</a>
    </form>

</div>

<script>
    const passwordInput = document.getElementById('password');
    const showPasswordCheckbox = document.getElementById('show-password');

    showPasswordCheckbox.addEventListener('change', function() {
        if (this.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
</script>

</body>
</html>
