<?php
session_start();
include '../includes/db.php';
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $otp = rand(100000, 999999);
        $_SESSION['reset_email'] = $email;
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expiry'] = time() + 900; 
        header("Location: reset_password.php");
        exit();
    } else {
        $error = "Email not registered.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Forgot Password</title>
<style>
body {
    background: #f5f8fc;
    font-family: "Segoe UI", Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}
.container {
    max-width: 400px;
    width: 100%;
    background: #fff;
    border-radius: 12px;
    padding: 36px 28px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.15);
}
h2 {
    color: #3366cc;
    text-align: center;
    margin-bottom: 28px;
    font-weight: 700;
    letter-spacing: 0.05em;
}
input[type="email"] {
    width: 100%;
    padding: 14px;
    margin-bottom: 22px;
    border-radius: 8px;
    border: 1.5px solid #b0afef;
    font-size: 1em;
    transition: border-color 0.3s ease;
    background: #f4f6fb;
}
input[type="email"]:focus {
    outline: none;
    border-color: #3366cc;
    box-shadow: 0 0 8px rgba(51, 102, 204, 0.25);
}
button {
    width: 100%;
    padding: 14px;
    background: #3366cc;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.1em;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
button:hover {
    background: #2451a6;
}
.error-message {
    color: #b94444;
    text-align: center;
    font-weight: 600;
    margin-top: 18px;
}
</style>
</head>
<body>
<div class="container">
    <h2>Forgot Password</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your registered email" required />
        <button type="submit">Send OTP</button>
    </form>
    <?php if ($error): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
</div>
</body>
</html>
