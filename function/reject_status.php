<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $status = 'Rejected';

    // Update the reservation status in the database
    include_once("../function/dbconnect.php");
    $conn = dbConnect();
    
    $date = '0000-00-00';
    $stmt = $conn->prepare("UPDATE reservation SET  checkin = ?, checkout = ?, status = ? WHERE transaction_id = ?");
    $stmt->bind_param("sssi", $date, $date, $status, $id);
    $stmt->execute();
    
    $stmt->close();
    $conn->close();

    echo "success"; // Return a success response
} else {
    echo "error"; // Return an error response
}
?>
