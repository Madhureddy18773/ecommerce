<?php
include '../includes/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $otp = rand(100000, 999999);
        $_SESSION['reset_email'] = $email;
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expiry'] = time() + 300; 

        $to = $email;
        $subject = "Password Reset OTP";
        $message = "Your OTP for password reset is: $otp";
        $headers = "From: noreply@yourdomain.com";

        mail($to, $subject, $message, $headers);

        header("Location: verify_otp.php");
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
      background: #f6f9fc;
      font-family: "Segoe UI", Arial, sans-serif;
      color: #222;
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .container {
      max-width: 400px;
      width: 100%;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 8px 30px rgba(68, 68, 68, 0.12);
      padding: 32px 24px;
      box-sizing: border-box;
    }
    h2 {
      text-align: center;
      color: #4460aa;
      margin-bottom: 24px;
      font-weight: 600;
      font-size: 1.8em;
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      font-size: 1.05em;
    }
    input[type="email"] {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 20px;
      border-radius: 7px;
      border: 1px solid #d0d4df;
      font-size: 1em;
      transition: border-color 0.3s;
    }
    input[type="email"]:focus {
      outline: none;
      border-color: #4460aa;
      box-shadow: 0 0 5px rgba(68, 96, 170, 0.3);
    }
    button {
      width: 100%;
      background-color: #4460aa;
      color: white;
      border: none;
      padding: 12px;
      font-size: 1.1em;
      font-weight: 600;
      border-radius: 7px;
      cursor: pointer;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 8px rgba(68, 96, 170, 0.3);
    }
    button:hover {
      background-color: #223269;
      box-shadow: 0 6px 12px rgba(34, 50, 105, 0.5);
    }
    .error-message {
      margin-top: 15px;
      color: #d33;
      text-align: center;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Forgot Password</h2>
    <form method="POST" action="">
      <label for="email">Enter your registered email:</label>
      <input type="email" name="email" id="email" required />
      <button type="submit">Send OTP</button>
    </form>
    <?php if (isset($error)): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
