<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $status = 'Accepted';

    // Update the reservation status in the database
    include_once("../function/dbconnect.php");
    $conn = dbConnect();
    
    $stmt = $conn->prepare("UPDATE reservation SET status = ? WHERE transaction_id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    
    $stmt->close();
    $conn->close();

    echo "success"; // Return a success response
} else {
    echo "error"; // Return an error response
}
?>