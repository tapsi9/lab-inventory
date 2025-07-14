<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header('Location: user-dashboard.php');
    exit;
}

require_once('header.php');
include('db-connection.php');

$user_id = $_SESSION['id'];
$user_course = $_SESSION['user_course'];

// Total items
$stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_items WHERE course = ?");
$stmt->bind_param("s", $user_course);
$stmt->execute();
$stmt->bind_result($totalItems);
$stmt->fetch();
$stmt->close();

// Total borrowed
$stmt = $conn->prepare("
    SELECT COUNT(*) 
    FROM tbl_borrowed_items AS bi
    JOIN tbl_items AS ti ON bi.item_id = ti.id
    WHERE bi.status = 'borrowed'
      AND ti.course = ?
");
$stmt->bind_param("s", $user_course);
$stmt->execute();
$stmt->bind_result($totalBorrowed);
$stmt->fetch();
$stmt->close();

// Low stock
$stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_items WHERE quantity < 5 AND course = ?");
$stmt->bind_param("s", $user_course);
$stmt->execute();
$stmt->bind_result($lowStock);
$stmt->fetch();
$stmt->close();

// Categories
$stmt = $conn->prepare("SELECT COUNT(DISTINCT category) FROM tbl_items WHERE course = ?");
$stmt->bind_param("s", $user_course);
$stmt->execute();
$stmt->bind_result($categories);
$stmt->fetch();
$stmt->close();

// Last 5 items
$stmt = $conn->prepare("SELECT name, quantity FROM tbl_items WHERE course = ? ORDER BY id DESC LIMIT 5");
$stmt->bind_param("s", $user_course);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Category distribution
$cat_stmt = $conn->prepare("SELECT category, COUNT(*) as count FROM tbl_items WHERE course = ? GROUP BY category");
$cat_stmt->bind_param("s", $user_course);
$cat_stmt->execute();
$cat_result = $cat_stmt->get_result();

$categoryLabels = [];
$categoryCounts = [];

while ($row = $cat_result->fetch_assoc()) {
    $categoryLabels[] = $row['category'];
    $categoryCounts[] = $row['count'];
}
$cat_stmt->close();

// Recent borrowed items
$sql = "SELECT bi.borrow_date, i.name, bi.quantity 
        FROM tbl_borrowed_items bi 
        JOIN tbl_items i ON bi.item_id = i.id 
        WHERE i.course = ? AND bi.status = 'borrowed' 
        ORDER BY bi.borrow_date DESC 
        LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_course);
$stmt->execute();
$result = $stmt->get_result();
$borrowed_notifications = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Borrowing trend (last 7 days)
$trend_stmt = $conn->prepare("
    SELECT DATE(bi.borrow_date) as day, SUM(bi.quantity) as total 
    FROM tbl_borrowed_items bi
    JOIN tbl_items i ON bi.item_id = i.id
    WHERE i.course = ? AND bi.status = 'borrowed' AND borrow_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(borrow_date)
    ORDER BY day ASC
");
$trend_stmt->bind_param("s", $user_course);
$trend_stmt->execute();
$trend_result = $trend_stmt->get_result();

$trendLabels = [];
$trendData = [];

$dates = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[$date] = 0;
}

while ($row = $trend_result->fetch_assoc()) {
    $dates[$row['day']] = $row['total'];
}

foreach ($dates as $day => $count) {
    $trendLabels[] = date('M d', strtotime($day));
    $trendData[] = $count;
}
$trend_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #qrScannerModal {
            position: fixed; top: 0; left: 0;
            width: 100vw; height: 100vh;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1001;
            background-color: rgba(0, 0, 0, 0.6);
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .close-btn { position: absolute; top: 10px; right: 15px; cursor: pointer; font-size: 1.5rem; }
        #categoryChart, #trendChart { max-width: 100%; margin: auto; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="text-end">
        <span class="badge bg-primary">Logged in as: <?= $_SESSION['full_name']; ?></span>
    </div>

    <div class="row text-white mt-3 mb-3">
        <div class="col-md-3 mb-3">
            <div class="card bg-info"><div class="card-body"><h5>Total Items</h5><p><?= $totalItems ?></p></div></div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning"><div class="card-body"><h5>Borrowed Items</h5><p><?= $totalBorrowed ?></p></div></div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-danger"><div class="card-body"><h5>Low Stock</h5><p><?= $lowStock ?></p></div></div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success"><div class="card-body"><h5>Total Categories</h5><p><?= $categories ?></p></div></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h5>Inventory Activity</h5>
            <div class="border p-3 bg-light">
                <h6>Last 5 Items Added:</h6>
                <table class="table table-bordered table-sm">
                    <thead><tr><th>Name</th><th>Quantity</th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr><td><?= htmlspecialchars($item['name']); ?></td><td><?= $item['quantity']; ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <h5>Quick Actions</h5>
            <div class="d-grid gap-2 mt-4">
                <a href="add-items.php" class="btn btn-primary">+ Add New Item & Generate QR code</a>
                <a href="borrow-return.php" class="btn btn-secondary">↺ Borrow/Return Item</a>
                <a href="inventory_report.php" class="btn btn-dark">Inventory Report</a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h5>Borrowed Items</h5>
            <ul class="list-group">
                <?php foreach ($borrowed_notifications as $notif): ?>
                    <li class="list-group-item">
                        <strong><?= htmlspecialchars($notif['name']) ?></strong><br>
                        <small class="text-muted">Borrowed <?= $notif['quantity'] ?> pcs on <?= date("M d, Y H:i", strtotime($notif['borrow_date'])) ?></small>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($borrowed_notifications)): ?>
                    <li class="list-group-item text-muted">No recent borrowed items</li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="col-md-6">
            <h5>Item Distribution by Category</h5>
            <canvas id="categoryChart" width="300" height="300"></canvas>
        </div>
    </div>

    <div class="text-center mt-4">
        <h5>Borrowing Trends (Last 7 Days)</h5>
        <canvas id="trendChart" height="120"></canvas>
    </div>

    <div class="text-center mt-5">
        <button class="btn btn-outline-primary" id="launchScannerBtn">🔍 Launch QR Scanner</button>
    </div>
</div>

<div id="qrScannerModal">
    <div class="modal-content">
        <span class="close-btn" id="closeScannerBtn">&times;</span>
        <h2>Upload QR Code Image</h2>
        <input type="file" id="qrImageInput" accept="image/*" class="form-control mb-3">
        <div id="qrResult" class="alert alert-info" style="display: none;">
            <strong>Scanned QR Code:</strong> <span id="qrData"></span>
        </div>
        <canvas id="qrCanvas" hidden></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<script>
new Chart(document.getElementById('categoryChart'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($categoryLabels) ?>,
        datasets: [{
            label: 'Items',
            data: <?= json_encode($categoryCounts) ?>,
            backgroundColor: ['#0dcaf0', '#ffc107', '#198754', '#6c757d', '#6610f2', '#fd7e14']
        }]
    }
});

new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($trendLabels) ?>,
        datasets: [{
            label: 'Items Borrowed',
            data: <?= json_encode($trendData) ?>,
            fill: false,
            borderColor: '#0d6efd',
            backgroundColor: '#0d6efd',
            tension: 0.2
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true, precision: 0 }
        }
    }
});
</script>

<?php require_once('footer.php'); ?>
