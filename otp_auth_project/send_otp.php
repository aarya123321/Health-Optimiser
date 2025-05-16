<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "user_auth");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Generate random 6-digit OTP
$otp = rand(100000, 999999);
$email = $_POST['email'];

// Insert OTP into database
$stmt = $conn->prepare("INSERT INTO otp_users (email, otp) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $otp);
$stmt->execute();

// Send OTP using Gmail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';  // Add PHPMailer via Composer

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_gmail@gmail.com';
    $mail->Password = 'your_app_specific_password';  // Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your_gmail@gmail.com', 'OTP Auth Service');
    $mail->addAddress($email);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "Your OTP code is $otp. It is valid for 5 minutes.";
    $mail->send();
    echo "OTP sent successfully!";
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}

$stmt->close();
$conn->close();
?>
