<?php
session_start();

$year = date("Y");
$user_id = $_SESSION['user_id'];
if (isset($_GET["id"])) {
  $roomId = $_GET["id"];
} else {
  $roomId = "";
}
if (isset($_GET["reservation_id"])) {
  $reservation = $_GET["reservation_id"];
} else {
  $reservation = "";
}

include_once("function/dbconnect.php");
$conn = dbConnect();

$stmt = $conn->prepare("SELECT * FROM room WHERE id = '$roomId'");
$stmt->execute();
$result = $stmt->get_result();

$sn = 0;
if ($row = $result->fetch_assoc()) {
  $sn++;

  $roomId = $row['id'];
  $roomImage = $row['image'];
  $roomName = $row['name'];
  $roomType = $row['type'];
  $roomDescription = $row['description'];
  $roomRate = $row['rate'];
}
$stmt->close();
$stmt = $conn->prepare("SELECT * FROM users WHERE id = '$user_id'");
$stmt->execute();
$result = $stmt->get_result();

$sn = 0;
if ($row = $result->fetch_assoc()) {
  $sn++;

  $fullname = $row["fname"] . ' ' . $row["mname"] . ' ' . $row["lname"];
  $email = $row['email'];
  $number = $row['phone'];
}
$stmt->close();


if (isset($_POST['walkin'])) {
    $dateS = new DateTime($_POST['date1']);
    $dateE = new DateTime($_POST['date2']);
    if ($dateS < ($dateE)) {

        //proceed save to database
        $rate = ($dateS->diff($dateE)->format('%a')) * $roomRate;
        $email = $_POST['email'];
        $number = $_POST['number'];
        $dateE = $dateE->format('y-m-d');
        $dateS = $dateS->format('y-m-d');
        $ref_no = rand(999999999, 000000000);

        $stmt = mysqli_query($conn, "INSERT INTO `reservation` (user_id,room_id, name, email, phone, address, checkin, checkout, amount_paid, total_rate, transaction_id, status, datecreated)
         VALUES('$user_id','$roomName', '$fullname', '$email', '$number', '', '$dateS', '$dateE', '0.00', '$rate', '$ref_no', 'Pending', current_date())");

        if ($stmt) {
            $logoImagePath = 'img/Pagana_logo.png';
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
                  <h1>Pagana Kutawato</h1>
                  </div>
                  <h1>Your Reservation</h1>
                  <p>Your Reservation has been accepted. Thank you for choosing our hotel.</p>
                  <p>Here are your reservation details:</p>
                  <ul>
                  <li>Transaction ID: ' . $ref_no . '</li>
                  <li>Name: ' . $nafullnameme . '</li>
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

            $_SESSION['success'] = 'Your reservation request has been sent. ';

            header("Location: index.php");
        }
    } else {

        $_SESSION['error'] = 'dates are not the same.';
    }
} else {

    $dateS = new DateTime($_POST['date1']);
    $dateE = new DateTime($_POST['date2']);
    
    $rate = ($dateS->diff($dateE)->format('%a')) * $roomRate;
    require_once 'config.php';
    $_SESSION['name'] = $fullname;
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['phone'] = $_POST['number'];
    $_SESSION['address'] ='';
    $_SESSION['room'] = $roomName;
    $_SESSION['rate'] = $rate;
    $_SESSION['checkin'] = $_POST['date1'];
    $_SESSION['checkout'] = $_POST['date2'];
    $_SESSION['user_id'] = $user_id;

    try {
        $response = $gateway->purchase(
            array(
                'amount' => $_SESSION['rate'],
                'currency' => PAYPAL_CURRENCY,
                'returnUrl' => PAYPAL_RETURN_URL,
                'cancelUrl' => PAYPAL_CANCEL_URL,
            )
        )->send();

        if ($response->isRedirect()) {
            $response->redirect(); // This will automatically forward the customer
        } else {
            // Not successful
            echo $response->getMessage();
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>