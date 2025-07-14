<?php
require_once('config.php');
require_once('db-connection.php');
date_default_timezone_set('Asia/Manila'); // or your timezone


$token = $_GET['token'] ?? '';
$alert = '';
$show_form = false;

if ($token) {
    // Check if token exists and is not expired
    $stmt = $conn->prepare("SELECT * FROM tbl_password_resets WHERE token = ? AND expires_at >= NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $resetData = $result->fetch_assoc();

    if ($resetData) {
        $show_form = true;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = trim($_POST['new_password']);
            $confirm_password = trim($_POST['confirm_password']);

            if ($new_password !== $confirm_password) {
                $alert = '<div class="alert alert-danger">Passwords do not match.</div>';
            } elseif (strlen($new_password) < 6) {
                $alert = '<div class="alert alert-warning">Password must be at least 6 characters long.</div>';
            } else {
                // Update password in tbl_users
                $hashed_password = md5($new_password);
                $update = $conn->prepare("UPDATE tbl_users SET user_password = ? WHERE id = ?");
                $update->bind_param("si", $hashed_password, $resetData['user_id']);
                if ($update->execute()) {
                    // Remove the reset token
                    $delete = $conn->prepare("DELETE FROM tbl_password_resets WHERE token = ?");
                    $delete->bind_param("s", $token);
                    $delete->execute();

                    $alert = '<div class="alert alert-success">Password has been reset. You can now <a href="login.php">login</a>.</div>';
                    $show_form = false;
                } else {
                    $alert = '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
                }
            }
        }
    } else {
        $alert = '<div class="alert alert-danger">Invalid or expired reset link.</div>';
    }
} else {
    $alert = '<div class="alert alert-danger">Missing token.</div>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h3 class="text-center mb-4">Reset Password</h3>
                <?= $alert ?>
                <?php if ($show_form): ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
