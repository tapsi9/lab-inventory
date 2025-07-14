<?php
session_start();
require_once('db-connection.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['id'];
$alert = '';

// Handle settings form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dark_mode = isset($_POST['dark_mode']) ? 1 : 0;
    $notify_email = isset($_POST['notify_email']) ? 1 : 0;

    $sql = "UPDATE tbl_users SET dark_mode = ?, notify_email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $dark_mode, $notify_email, $userId);

    if ($stmt->execute()) {
        $alert = '<div class="alert alert-success">Settings updated.</div>';
    } else {
        $alert = '<div class="alert alert-danger">Failed to update settings.</div>';
    }
}

// Fetch current user settings
$dark_mode = 0;
$notify_email = 1;

$sql = "SELECT dark_mode, notify_email FROM tbl_users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($dark_mode, $notify_email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Settings</h3>
    <?= $alert ?>
    <form method="POST">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="dark_mode" id="dark_mode" <?= $dark_mode ? 'checked' : '' ?>>
            <label class="form-check-label" for="dark_mode">Enable Dark Mode</label>
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="notify_email" id="notify_email" <?= $notify_email ? 'checked' : '' ?>>
            <label class="form-check-label" for="notify_email">Receive Email Notifications</label>
        </div>

        <button type="submit" class="btn btn-primary">Save Settings</button>
        <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>
</body>
</html>
