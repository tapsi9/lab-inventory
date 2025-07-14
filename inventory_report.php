<?php
require_once('config.php');
session_start();

// Ensure only admins can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: user-dashboard.php');
    exit;
}

require_once('header.php');
require_once('db-connection.php');

$user_course = $_SESSION['user_course']; // Admin's course

// Fetch items for this admin’s course
$stmt = $conn->prepare("SELECT id, photo, name, quantity FROM tbl_items WHERE course = ? ORDER BY id DESC");
$stmt->bind_param("s", $user_course);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><?= htmlspecialchars($user_course) ?> Inventory Report</h2>
        <button id="exportPDF" class="btn btn-danger">🖨 Export as PDF</button>
    </div>

    <div id="reportContent">
        <table class="table table-striped table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $ctr = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $ctr++ ?></td>
                        <td>
                            <img src="<?= BASE_URL . $row['photo']; ?>" alt="Item Photo" style="width: 80px; height: 80px; object-fit: cover;">
                        </td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= (int)$row['quantity'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No items found for <?= htmlspecialchars($user_course) ?>.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- PDF Export Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.getElementById("exportPDF").addEventListener("click", function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');
    const report = document.getElementById("reportContent");

    html2canvas(report).then(canvas => {
        const imgData = canvas.toDataURL("image/png");
        const imgProps = doc.getImageProperties(imgData);
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        doc.save("<?= htmlspecialchars($user_course) ?>_inventory_report.pdf");
    });
});
</script>

<?php
$conn->close();
require_once('footer.php');
?>
