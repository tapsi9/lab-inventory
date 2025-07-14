<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// ✅ 1. Generic Email Function for single recipients (like forgot-password)
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'brianneilbaybay.villaviza@olivarezcollegetagaytay.edu.ph';
        $mail->Password   = 'gesz pkrs qikc qrvq'; // Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->SMTPDebug  = 0;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            ]
        ];

        $mail->setFrom('brianneilbaybay.villaviza@olivarezcollegetagaytay.edu.ph', 'Inventory System');

        if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            return $mail->send(); // returns true on success, false on failure
        } else {
            error_log("Invalid email address: $to");
            return false;
        }

    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

// ✅ 2. Send Email to All Admins in a Specific Course
function sendEmailToAdmins($conn, $subject, $body, $course) {
    $stmt = $conn->prepare("SELECT user_email FROM tbl_users WHERE role = 'admin' AND user_course = ?");
    $stmt->bind_param("s", $course);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        sendEmail($row['user_email'], $subject, $body);
    }
}
