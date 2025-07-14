<?php
session_start();
require_once('config.php');
include('db-connection.php');

$user_course = $_SESSION['user_course'];
$role = $_SESSION['role'];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $sql = "SELECT * FROM tbl_items WHERE course = '$user_course' AND (name LIKE '%$search_escaped%' OR quantity LIKE '%$search_escaped%')";
} else {
    $sql = "SELECT * FROM tbl_items WHERE course = '$user_course'";
}

$result = mysqli_query($conn, $sql);
$items = [];
if ($result) {
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
$ctr = 1;
if (!empty($items)) {
    foreach ($items as $item) {
        echo '<tr>';
        echo '<td>' . ($ctr++) . '</td>';
        echo '<td><img src="' . BASE_URL . $item['photo'] . '" style="width: 150px; height: 150px; object-fit: cover;" /></td>';
        echo '<td>' . htmlspecialchars($item['name']) . '</td>';
        echo '<td>' . htmlspecialchars($item['quantity']) . '</td>';
        echo '<td><img src="' . BASE_URL . $item['qr_code'] . '" style="width: 200px; height: 200px; object-fit: contain;" /></td>';
        if ($role === 'admin') {
            echo '<td>';
            echo '<a href="edit-items.php?id=' . $item['id'] . '" class="btn btn-success btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a> ';
            echo '<a href="process/delete-items-process.php?id=' . $item['id'] . '" onclick="return confirm(\'Delete selected item ' . htmlspecialchars($item['name']) . ' x' . htmlspecialchars($item['quantity']) . '?\')" class="btn btn-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></a>';
            echo '</td>';
        }
        echo '</tr>';
    }
} else {
    $colspan = ($role === 'admin') ? 6 : 5;
    echo '<tr><td colspan="' . $colspan . '" class="text-center">No items found.</td></tr>';
} 