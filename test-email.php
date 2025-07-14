<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'brianneilbaybay.villaviza@olivarezcollegetagaytay.edu.ph';
    $mail->Password   = 'gesz pkrs qikc qrvq';  // App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('brianneilbaybay.villaviza@olivarezcollegetagaytay.edu.ph', 'Inventory Test');
    $mail->addAddress('your-real-gmail@gmail.com'); // Replace with your actual test email

    $mail->isHTML(true);
    $mail->Subject = 'Test Email from Inventory System';
    $mail->Body    = 'This is a test email.';

    $mail->send();
    echo 'Test email sent successfully.';
} catch (Exception $e) {
    echo 'Test email failed. Mailer Error: ' . $mail->ErrorInfo;
}
