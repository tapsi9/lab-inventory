<?php
require_once('header.php');
require_once('db-connection.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];

// Recent borrowed items
$sql = "SELECT bi.borrow_date, i.name, bi.quantity, bi.status 
        FROM tbl_borrowed_items bi 
        JOIN tbl_items i ON bi.item_id = i.id 
        WHERE bi.user_id = ? 
        ORDER BY bi.borrow_date DESC 
        LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$borrowed_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    #qrScannerModal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
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
        position: relative;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        cursor: pointer;
        font-size: 1.5rem;
    }
    </style>
</head>
<body>
<div class="container mt-5 d-flex justify-content-center">
    <div class="col-lg-10 text-center">
        <img src="olivarez-college-tagaytay-logo.png" alt="Olivarez College Tagaytay Logo" style="max-width: 200px; height: auto;" class="mb-4">

        <h1 class="display-5">Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h1>
        <p class="lead">You're now logged in as a <strong><?= htmlspecialchars($_SESSION['user_course']) ?></strong> student.</p>

        <div class="mt-4">
            <a href="borrow-return.php" class="btn btn-success btn-lg">Borrow or Return Items</a>
            <a href="items-list.php" class="btn btn-outline-primary btn-lg ms-2">View Available Items</a>
            <button class="btn btn-outline-primary btn-lg ms-2" id="launchScannerBtn">🔍 Launch QR Scanner</button>
        </div>

        <hr>

        <div class="mb-4">
            <h5>Your Borrowed Items</h5>
            <table class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Borrowed On</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
    <?php if (count($borrowed_items) > 0): ?>
        <?php foreach ($borrowed_items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= date("M d, Y", strtotime($item['borrow_date'])) ?></td>
                <td>
                    <?php
                    $status = strtolower($item['status']);
                    $badge_class = match($status) {
                        'pending' => 'bg-warning',
                        'available', 'borrowed' => 'bg-success',
                        'unavailable' => 'bg-danger',
                        'returned' => 'bg-secondary',
                        default => 'bg-light text-dark'
                    };
                    ?>
                    <span class="badge <?= $badge_class ?>">
                        <?= ucfirst($item['status']) ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="4" class="text-center text-muted">You haven't borrowed anything yet.</td></tr>
    <?php endif; ?>
</tbody>

            </table>
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

<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<script>
const launchBtn = document.getElementById('launchScannerBtn');
const scannerModal = document.getElementById('qrScannerModal');
const closeBtn = document.getElementById('closeScannerBtn');
const input = document.getElementById('qrImageInput');
const canvas = document.getElementById('qrCanvas');
const ctx = canvas.getContext('2d');
const resultBox = document.getElementById('qrResult');
const qrDataText = document.getElementById('qrData');

launchBtn.onclick = () => {
    scannerModal.style.display = 'flex';
}
closeBtn.onclick = () => {
    scannerModal.style.display = 'none';
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
            canvas.hidden = false;
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
            canvas.hidden = true;
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
});
</script>
<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
