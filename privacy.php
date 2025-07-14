<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Privacy Policy</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Privacy Policy</h2>

    <p>We value your privacy. This system collects only the necessary information required to maintain accurate records for the Laboratory Inventory Management System. By using this platform, you agree to the collection and use of information in accordance with this policy.</p>

    <h4 class="mt-4">Information We Collect</h4>
    <ul>
        <li>Your username and full name</li>
        <li>Email address and Student ID (for students)</li>
        <liBorrowing history and item interaction records</li>
    </ul>

    <h4 class="mt-4">How We Use Your Information</h4>
    <ul>
        <li>To manage item borrowings and returns</li>
        <li>To send notifications or updates</li>
        <li>To help improve inventory monitoring and accountability</li>
    </ul>

    <h4 class="mt-4">Security</h4>
    <p>Your data is protected through secure database access and authentication methods. However, no system is 100% secure — please use a strong password and do not share your login credentials.</p>

    <h4 class="mt-4">Changes to This Policy</h4>
    <p>We may update this Privacy Policy from time to time. You will be notified of any significant changes through this platform.</p>

    <a href="index.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>
</body>
</html>
