<?php
require_once '../config.php';

if (isset($_SESSION['name']) && isset($_SESSION['email']) && isset($_SESSION['address'])) {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $phone = $_SESSION['phone'];
    $address = $_SESSION['address'];
    $room = $_SESSION['room'];
    $rate = $_SESSION['rate'];
    $checkin = $_SESSION['checkin'];
    $checkout = $_SESSION['checkout'];
    $am1 = $_SESSION['am1'];
    $am2 = $_SESSION['am2'];
    $user_id = $_SESSION['user_id'];
    $ref_no = rand(999999999, 000000000);
    $status = "Accepted";
    date_default_timezone_set('Asia/Manila');
    $now = date("Y-m-d H:i:s");
    
    $stmt = mysqli_query($conn, "INSERT INTO `reservation` (user_id, room_id, name, email, phone, address, checkin, checkout, amount_paid, total_rate, transaction_id, status, datecreated) VALUES('$user_id', '$room', '$name', '$email', '$phone', '$address', '$checkin', '$checkout', '$rate', '$rate', '$ref_no', '$status', '$now')");
    $reservationId = mysqli_insert_id($conn);
    if($am1 == false){
    }
    else if ($am1 == true){
        $stmt1 = mysqli_query($conn, "INSERT INTO `amenities_reservation` (reservation_id, name, checkin, checkout) VALUES('$reservationId', '$am1', '$checkin', '$checkout')");
    }
    if($am2 == false){
    }
    else if ($am2 == true){
    $stmt2 = mysqli_query($conn, "INSERT INTO `amenities_reservation` (reservation_id, name, checkin, checkout) VALUES('$reservationId', '$am2', '$checkin', '$checkout')");
    }
    if ($stmt) {
        $logoImagePath = '../img/Pagana_logo.png';
        $logoData = base64_encode(file_get_contents($logoImagePath));
        $logoSrc = 'data:image/png;base64,' . $logoData;

        $subject = "Your Reservation";
        $message = '<html>
        <head>
        <title>Your Reservation</title>
        <style>
            body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            }
            .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            }
            .logo {
            text-align: center;
            margin-bottom: 20px;
            }
            .logo img {
            max-width: 200px;
            }
            h1 {
            color: #333333;
            }
            ul {
            list-style-type: none;
            padding: 0;
            }
            li {
            margin-bottom: 10px;
            }
        </style>
        </head>
        <body>
        <div class="container">
            <div class="logo">
            <img alt="Pagana Kutawato">
            </div>
            <h1>Your Reservation</h1>
            <p>Your Reservation has been accepted. Thank you for choosing our hotel.</p>
            <p>Here are your reservation details:</p>
            <ul>
            <li>Transaction ID: ' . $ref_no . '</li>
            <li>Name: ' . $name . '</li>
            <li>Email: ' . $email . '</li>
            <li>Check-in Date: ' . $checkin . '</li>
            <li>Check-out Date: ' . $checkout . '</li>
            <!-- Add more reservation details as needed -->
            </ul>
            <p>We look forward to seeing you soon.</p>
        </div>
        </body>
        </html>';

        // Set the content-type header for the email as HTML
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // Add the sender information
        $headers .= "From: Pagana Kutawato" . "\r\n";

        // Send the email
        mail($email, $subject, $message, $headers);

        // Redirect the user to the homepage
        header("Location: ../index.php");

        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
