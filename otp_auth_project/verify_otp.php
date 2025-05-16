<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "user_auth");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$otp = $_POST['otp'];

// Verify OTP
$query = "SELECT * FROM otp_users WHERE email = ? AND otp = ? AND TIMESTAMPDIFF(MINUTE, timestamp, NOW()) <= 5";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $email, $otp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "OTP verified successfully!";
} else {
    echo "Invalid or expired OTP.";
}

$stmt->close();
$conn->close();
?>
