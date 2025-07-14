<?php require_once('header.php'); ?>
<?php
include('db-connection.php');


$sql = "SELECT * FROM `tbl_items`";


$result = mysqli_query($conn, $sql);


if ($result) {
    $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo 'Error: ' . mysqli_error($conn);
}
?>
<div class="container register">
    <h1 class="title-student-list">NURSING LABORATORIES</h1>
    <div class="row">
        <div class="col-md-12 register-right student-list">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row register-form student-list-table-cont">
                        <div class="col-md-12">
                            <table class="table table-striped table-hover table-bordered">
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>QR Code</th>
                                    <th>Action</th>
                                </tr>


                                <?php $ctr = 1; ?>
                                <?php foreach ($students as $student) : ?>
                                    <tr>
                                        <td><?= $ctr++; ?></td>
                                        <td>
                                            <a href="#">
                                                <img id="selectedAvatar" src="<?= BASE_URL . $student['photo']; ?>" style="width: 150px; height: 150px; object-fit: cover;" />
                                            </a>
                                        </td>
                                        <td><?= $student['name'];?></td>
                                        <td><?= $student['quantity']; ?></td>
                                        <td><img id="selectedAvatar" src="<?= BASE_URL . $student['qr_code']; ?>" style="width: 150px; height: 150px; object-fit: cover;" /></td>
                                        <td>
                                            <a href="edit-items.php?id=<?php echo $student['id']; ?>" class="btn btn-success btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                            <a href="process/delete-items-process.php?id=<?php echo $student['id']; ?>" onclick="return confirm('Delete selected student <?php echo $student['name'] ?> <?php echo $student['quantity'] ?>?')" class="btn btn-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
