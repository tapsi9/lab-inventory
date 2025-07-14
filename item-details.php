<?php
include 'db-connection.php';
session_start();

if (!isset($_GET['id'])) {
    die('Item not specified.');
}

$item_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM tbl_items WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die('Item not found.');
}

$user_course = $_SESSION['user_course'] ?? '';
$isAllowed = ($user_course === $item['course']);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($item['name']) ?> Details</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2><?= htmlspecialchars($item['name']) ?></h2>
    <img src="<?= htmlspecialchars($item['photo']) ?>" alt="Item Photo" class="img-thumbnail mb-3" style="max-width: 300px;">
    <p><strong>Category:</strong> <?= htmlspecialchars($item['category']) ?></p>
    <p><strong>Available Quantity:</strong> <?= htmlspecialchars($item['quantity']) ?></p>
    <p><strong>Course:</strong> <?= htmlspecialchars($item['course']) ?></p>

    <?php if (!$isAllowed): ?>
        <div class="alert alert-warning mt-4">
            This item belongs to the <strong><?= htmlspecialchars($item['course']) ?></strong> course.
            Ask the <strong><?= htmlspecialchars($item['course']) ?></strong> custodian for further assistance.
        </div>
    <?php else: ?>
        <a href="borrow-return.php?item_id=<?= $item_id ?>" class="btn btn-primary">Borrow this Item</a>
    <?php endif; ?>

    <div class="mt-3">
        <a href="<?= ($_SESSION['role'] === 'admin') ? 'index.php' : 'user-dashboard.php' ?>" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</body>
</html>
