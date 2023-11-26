<?php
// verify.php
include_once "function/dbconnect.php";
// Retrieve the verification token from the URL
$verificationToken = $_GET['token'];
$conn = dbConnect();
// Update the status to 1 in the database
$stmt = $conn->prepare("UPDATE users SET status = 1 WHERE verification_token = ?");
$stmt->bind_param("s", $verificationToken);

if ($stmt->execute()) {
    // Status updated successfully
    echo "Email verification successful. Your account is now active.";
    header("location: index.php");
} else {
    // Failed to update the status
    echo "Email verification failed. Please try again.";
}

$stmt->close();
$conn->close();
?>
