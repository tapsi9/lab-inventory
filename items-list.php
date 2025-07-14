<?php
session_start();
require_once('config.php');
require_once('header.php'); // Always load this early to initialize DB based on course
include('db-connection.php'); // Optional if already handled in header.php

$user_course = $_SESSION['user_course']; // 'BSIT', 'BSN', etc.

$course_names = [
    'BSIT' => 'INFORMATION TECHNOLOGY COMPUTER LABORATORIES',
    'BSN' => 'NURSING EQUIPMENT ROOM',
    'BSBA' => 'BUSINESS ADMIN LAB',
    // Add more as needed
];

// Remove PHP search logic here; handled by AJAX endpoint
$sql = "SELECT * FROM tbl_items WHERE course = '$user_course'";
$result = mysqli_query($conn, $sql);
$items = [];
if ($result) {
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo 'Error: ' . mysqli_error($conn);
}
?>

<div class="container register">
    <h1 class="title-student-list">
        <?= $course_names[$user_course] ?? 'COURSE INVENTORY LIST' ?>
    </h1>

    <div class="row">
        <div class="col-md-12 register-right student-list">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row register-form student-list-table-cont">
                        <div class="col-md-12">
                            <form id="search-form" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" id="search-input" class="form-control" placeholder="Search items...">
                                    <button class="btn btn-secondary" type="submit">Search</button>
                                </div>
                            </form>
                            <table class="table table-striped table-hover table-bordered">
                                <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>QR Code</th>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                                <tbody id="items-table-body">
                                <?php $ctr = 1; ?>
                                <?php foreach ($items as $item): ?>
<tr>
    <td><?= $ctr++; ?></td>
    <td>
        <img src="<?= BASE_URL . $item['photo']; ?>" style="width: 150px; height: 150px; object-fit: cover;" />
    </td>
    <td><?= htmlspecialchars($item['name']); ?></td>
    <td><?= htmlspecialchars($item['quantity']); ?></td>
    <td>
        <img src="<?= BASE_URL . $item['qr_code']; ?>" style="width: 200px; height: 200px; object-fit: contain;" />
    </td>
    <?php if ($_SESSION['role'] === 'admin'): ?>
    <td>
        <a href="edit-items.php?id=<?= $item['id']; ?>" class="btn btn-success btn-sm" title="Edit">
            <i class="bi bi-pencil-square"></i>
        </a>
        <a href="process/delete-items-process.php?id=<?= $item['id']; ?>"
           onclick="return confirm('Delete selected item <?= $item['name'] ?> x<?= $item['quantity'] ?>?')"
           class="btn btn-danger btn-sm" title="Delete">
            <i class="bi bi-trash"></i>
        </a>
    </td>
    <?php endif; ?>
</tr>
<?php endforeach; ?>
<?php if (empty($items)): ?>
    <tr>
        <td colspan="<?= $_SESSION['role'] === 'admin' ? 6 : 5 ?>" class="text-center">No items found.</td>
    </tr>
<?php endif; ?>
                                </tbody>
                            </table>
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script>
                            $(document).ready(function() {
                                $('#search-form').on('submit', function(e) {
                                    e.preventDefault();
                                    var search = $('#search-input').val();
                                    $.ajax({
                                        url: 'get-items-ajax.php',
                                        method: 'GET',
                                        data: { search: search },
                                        success: function(response) {
                                            $('#items-table-body').html(response);
                                        }
                                    });
                                });
                                // Optional: live search as you type
                                $('#search-input').on('input', function() {
                                    $('#search-form').submit();
                                });
                            });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

