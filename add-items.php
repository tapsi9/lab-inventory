<?php require_once('header.php'); ?>
<?php require_once('db-connection.php'); ?>

<?php

// Display success/error messages
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>' . $_SESSION['success_message'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>' . $_SESSION['error_message'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['error_message']);
}
?>

<div class="container register">
    <div class="row">
        <div class="col-md-3 register-left">
            <h3>Add New Item</h3>
            <p>Laboratory Inventory Management System for Olivarez College Tagaytay Using QR Code</p>
        </div>
        <div class="col-md-9 register-right">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane show active" id="home" role="tabpanel">
                    <form action="process/add-items-process.php" method="POST" enctype="multipart/form-data">
                        <div class="row register-form">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="item_name">Item Name:</label>
                                            <input type="text" class="form-control" id="item_name" name="name" placeholder="Item name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantity">Quantity:</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
    <label for="category" class="form-label">Category</label>
    <select name="category" class="form-control" required>
        <option value="">-- Select Category --</option>
        <option value="Chemicals">Chemicals</option>
        <option value="Tools">Tools</option>
        <option value="Electronics">Electronics</option>
        <option value="Others">Others</option>
    </select>
</div>



                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-center mb-4">
                                            <img id="selectedAvatar" src="https://mdbootstrap.com/img/Photos/Others/placeholder-avatar.jpg"
                                                 class="rectangle" style="width: 200px; height: 200px; object-fit: cover;" alt="Item Image" />
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <div class="btn btn-primary btn-rounded">
                                                <label class="form-label text-white m-1" for="customFile2">Choose Item Picture</label>
                                                <input type="file" class="form-control d-none" id="customFile2" accept="image/*" onchange="validateAndDisplayImage(event, 'selectedAvatar')" name="Photo" required>
                                            </div>
                                        </div>
                                        <div class="text-center mt-2">
                                            <small class="text-muted">Allowed formats: JPG, JPEG, PNG, GIF (Max size: 10MB)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-center mb-4">
                                            <div id="qrcode" style="padding: 15px; background: white;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="d-grid gap-2">
                                            <button name="add_items" type="submit" class="btn btn-block btn-primary">SUBMIT</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-grid gap-2">
                                            <button type="reset" class="btn btn-block btn-outline-dark" onclick="clearQRCode()">RESET</button>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add QR Code preview library -->
<script src="js/qrcode.min.js"></script>
<script>
    let qrcode = null;

    function generateQRCode() {
        const itemName = document.getElementById('item_name').value.trim();
        const quantity = document.getElementById('quantity').value || '';

        if (itemName === '') {
            clearQRCode();
            return;
        }

        const qrText = `ITEM:${itemName}|QTY:${quantity}`;
        if (qrcode) qrcode.clear();
        document.getElementById('qrcode').innerHTML = '';

        qrcode = new QRCode(document.getElementById("qrcode"), {
            text: qrText,
            width: 300,
            height: 300,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    function displaySelectedImage(event, elementId) {
        const fileInput = event.target;
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(elementId).src = e.target.result;
                generateQRCode();
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }

    function validateAndDisplayImage(event, elementId) {
        const file = event.target.files[0];
        const maxSize = 10 * 1024 * 1024;
        if (file) {
            if (file.size > maxSize) {
                alert('File size exceeds 10MB limit.');
                event.target.value = '';
                return;
            }
            if (!file.type.startsWith('image/')) {
                alert('Invalid file type.');
                event.target.value = '';
                return;
            }
            displaySelectedImage(event, elementId);
        }
    }

    function clearQRCode() {
        if (qrcode) qrcode.clear();
        document.getElementById('qrcode').innerHTML = '';
        document.getElementById('selectedAvatar').src = 'https://mdbootstrap.com/img/Photos/Others/placeholder-avatar.jpg';
    }

    // Trigger QR generation on input
    document.getElementById('item_name').addEventListener('input', generateQRCode);
    document.getElementById('quantity').addEventListener('input', generateQRCode);
</script>

<?php require_once('footer.php'); ?>
