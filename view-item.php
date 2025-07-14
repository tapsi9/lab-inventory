<?php
require_once('header.php');

// Get parameters from URL
$code = isset($_GET['code']) ? $_GET['code'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';
$qty = isset($_GET['qty']) ? $_GET['qty'] : '';
$img = isset($_GET['img']) ? $_GET['img'] : '';

// Validate required parameters
if (empty($code) || empty($name)) {
    die('Invalid QR code data');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .item-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .item-image {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .item-details {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .item-title {
            color: #0d6efd;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="item-container">
            <?php if (!empty($img)): ?>
            <img src="<?php echo htmlspecialchars($img); ?>" alt="Item Image" class="item-image">
            <?php endif; ?>
            
            <div class="item-details">
                <h2 class="item-title"><?php echo htmlspecialchars($name); ?></h2>
                <p><strong>Item Code:</strong> <?php echo htmlspecialchars($code); ?></p>
                <?php if (!empty($qty)): ?>
                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($qty); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php require_once('footer.php'); ?> 