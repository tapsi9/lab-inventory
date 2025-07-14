<?php
header('Content-Type: application/json');

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['image_url'])) {
    echo json_encode(['success' => false, 'error' => 'No image URL provided']);
    exit;
}

$image_url = $data['image_url'];
$qr_code_dir = 'qr_codes';

// Create QR codes directory if it doesn't exist
if (!file_exists($qr_code_dir)) {
    mkdir($qr_code_dir, 0777, true);
}

// Generate unique filename for the QR code
$qr_code_file = $qr_code_dir . '/qr_' . uniqid() . '.png';

// Generate QR code using the Python script
$python_script = 'python generate_qr.py "' . escapeshellarg($image_url) . '" "' . escapeshellarg($qr_code_file) . '"';
exec($python_script, $output, $return_var);

if ($return_var === 0) {
    echo json_encode([
        'success' => true,
        'qr_code_url' => $qr_code_file
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to generate QR code'
    ]);
} 