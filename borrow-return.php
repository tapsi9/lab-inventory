<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('header.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

include('db-connection.php');
require_once('send-email.php');

$user_id = $_SESSION['id'];
$user_course = $_SESSION['user_course'];
$alert_msg = '';

// BORROW ITEM
if (isset($_POST['borrow_submit'])) {
    $item_id = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity']);
    $return_date = $_POST['return_date'];

    $check_sql = "SELECT quantity FROM tbl_items WHERE id = ? AND course = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("is", $item_id, $user_course);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item && $item['quantity'] >= $quantity) {
        $insert_stmt = $conn->prepare("INSERT INTO tbl_borrowed_items (user_id, item_id, quantity, return_date) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("iiis", $user_id, $item_id, $quantity, $return_date);

        if ($insert_stmt->execute()) {
            $update = $conn->prepare("UPDATE tbl_items SET quantity = quantity - ? WHERE id = ?");
            $update->bind_param("ii", $quantity, $item_id);
            $update->execute();

            $item_stmt = $conn->prepare("SELECT name FROM tbl_items WHERE id = ?");
            $item_stmt->bind_param("i", $item_id);
            $item_stmt->execute();
            $item_result = $item_stmt->get_result();
            $item_data = $item_result->fetch_assoc();
            $item_name = $item_data['name'] ?? 'Unknown';
            $item_stmt->close();

            $admin_sql = "SELECT user_email FROM tbl_users WHERE role = 'admin'";
            $admin_result = $conn->query($admin_sql);

            if ($admin_result && $admin_result->num_rows > 0) {
                while ($admin = $admin_result->fetch_assoc()) {
                    $to = $admin['user_email'];
                    $subject = "New Borrow Request - Pending Approval";
                    $message = "
                        <html>
                        <head><title>Borrow Request</title></head>
                        <body>
                            <h3>New Borrow Request Submitted</h3>
                            <p><strong>User:</strong> {$_SESSION['full_name']} ({$_SESSION['user_name']})</p>
                            <p><strong>Course:</strong> {$_SESSION['user_course']}</p>
                            <p><strong>Item:</strong> [$item_id] $item_name</p>
                            <p><strong>Quantity:</strong> $quantity</p>
                            <p><strong>Expected Return Date:</strong> $return_date</p>
                            <p><strong>Status:</strong> Pending</p>
                        </body>
                        </html>
                    ";

                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                    $headers .= "From: inventory-system@yourdomain.com\r\n";

                    sendEmailToAdmins($conn, $subject, $message, $_SESSION['user_course']);
                }
            }

            $alert_msg = '<div class="alert alert-success">Item borrowed successfully! Admin has been notified.</div>';
        } else {
            $alert_msg = '<div class="alert alert-danger">Failed to borrow item.</div>';
        }
    } else {
        $alert_msg = '<div class="alert alert-warning">Not enough stock available.</div>';
    }
}

// RETURN ITEM — Admin only
if (isset($_POST['return_submit'])) {
    $borrow_id = intval($_POST['borrow_id']);

    if ($_SESSION['role'] === 'admin') {
        $stmt = $conn->prepare("SELECT item_id, quantity FROM tbl_borrowed_items WHERE id = ?");
        $stmt->bind_param("i", $borrow_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $borrowed = $res->fetch_assoc();

        if ($borrowed) {
            $update_stmt = $conn->prepare("UPDATE tbl_items SET quantity = quantity + ? WHERE id = ?");
            $update_stmt->bind_param("ii", $borrowed['quantity'], $borrowed['item_id']);
            $update_stmt->execute();

            $delete_stmt = $conn->prepare("DELETE FROM tbl_borrowed_items WHERE id = ?");
            $delete_stmt->bind_param("i", $borrow_id);

            if ($delete_stmt->execute()) {
                $alert_msg = '<div class="alert alert-success">Item returned and record removed!</div>';
            } else {
                $alert_msg = '<div class="alert alert-danger">Failed to delete borrow record.</div>';
            }
        } else {
            $alert_msg = '<div class="alert alert-danger">Invalid return request.</div>';
        }
    } else {
        $alert_msg = '<div class="alert alert-danger">You are not authorized to return items.</div>';
    }
}

// UPDATE STATUS
if (isset($_POST['update_status'])) {
    $borrow_id = intval($_POST['status_borrow_id']);
    $new_status = $_POST['new_status'];

    if ($_SESSION['role'] === 'admin') {
        $stmt = $conn->prepare("UPDATE tbl_borrowed_items SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $borrow_id);
    } else {
        $stmt = $conn->prepare("UPDATE tbl_borrowed_items SET status = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $new_status, $borrow_id, $user_id);
    }

    if ($stmt->execute()) {
        $alert_msg = '<div class="alert alert-info">Status updated successfully.</div>';
    } else {
        $alert_msg = '<div class="alert alert-danger">Failed to update status.</div>';
    }
}

// FETCH ITEMS
$items_stmt = $conn->prepare("SELECT * FROM tbl_items WHERE course = ?");
$items_stmt->bind_param("s", $user_course);
$items_stmt->execute();
$items = $items_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// FETCH borrowed items
if ($_SESSION['role'] === 'admin') {
    $borrowed_stmt = $conn->prepare("SELECT bi.*, i.name, u.full_name, u.student_id FROM tbl_borrowed_items bi
    JOIN tbl_items i ON bi.item_id = i.id
    JOIN tbl_users u ON bi.user_id = u.id
    WHERE i.course = ?
    ORDER BY bi.borrow_date DESC");

    $borrowed_stmt->bind_param("s", $user_course);
} else {
    $borrowed_stmt = $conn->prepare("SELECT bi.*, i.name FROM tbl_borrowed_items bi
        JOIN tbl_items i ON bi.item_id = i.id
        WHERE bi.user_id = ?
        ORDER BY bi.borrow_date DESC");
    $borrowed_stmt->bind_param("i", $user_id);
}
$borrowed_stmt->execute();
$borrowed_result = $borrowed_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Borrow / Return Items</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Borrow/Return Items for <?= htmlspecialchars($user_course) ?></h2>
    <?= $alert_msg ?>

    <form method="POST" class="mb-5">
        <div class="mb-3">
            <label for="item_id" class="form-label">Select Item</label>
            <?php
$selected_item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : null;
?>
<select name="item_id" class="form-control" required>
    <option value="">-- Select Item --</option>
    <?php foreach ($items as $item): ?>
        <option value="<?= $item['id'] ?>" <?= ($item['id'] === $selected_item_id) ? 'selected' : '' ?>>
            <?= htmlspecialchars($item['name']) ?> (Available: <?= $item['quantity'] ?>)
        </option>
    <?php endforeach; ?>
</select>

        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" min="1" name="quantity" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="return_date" class="form-label">Expected Return Date</label>
            <input type="date" name="return_date" class="form-control" required>
        </div>
        <button type="submit" name="borrow_submit" class="btn btn-primary">Borrow</button>
    </form>

    <h4>Your Borrowed Items</h4>
    <table class="table table-bordered">
        <thead>
<tr>
    <?php if ($_SESSION['role'] === 'admin'): ?>
    <th>Borrower</th>
    <th>Student ID</th>
<?php endif; ?>

    <th>Item</th>
    <th>Quantity</th>
    <th>Borrow Date</th>
    <th>Return Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

        <tbody>
        <?php while ($row = $borrowed_result->fetch_assoc()) : ?>
            <tr
<?php
    $isOverdue = strtotime($row['return_date']) < time() && strtolower($row['status']) === 'borrowed';
    if ($isOverdue) echo ' class="table-danger"';
?>
>
    <?php if ($_SESSION['role'] === 'admin'): ?>
    <td><?= htmlspecialchars($row['full_name']) ?></td>
    <td><?= htmlspecialchars($row['student_id']) ?></td>
<?php endif; ?>

    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= $row['quantity'] ?></td>
    <td><?= date('M d, Y', strtotime($row['borrow_date'])) ?></td>
    <td>
        <?= date('M d, Y', strtotime($row['return_date'])) ?>
        <?php if ($isOverdue): ?>
            <span class="badge bg-danger ms-2">Overdue</span>
        <?php endif; ?>
    </td>

                <td>
                    <?php if ($_SESSION['role'] === 'admin') : ?>
                        <form method="POST" style="display: flex; gap: 5px;">
                            <input type="hidden" name="status_borrow_id" value="<?= $row['id'] ?>">
                            <select name="new_status" class="form-select form-select-sm">
                                <?php 
                                    $statuses = ['Pending', 'Available', 'Unavailable', 'Borrowed'];
                                    foreach ($statuses as $status) {
                                        $selected = (strtolower($row['status']) === strtolower($status)) ? 'selected' : '';
                                        echo "<option value='$status' $selected>$status</option>";
                                    }
                                ?>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-secondary">Update</button>
                        </form>
                    <?php else : ?>
                        <?php
                            $status = strtolower($row['status']);
                            $badge_class = match($status) {
                                'pending' => 'bg-warning',
                                'available' => 'bg-success',
                                'borrowed' => 'bg-success',
                                'unavailable' => 'bg-danger',
                                'returned' => 'bg-secondary',
                                default => 'bg-light text-dark'
                            };
                        ?>
                        <span class="badge <?= $badge_class ?>"><?= ucfirst($row['status']) ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($_SESSION['role'] === 'admin') : ?>
                        <form method="POST" onsubmit="return confirm('Return this item?');">
                            <input type="hidden" name="borrow_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="return_submit" class="btn btn-success btn-sm">Return</button>
                        </form>
                    <?php else : ?>
                        <span class="text-muted">Awaiting admin confirmation</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php if ($borrowed_result->num_rows == 0): ?>
            <tr><td colspan="6" class="text-center">No borrowed items</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
