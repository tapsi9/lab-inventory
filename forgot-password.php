<?php
require_once('config.php');
require_once('db-connection.php');
require_once('send-email.php'); // ✅ Uses PHPMailer here
date_default_timezone_set('Asia/Manila'); // or your timezone


$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert = '<div class="alert alert-danger">Please enter a valid email address.</div>';
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id, full_name FROM tbl_users WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires_at = date("Y-m-d H:i:s", time() + 3600); // 1 hour expiration

            // Save token in a password reset table
            $insert = $conn->prepare("INSERT INTO tbl_password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            $insert->bind_param("iss", $user['id'], $token, $expires_at);
            $insert->execute();

            // Create reset link
            $reset_link = BASE_URL . "reset-password.php?token=$token";

            $subject = "Password Reset Request";
            $message = "
                <html>
                <head><title>Password Reset</title></head>
                <body>
                    <p>Hi <strong>{$user['full_name']}</strong>,</p>
                    <p>You requested to reset your password.</p>
                    <p><a href='$reset_link'>Click here to reset your password</a></p>
                    <p>This link will expire in 1 hour.</p>
                    <p>If you did not request this, you can ignore this message.</p>
                </body>
                </html>
            ";

            // ✅ Use PHPMailer
            if (sendEmail($email, $subject, $message)) {
                $alert = '<div class="alert alert-success">A password reset link has been sent to your email.</div>';
            } else {
                $alert = '<div class="alert alert-danger">Failed to send email. Try again later.</div>';
            }

        } else {
            $alert = '<div class="alert alert-danger">Email not found in the system.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h3 class="text-center mb-4">Forgot Your Password?</h3>
                <?= $alert ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Enter your registered email</label>
                        <input type="email" name="email" id="email" class="form-control" required placeholder="name@example.com">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                    <div class="text-center mt-3">
                        <a href="login.php">← Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
