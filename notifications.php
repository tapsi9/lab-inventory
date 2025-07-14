<?php
require_once 'db-connection.php';
require_once 'header.php';

// Get current date
$current_date = date('Y-m-d');

// Query to get borrowed items with due dates
$query = "SELECT b.*, i.item_name, i.item_code, u.firstname, u.lastname 
          FROM borrowings b 
          JOIN items i ON b.item_id = i.id 
          JOIN users u ON b.user_id = u.id 
          WHERE b.return_date IS NULL 
          ORDER BY b.due_date ASC";

$result = $conn->query($query);
?>

<div class="container mt-4">
    <h2 class="mb-4">Borrowed Items Notifications</h2>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Due Date Notifications</h5>
                </div>
                <div class="card-body">
                    <?php if ($result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Borrower</th>
                                        <th>Borrow Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): 
                                        $due_date = new DateTime($row['due_date']);
                                        $today = new DateTime();
                                        $status = '';
                                        $status_class = '';
                                        
                                        if ($due_date < $today) {
                                            $status = 'Overdue';
                                            $status_class = 'text-danger';
                                        } else {
                                            $diff = $today->diff($due_date);
                                            if ($diff->days <= 3) {
                                                $status = 'Due Soon';
                                                $status_class = 'text-warning';
                                            } else {
                                                $status = 'On Time';
                                                $status_class = 'text-success';
                                            }
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['item_code']); ?></td>
                                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($row['borrow_date'])); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($row['due_date'])); ?></td>
                                            <td class="<?php echo $status_class; ?>"><?php echo $status; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            No borrowed items found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    background-color: #f8f9fa;
}
.text-danger {
    font-weight: bold;
}
.text-warning {
    font-weight: bold;
}
.text-success {
    font-weight: bold;
}
</style>

<?php require_once 'footer.php'; ?> 