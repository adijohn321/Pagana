<?php
function dbConnect()
{
    // Modify these variables with your database credentials
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "paganadb";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
$conn = dbConnect();
$users = $conn->prepare("SELECT COUNT(*) as totalUsers FROM `users` where user_level = 'Guest'");
$users->execute();
$result = $users->get_result();
$row = $result->fetch_assoc();
$userCount = $row['totalUsers'];

$room = $conn->prepare("SELECT COUNT(*) as totalRooms FROM `room`");
$room->execute();
$result = $room->get_result();
$row = $result->fetch_assoc();
$roomCount = $row['totalRooms'];

$reservation = $conn->prepare("SELECT COUNT(*) as totalReservation FROM `reservation`");
$reservation->execute();
$result = $reservation->get_result();
$row = $result->fetch_assoc();
$reservationCount = $row['totalReservation'];
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "InCharge") {
  
    $id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$id'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
  
    $firstName = $row['fname'];
    $middleName = $row['mname'];
    $lastName = $row['lname'];
    $fullname = $row['fname'] ." " .$row['mname'] ." ".$row['lname'];;
    $Email = $row['email'];
    $Phone = $row['phone'];
  }

?>