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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_GET['reservation_id'])) {
    echo '<h1>Hello</h1>';

    $stmt = $conn->prepare("SELECT * FROM reservation WHERE reservation_id = '$reservation'");
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
  } else {
    if (isset($_GET['action'])) {

    } else {
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

    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Room Availability Timeline</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <!-- Favicon -->
  <link href="img/favicon.ico" rel="icon">

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
    rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Libraries Stylesheet -->
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

  <!-- Customized Bootstrap Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Template Stylesheet -->
  <link href="css/style.css" rel="stylesheet">
  <style>
    /* Custom CSS for setting a specific image height */
    .room-image {
      max-height: 350px;
      /* Set your desired height here */
      object-fit: cover;
      /* Ensure the image covers the specified height */
    }

    .navbar-light .navbar-nav .nav-link {
      color: white;
      font-weight: 500;
    }

    .page-header {
      background: linear-gradient(rgba(43, 57, 64, .5), rgba(43, 57, 64, .5)), url(img/cubes.png) center center no-repeat;
      background-size: cover;
    }

    .map-container {
      position: relative;
      overflow: hidden;
      padding-top: 56.25%;
      /* 16:9 aspect ratio (height / width) */
    }

    .map-container iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }

    .unavailable-indicator {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: red;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
    }
  </style>
  <style>
    .timeline-container {
      display: flex;
      align-items: flex-start;
    }

    .room-info {
      flex: 1;
    }

    .timeline {
      position: relative;
      margin-top: 30px;
      margin-bottom: 30px;
      flex: 2;
    }

    .timeline::before {
      content: '';
      position: absolute;
      top: 0;
      bottom: 0;
      left: 50%;
      width: 2px;
      background: #007bff;
    }

    .timeline-event {
      position: relative;
      margin-bottom: 30px;
      background: #fff;
      border: 2px solid #007bff;
      border-radius: 5px;
      padding: 20px;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      background-color: aqua;
    }

    .timeline-event::before {
      content: '';
      position: absolute;
      top: 5px;
      left: -6px;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: #007bff;
    }

    .reserved {
      border-color: #dc3545;
      background-color: #f5cccc;
    }

    .reserved .timeline-event::before {
      background: #dc3545;
    }

    .timeline-content {
      position: relative;
      z-index: 1;
    }

    .timeline-content p {
      margin: 0;
      padding: 0;
    }

    .timeline-content p:first-child {
      font-size: 20px;
      font-weight: bold;
    }

    .active1 {
      background-color: #e1fb45;
    }
  </style>
</head>

<body>
  <!-- Navbar Start -->
  <nav class="navbar navbar-expand-lg bg-dark navbar-light shadow sticky-top p-0">
    <a href="index.php" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
      <h1 class="m-0 text-primary">Pagana Hotel</h1>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
      <span class="navbar-toggler-icon" style="background-color: white;"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <div class="navbar-nav ms-auto p-4 p-lg-0">
        <a href="index.php" class="nav-item nav-link active">Home</a>
        <a href="rooms.php" class="nav-item nav-link">Rooms</a>
        <a href="about.php" class="nav-item nav-link">About</a>
        <a href="contact.php" class="nav-item nav-link">Contact</a>
        <?php
        if (!isset($_SESSION['user_id']) && !isset($_SESSION['role'])) {
          ?>
          <a href="login.php" class="btn btn-success rounded-0 py-4 px-lg-5">Login<i
              class="fa fa-arrow-right ms-3"></i></a>
          <?php
        } else {
          ?>
          <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Account
            </a>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="user_profile.php">Profile</a></li>
              <li><a class="dropdown-item" href="#">Settings</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="function/logout.php">Logout</a></li>
            </ul>
          </div>
          <?php
        }
        ?>
      </div>
    </div>
  </nav>
  <!-- Navbar End -->

  <div class="container mt-4">

    <?php if (isset($_SESSION['success'])) { ?>
      <div class="alert alert-success">
        <?php echo $_SESSION['success']; ?>
      </div>
      <?php
      unset($_SESSION['success']);
    }
    ?>
    <?php if (isset($_SESSION['error'])) { ?>
      <div class="alert alert-danger">
        <?php echo $_SESSION['error']; ?>
      </div>
      <?php
      unset($_SESSION['error']);
    }
    ?>
    <div class="timeline-container ">

      <?php

      ?>


      <div class="col-md-8" style="padding:10px">
        <div class="card" style="padding:10px">
          <h4 style="margin:10px">Room Details</h4>
          <img src="uploads/<?php echo $roomImage ?>" class="card-img-top room-image" alt="' . $room['name'] . '">
          <?php
          // if ($select_arr[$fetch['name']]['status'] == 1) {
          //     echo '<div class="unavailable-indicator">Out of Order</div>';
          // }
          ?>
          <div class="card-body">
            <h5 class="card-title">
              <?php echo $roomName ?>
            </h5>
            <p class="card-text">Description:
              <?php echo ($roomDescription); ?>
            </p>
            <p>Starting from <strong> ₱
                <?php echo number_format((float) $roomRate, 2, '.', ''); ?>
              </strong> per night (taxes and fees not included). Special rates available for extended stays.</p>
            <?php
            // if ($select_arr[$fetch['name']]['status'] == 0) {
            //     echo '<a href="#" class="btn btn-primary">Reserve</a>';
            // }
            ?>
          </div>
          <ul class="list-group mt-4">
            <h4>Features</h4>
            <!-- Predefined Amenities with Delete Button -->

            <?php

            $i = 0;
            $stmt = $conn->prepare("SELECT * FROM room_features WHERE room_id = '$roomId'");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
              $name = $row["description"];

              ?>
              <li class="list-group-item">
                <i class="fas fa-check"></i>
                <?php echo $name ?>
              </li>
              <?php
              $i++;
            }
            if ($i == 0) {
              echo "N/A";
            }
            ?>

          </ul>
          <ul class="list-group mt-4">
            <h4>Amenities</h4>
            <p>Guests enjoy access to a range of amenities, including:</p>
            <!-- Predefined Amenities with Delete Button -->

            <?php

            $i = 0;
            $stmt = $conn->prepare("SELECT * FROM room_amenities WHERE room_id = '$roomId'");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
              $name = $row["description"];

              ?>
              <li class="list-group-item">
                <i class="fas fa-check"></i>
                <?php echo $name ?>
              </li>
              <?php
              $i++;
            }
            if ($i == 0) {
              echo "N/A";
            }
            ?>

          </ul>
        </div>
      </div>


      <?php





      ?>

      <div style="width: -webkit-fill-available;">
        <h4>Room reservation timeline</h4>
        <div class="timeline">

          <?php
          // Fetch rooms data from the database
          $stmt = $conn->prepare("SELECT * FROM reservation right join room on room.name = room_id where room.id = '$roomId' and checkout > current_date() and reservation.status != 'pending' and reservation.transaction_id != '$reservation' order by checkout");
          $stmt->execute();
          $result = $stmt->get_result();
          $availabilityData;

          $endDate = new DateTime;
          $startDate = new DateTime;
          $curr = new DateTime($startDate->format('y-m-d'));
          $sn = 0;
          while ($row = $result->fetch_assoc()) {

            if ((date_diff($endDate, new DateTime($row["checkin"]))->format('%R%a') >= 1)) {
              $indicatorClass = '';

              $startDate = $endDate;
              $endDate = new DateTime($row['checkin']);
              $duration = ($startDate->diff($endDate))->format('%a days');
              echo '<div class=" timeline-event ' . $indicatorClass . '" style="display:flex">';
              echo '<div class=" col-md-9 timeline-content ">';
              echo '<p>  Available </p>';
              echo '<p>' . $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y') . ' (' . $duration . ')</p>';

              echo '</div>';
              ?>
              <div class="col-md-3">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#loginModal"
                  onclick="changeInputText('<?php echo $startDate->format('Y-m-d'); ?>','<?php echo $endDate->format('Y-m-d'); ?>')"
                  style="margin-top:8px">
                  Reserve
                </button>
              </div>
              <?php
              //echo '<a href=">" class="btn btn-primary">Reserve</a>';
          
              echo '</div>';
            }



            $sn++;

            $indicatorClass = 'reserved';

            $startDate = new DateTime($row['checkin']);
            $endDate = new DateTime($row['checkout']);
            if (date_diff($startDate, $curr)->format('%R%a') >= 0) {
              $indicatorClass = 'active1';
            }
            $duration = ($startDate->diff($endDate))->format('%a days');
            echo '<div class="timeline-event ' . $indicatorClass . '">';
            echo '<div class="timeline-content">';
            echo '<p>  Resereved </p>';
            echo '<p>' . $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y') . ' (' . $duration . ')</p>';
            //echo '<a href=">" class="btn btn-primary">Reserve</a>';
            echo '</div>';
            echo '</div>';

            ?>
            <!-- Add components here -->
            <?php
          }
          $stmt->close();
          $conn->close();
          ?>
          <div class="timeline-event" style="display:flex">
            <div class="timeline-content col-md-9">
              <p> Available </p>
              <p>
                <?php echo $endDate->format('M d,Y') ?> - Onward
              </p>
            </div>
            <div class="col-md-3">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#loginModal"
                onclick="changeInputText('<?php echo $endDate->format('Y-m-d'); ?>','<?php echo '2033-12-31'; ?>')"
                style="margin-top:8px">
                Reserve
              </button>
            </div>
          </div>
          <?php
          echo '<div style="text-align:center;"><span style="text-align:center;background-color:white;position :relative;padding: 20px;color:black;border-radius:50% ;border:1px solid"><i class="fa fa-stop"></i></span></div>';
          ?>


        </div>
      </div>

    </div>

  </div>



  <!-- Start Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width:900px;width:900px">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Reserve Room</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">


          <main class="main-content" style="    width: -webkit-fill-available;">
            <div class="container mt-4">
              <form action="charge2.php?id=<?php echo $roomId?>" method="post" enctype="multipart/form-data" style="display:block;margin-bottom:10px">


                <div class="row" hidden>
                  <div class="col-md-6">

                    <div class="form-group">
                      <label for="number">Mobile Number</label>

                      <input type="number" class="form-control" id="number" name="number" max_length="13" required
                        value="<?php echo $number ?>">
                    </div>
                  </div>
                  <div class="col-md-6">

                    <div class="form-group">
                      <label for="email">Email</label>

                      <input type="email" class="form-control" id="email" max_length="50" required name="email"
                        value="<?php echo $email ?>">
                    </div>
                  </div>


                </div>
            
                <input type="text" name="rate" value="<?php echo $roomRate?>" >
                <div class="row">
                  <div class="col-md-6">

                    <div class="form-group">
                      <label for="date1">Check-in</label>

                      <input type="date" class="form-control" id="date1" max_length="50" required name="date1">
                    </div>
                  </div>
                  <div class="col-md-6">

                    <div class="form-group">
                      <label for="date2">Check-out</label>

                      <input type="date" class="form-control" id="date2" max_length="50" required name="date2">
                    </div>
                  </div>


                </div>

                <div class="form-group">
                  <label for="request">Any Special Request? Let us know here.</label>
                  <textarea id="request" name="request" class="form-control" name="request"></textarea>
                </div>

                <div class="container mt-5">
                  <div class="alert alert-info">
                    <h5 class="alert-heading">Important Information</h5>
                    <p><strong>Check-in Time:</strong> 3:00 PM</p>
                    <p><strong>Check-out Time:</strong> 11:00 AM</p>
                    <p class="mb-0">Please note that early check-ins and late check-outs may be available upon request,
                      subject to availability, and additional charges.</p>
                    <p class="mb-0">Please note that you have to pay the amount to insure this reservation and that your
                      reservation may be cancelled by both party.</p>
                  </div>
                </div>


                <div class="container mt-5">
                  <div class="alert alert-info">
                    <h5 class="alert-heading">Payment Summary</h5>
                    <div><label style="width: 50%;"><strong>Rate:</strong> </label><label id="roomRate"
                        style="width: 50%;text-align: end;">500.00</label></div>
                    <div><label style="width: 50%;"><strong>No. of nights:</strong> </label><label id="night"
                        style="width: 50%;text-align: end;">x 5</label></div>
                    <hr>
                    <div><label style="width: 50%;"><strong>Total Cost:</strong> </label><label id="total"
                        style="width: 50%;text-align: end;">2500.00</label></div>
                    <div style="display: none;" id="disDIV"><label style="width: 50%;"><strong>Extended stay discount
                          (3%):</strong> </label><label id="discount"
                        style="width: 50%;text-align: end;">2500.00</label></div>
                    <div><label style="width: 50%;"><strong>Tax (12%):</strong> </label><label id="tax"
                        style="width: 50%;text-align: end;">+ 300.00</label></div>
                    <hr>

                    <div><label style="width: 50%;"><strong>Grand total:</strong> </label><label id="gt"
                        style="width: 50%;text-align: end;">2800.00</label></div>
                    <p class="mb-0">NOTE: You can use your discount voucher in Check-out</p>
                  </div>
                </div>




                <div style="text-align:center">
                  <button type="submit" name="walkin" class="btn"
                    style="color: #fff;background-color: #0d6efd;border-color: #0d6efd;border-radius:5px;width:300px">Reserve</button>

                  <button type="submit" name="book" class="btn"
                    style="color: #fff;background-color: #0d6efd;border-color: #0d6efd;border-radius:5px;width:300px"><i
                      class="fa fa-credit-card"></i> Book Now</button>
                </div>
                <div style="text-align:center">
                </div>

              </form>
          </main>
        </div>
      </div>
    </div>
  </div>

  <!-- End Modal -->



  <!-- Footer Start -->
  <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-4 col-md-6">
          <h5 class="text-white mb-4">Pagana Hotel</h5>
          <a class="btn btn-link text-white-50" href="about.php">About Us</a>
          <a class="btn btn-link text-white-50" href="contact.php">Contact Us</a>
          <a class="btn btn-link text-white-50" href="rooms.php">Room</a>
          <a class="btn btn-link text-white-50" href="#">Privacy Policy</a>
          <a class="btn btn-link text-white-50" href="#">Terms & Condition</a>
        </div>
        <div class="col-lg-4 col-md-6">
          <h5 class="text-white mb-4">About Us</h5>
          <p>We deliver best services to our clients and making sure they are comfy to the rooms.</p>
        </div>
        <div class="col-lg-4 col-md-6">
          <h5 class="text-white mb-4">Contact</h5>
          <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Rufo Mañara St., RH XI, Cotabato City, near Grecco
            Gas. Station.</p>
          <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>(064) 552 0592</p>
          <p class="mb-2"><i class="fa fa-envelope me-3"></i>paganakutawato@gmail.com</p>
          <div class="d-flex pt-2">
            <a class="btn btn-outline-light btn-social"
              href="https://www.facebook.com/paganakutawatonativerestaurant"><i class="fab fa-facebook-f"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer End -->


  <!-- Back to Top -->
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    var inputElement = document.getElementById("date1");
    var inputElement1 = document.getElementById("date2");

    function changeInputText(date, date1) {
      // Get the input element by its ID

      // Change the value (text) of the input element
      inputElement.value = date;
      inputElement1.value = date1;
      inputElement.setAttribute('max', date1)
      inputElement.setAttribute('min', date)
      inputElement1.setAttribute('max', date1)
      inputElement1.setAttribute('min', date)
      calculatePayment();
    }

    const checkinDateInput = document.getElementById('date2');
    const checkoutDateInput = document.getElementById('date1');
    document.addEventListener('DOMContentLoaded', function () {
      // Get references to your date inputs

      // Add event listeners to date inputs
      checkinDateInput.addEventListener('change', calculatePayment);
      checkoutDateInput.addEventListener('change', calculatePayment);

      // Function to calculate payment
    });
    var element = document.getElementById("disDIV");


    function calculatePayment() {
      // Get the values of the date inputs
      const checkinDate = new Date(checkinDateInput.value);
      const checkoutDate = new Date(checkoutDateInput.value);

      // Perform your payment calculation logic here
      // For this example, we'll assume a simple calculation
      const pricePerNight = <?php echo $roomRate ?>; // Change this to your room's price per night
      const taxRate = 0.12; // 10% tax rate
      var discountRate = 0.0; // 10% discount rate

      // Calculate the number of nights
      const oneDay = 24 * 60 * 60 * 1000; // hours * minutes * seconds * milliseconds
      const nights = Math.round(Math.abs((checkinDate - checkoutDate) / oneDay));
      if (nights >= 7) {
        discountRate = 0.03;
        element.style.display = "block";
      }
      else {
        discountRate = 0.0;
        element.style.display = "none";
      }

      // Calculate the payment information
      const totalAmount = pricePerNight * nights;
      const taxAmount = totalAmount * taxRate;
      const discountAmount = totalAmount * discountRate;
      const grandTotal = totalAmount + taxAmount - discountAmount;

      // Display the payment information
      document.getElementById('roomRate').textContent = '₱ ' + pricePerNight.toFixed(2);
      document.getElementById('night').textContent = 'x ' + nights;
      document.getElementById('tax').textContent = '+ ₱ ' + taxAmount.toFixed(2);
      document.getElementById('discount').textContent = '- ₱ ' + discountAmount.toFixed(2);
      document.getElementById('total').textContent = '₱ ' + totalAmount.toFixed(2);
      document.getElementById('gt').textContent = '₱ ' + grandTotal.toFixed(2);
    }
  </script>
  <script>

  </script>

</body>

</html>