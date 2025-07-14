<?php
require_once('db-connection.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}




// Fetch recent borrowed items (notification dropdown)
$borrowed_notifications = [];
if (isset($conn)) {
    $sql = "SELECT ti.name, bi.quantity, bi.borrow_date 
        FROM tbl_borrowed_items AS bi
        JOIN tbl_items AS ti ON bi.item_id = ti.id
        WHERE bi.user_id = ? AND bi.status = 'borrowed'
        ORDER BY bi.borrow_date DESC 
        LIMIT 5";


    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $borrowed_notifications = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="custom.scss">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> 
</head>
<body style="background-color: #f1f2f7;">
<header>
    <nav class="navbar navbar-expand-lg bg-secondary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Inventory System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                    aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?= $items_url; ?>" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Items
                        </a>
                        <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="items-list.php">All Items</a></li>
    
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <li><a class="dropdown-item" href="add-items.php">Add New Item</a></li>
    <?php endif; ?>

    <li><a class="dropdown-item" href="inventory_report.php">Inventory Report</a></li>
</ul>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="borrow-return.php">Borrowed Items</a>
                    </li>
                </ul>

                <!-- Notifications Dropdown -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            <?php if (count($borrowed_notifications) > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= count($borrowed_notifications) ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (empty($borrowed_notifications)): ?>
                                <li><span class="dropdown-item-text">No recent borrowed items.</span></li>
                            <?php else: ?>
                                <?php foreach ($borrowed_notifications as $note): ?>
                                    <li>
                                        <span class="dropdown-item-text">
                                            <?= htmlspecialchars($note['name']) ?> (x<?= $note['quantity'] ?>) <br>
                                            <small class="text-muted"><?= date('M d, Y', strtotime($note['borrow_date'])) ?></small>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <i class="bi bi-person-circle"></i> &nbsp; <?= $_SESSION['user_name']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="edit-profile.php">Edit Profile</a></li>
                            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                            <li><a class="dropdown-item" href="privacy.php">Privacy</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
</header>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
