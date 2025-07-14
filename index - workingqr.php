<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Restrict non-admin users
if ($_SESSION['role'] !== 'admin') {
    header('Location: user-dashboard.php');
    exit;
}

require_once('header.php');



$user_id = $_SESSION['id'];
$user_course = $_SESSION['user_course'];

// Unified connection based on course
include('db-connection.php');

// Overview stats

// Total Items
$stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_items WHERE course = ?");
$stmt->bind_param("s", $user_course);
$stmt->execute();
$stmt->bind_result($totalItems);
$stmt->fetch();
$stmt->close();

// Borrowed Today
$stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_borrowed_items WHERE DATE(borrow_date) = CURDATE() AND user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($borrowedToday);
$stmt->fetch();
$stmt->close();

// Low Stock
$stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_items WHERE quantity < 5 AND course = ?");
$stmt->bind_param("s", $user_course);
$stmt->execute();
$stmt->bind_result($lowStock);
$stmt->fetch();
$stmt->close();

// Total Categories
$stmt = $conn->prepare("SELECT COUNT(DISTINCT category) FROM tbl_items WHERE course = ?");
$stmt->bind_param("s", $user_course);
$stmt->execute();
$stmt->bind_result($categories);
$stmt->fetch();
$stmt->close();

// Last 5 Items
$stmt = $conn->prepare("SELECT name, quantity FROM tbl_items WHERE course = ? ORDER BY id DESC LIMIT 5");
$stmt->bind_param("s", $user_course);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Category chart data
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-9">
            <input type="text" class="form-control" placeholder="Search inventory...">
        </div>
        <div class="col-md-3 text-end">
            <span class="badge bg-primary">Logged in as: <?= $_SESSION['full_name']; ?></span>
        </div>
    </div>

    <div class="row text-white">
        <div class="col-md-3 mb-3">
            <div class="card bg-info"><div class="card-body"><h5>Total Items</h5><p><?= $totalItems ?></p></div></div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning"><div class="card-body"><h5>Borrowed Today</h5><p><?= $borrowedToday ?></p></div></div>
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
            <div class="d-grid gap-2">
                <a href="add-items.php" class="btn btn-primary mt-4">+ Add New Item & Generate QR code</a>
                <a href="borrow-return.php" class="btn btn-secondary">↺ Borrow/Return Item</a>
                <a href="inventory_report.php" class="btn btn-dark">Inventory Report</a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h5>Borrowed Items</h5>
            <ul class="list-group">
                <?php if (count($borrowed_notifications) > 0): ?>
                    <?php foreach ($borrowed_notifications as $notif): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($notif['name']) ?></strong><br>
                            <small class="text-muted">Borrowed <?= $notif['quantity'] ?> pcs on <?= date("M d, Y H:i", strtotime($notif['borrow_date'])) ?></small>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted">No recent borrowed items</li>
                <?php endif; ?>
            </ul>
        </div>
        <div style="max-width: 300px; margin: auto; text-align: center;">
    <h5>Item Distribution by Category</h5>
    <canvas id="categoryChart" width="300" height="300"></canvas>
</div>


    </div>

    <div class="text-center mt-4">
        <h5>Borrowing Trends</h5>
        <canvas id="trendChart" style="max-width:600px;"></canvas>
    </div>

    <div class="text-center mt-5">
        <button class="btn btn-outline-primary" id="launchScannerBtn">🔍 Launch QR Scanner</button>
    </div>
</div>



<div id="qrScannerModal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn" id="closeScannerBtn">&times;</span>
        <h2>Upload QR Code Image</h2>
        <input type="file" id="qrImageInput" accept="image/*" class="form-control mb-3">
        <div id="qrResult" class="alert alert-info" style="display: none;">
            <strong>Scanned QR Code:</strong> <span id="qrData"></span>
        </div>
        <canvas id="qrCanvas" style="display: none;"></canvas>
    </div>
</div>
<div id="overlay" class="overlay" style="display:none;"></div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<script>
// QR SCANNER FUNCTIONALITY
const launchBtn = document.getElementById('launchScannerBtn');
const scannerModal = document.getElementById('qrScannerModal');
const closeBtn = document.getElementById('closeScannerBtn');
const overlay = document.getElementById('overlay');
const input = document.getElementById('qrImageInput');
const canvas = document.getElementById('qrCanvas');
const ctx = canvas.getContext('2d');
const resultBox = document.getElementById('qrResult');
const qrDataText = document.getElementById('qrData');

launchBtn.onclick = () => {
    scannerModal.style.display = 'block';
    overlay.style.display = 'block';
}
closeBtn.onclick = () => {
    scannerModal.style.display = 'none';
    overlay.style.display = 'none';
    resultBox.style.display = 'none';
    qrDataText.textContent = '';
    input.value = '';
}

input.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);
            if (code) {
                qrDataText.textContent = code.data;
                resultBox.style.display = 'block';
                window.location.href = code.data;
            } else {
                alert('No QR code found in the image.');
            }
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
});
</script>

<script>
// Chart rendering (category distribution)
const categoryLabels = <?= json_encode($categoryLabels) ?>;
const categoryData = <?= json_encode($categoryCounts) ?>;

new Chart(document.getElementById('categoryChart'), {
    type: 'pie',
    data: {
        labels: categoryLabels,
        datasets: [{
            label: 'Items',
            data: categoryData,
            backgroundColor: ['#0dcaf0', '#ffc107', '#198754', '#6c757d', '#6610f2', '#fd7e14']
        }]
    }
});
</script>

<?php require_once('footer.php'); ?>

