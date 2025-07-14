<?php
require_once('header.php');
include('db-connection.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/phpmary/'); // Change if needed
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="alert alert-danger">Invalid item selected.</div>';
    require_once('footer.php');
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM tbl_items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="alert alert-warning">Item not found.</div>';
    require_once('footer.php');
    exit;
}

$item = $result->fetch_assoc();
?>

<div class="container mt-4">
    <h2>Edit Item - <?= htmlspecialchars($item['name']) ?></h2>
    <form action="process/edit-items-process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <input type="hidden" name="current_image" value="<?= $item['photo'] ?>">

        <div class="mb-3">
            <label for="name" class="form-label">Item Name</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($item['name']) ?>">
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" required value="<?= $item['quantity'] ?>">
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" class="form-control" required value="<?= htmlspecialchars($item['category']) ?>">
        </div>

        <div class="mb-3">
            <label for="course" class="form-label">Course</label>
            <select name="course" class="form-select" required>
                <?php
                $courses = ['BSIT', 'BSN', 'BSBA', 'BSA', 'BSIS', 'BSTM', 'BSHRM', 'BSCRIM'];
                foreach ($courses as $course) {
                    $selected = $item['course'] === $course ? 'selected' : '';
                    echo "<option value=\"$course\" $selected>$course</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
    <label class="form-label">Current Photo</label><br>
    <img src="<?= htmlspecialchars(BASE_URL . $item['photo']) ?>" alt="Current Photo"
         width="150" height="150" style="object-fit: cover; border-radius: 10px; border: 1px solid #ccc;">
</div>

<div class="mb-3">
    <label for="photo" class="form-label">Change Photo (optional)</label>
    <input type="file" name="photo" class="form-control" accept="image/*">
</div>


        <div class="d-grid gap-2">
            <button type="submit" name="edit_items" class="btn btn-primary">Update Item</button>
            <a href="items-list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once('footer.php'); ?>
