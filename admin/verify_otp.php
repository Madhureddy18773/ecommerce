<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}
$message = "";
$success = "";
if (isset($_POST['resend_otp'])) {
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 900; // 15 minutes
    $message = "New OTP generated for testing: $otp";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = trim($_POST['otp']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    if (time() > ($_SESSION['otp_expiry'] ?? 0)) {
        $message = "OTP expired. Please try again.";
        unset($_SESSION['otp'], $_SESSION['otp_expiry'], $_SESSION['reset_email']);
    } elseif ($entered_otp != ($_SESSION['otp'] ?? '')) {
        $message = "Invalid OTP.";
    } elseif ($new_password !== $confirm_password) {
        $message = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $message = "Password must be at least 6 characters.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed_password, $_SESSION['reset_email']]);
        $success = "Password updated successfully! <a href='login.php'>Login</a>";
        unset($_SESSION['otp'], $_SESSION['otp_expiry'], $_SESSION['reset_email']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Verify OTP & Reset Password</title>
<style>
body {
    height: 100vh;
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #e3ecfc 0%, #f0f4ff 100%);
    display: flex;
    justify-content: center;
    align-items: center;
}
.container {
    background: #fff;
    max-width: 400px;
    width: 100%;
    border-radius: 20px;
    padding: 40px 36px 38px 36px;
    box-shadow: 0 6px 36px rgba(44, 98, 241, 0.12);
    margin: 40px 16px;
    text-align: left;
}
h2 {
    color: #1a4d8f;
    font-weight: 600;
    font-size: 1.85rem;
    margin-bottom: 36px;
    text-align: center;
    letter-spacing: 0.02em;
}
label {
    display: block;
    margin-bottom: 8px;
    font-size: 1rem;
    font-weight: 600;
    color: #242424;
}
input[type="text"],
input[type="password"],
input[type="email"] {
    width: 100%;
    padding: 14px 12px;
    margin-bottom: 24px;
    font-size: 1.04rem;
    border-radius: 10px;
    border: 1.4px solid #bdc8e0;
    background: #f7fafd;
    box-sizing: border-box;
    transition: border-color 0.2s ease-in-out;
}
input:focus {
    border-color: #2f60d1;
    outline: none;
    box-shadow: 0 0 0 3px rgba(47, 96, 209, 0.15);
}
button {
    width: 100%;
    padding: 16px 0;
    background: #2161d4;
    color: white;
    font-weight: 700;
    font-size: 1.17rem;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(33, 97, 212, 0.16);
    cursor: pointer;
    transition: background-color 0.25s ease-in-out, transform 0.15s ease;
    margin-bottom: 12px;
}
button:hover {
    background: #184a9d;
    transform: translateY(-2px);
}
.resend-btn {
    background: #1c5295;
    box-shadow: 0 4px 12px rgba(28, 82, 149, 0.15);
}
.resend-btn:hover {
    background: #143a67;
}
.message {
    margin-bottom: 16px;
    font-weight: 600;
    font-size: 1rem;
    color: #b63939;
    background: #ffe6e6;
    padding: 10px 14px;
    border: 1.4px solid #f4a0a0;
    border-radius: 8px;
    text-align: center;
}
.success {
    background: #e7f9e7;
    color: #216b21;
    border: 1.4px solid #a1dca1;
}
</style>
</head>
<body>
<div class="container">
    <h2>Verify OTP & Reset Password</h2>
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="message success"><?= $success ?></div>
    <?php else: ?>
    <form method="post">
        <label for="otp">Enter OTP:</label>
        <input type="text" id="otp" name="otp" required autocomplete="off" />

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required />

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required />

        <button type="submit" name="reset_password">Reset Password</button>
        <button type="submit" name="resend_otp" class="resend-btn">Resend OTP</button>
    </form>
    <p style="text-align:center; font-weight:600; color:#185ec3; margin-top:12px;">
        For testing, your OTP is: <?= htmlspecialchars($_SESSION['otp'] ?? '') ?>
    </p>
    <?php endif; ?>
</div>
</body>
</html>
