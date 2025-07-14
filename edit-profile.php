<?php
session_start();
require_once('db-connection.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['id'];
$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $user_email = trim($_POST['user_email']);
    $new_password = trim($_POST['new_password']);

    if (!empty($new_password)) {
        $hashed = md5($new_password);
        $sql = "UPDATE tbl_users SET full_name = ?, user_email = ?, user_password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $full_name, $user_email, $hashed, $userId);
    } else {
        $sql = "UPDATE tbl_users SET full_name = ?, user_email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $full_name, $user_email, $userId);
    }

    if ($stmt->execute()) {
        $_SESSION['full_name'] = $full_name;
        $_SESSION['user_email'] = $user_email;
        $alert = '<div class="alert alert-success">Profile updated successfully.</div>';
    } else {
        $alert = '<div class="alert alert-danger">Something went wrong.</div>';
    }
}

// Fetch current data
$stmt = $conn->prepare("SELECT full_name, user_email FROM tbl_users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($full_name, $user_email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h3>Edit Profile</h3>
        <?= $alert ?>
        <form method="POST">
            <div class="mb-3">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($full_name) ?>" required>
            </div>
            <div class="mb-3">
                <label>Email Address</label>
                <input type="email" name="user_email" class="form-control" value="<?= htmlspecialchars($user_email) ?>" required>
            </div>
            <div class="mb-3">
                <label>New Password <small>(Leave blank to keep current)</small></label>
                <input type="password" name="new_password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
            <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>
</body>
</html>
