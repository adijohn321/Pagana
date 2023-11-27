<?php
session_start();
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
if ($user_id == false || $role != "InCharge") {
    header('Location:../login.php');
    exit();
}
$id = $_GET['id'];
$checkin = $_GET['checkin'];
$checkout = $_GET['checkout'];
$calc_days = abs(strtotime($_GET['checkout']) - strtotime($_GET['checkin']));
$calc_days = floor($calc_days / (60 * 60 * 24));
$conn = new mysqli('localhost', 'root', '', 'paganadb');


if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $room = $_POST['room'];
    $rate = $_POST['rate'];
    $rate = str_replace("₱ ", "", $rate);
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $am1 = $_POST['amenities1'];
    $am2 = $_POST['amenities2'];
    $ref_no = rand(999999999, 000000000);
    $status = "Reserved";
    date_default_timezone_set('Asia/Manila');
    $now = date("Y-m-d H:i:s");

    $stmt = mysqli_query($conn, "INSERT INTO `reservation` (room_id, name, email, phone, address, checkin, checkout, amount_paid, total_rate, transaction_id, status, datecreated) VALUES('$room', '$name', '$email', '$phone', '$address', '$checkin', '$checkout', '$rate', '$rate', '$ref_no', '$status', '$now')");
    $reservationId = mysqli_insert_id($conn);
    if (isset($am1)) {
    } else if (isset($am1)) {
        $stmt1 = mysqli_query($conn, "INSERT INTO `amenities_reservation` (reservation_id, name, checkin, checkout) VALUES('$reservationId', '$am1', '$checkin', '$checkout')");
    }
    if ($am2 == false) {
    } else if ($am2 == true) {
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
            <h1>Pagana Kutawato</h1>
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
        header("Location: roomavail.php");

        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
//else
// {
//     $stmt = $conn->prepare("SELECT * FROM room WHERE id = '$id'");
//     $stmt->execute();
//     $result = $stmt->get_result();

//     $sn = 0;
//     while ($row = $result->fetch_assoc()) {
//     $sn++;

//     $roomId = $row['id'];
//     $roomImage = $row['image'];
//     $roomName = $row['name'];
//     $roomType = $row['type'];
//     $roomDescription = $row['description'];
//     $roomRate = $row['rate'];
//     }
// }






?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Pagana Kutawato</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .navbar-light .navbar-nav .nav-link {
            color: white;
            font-weight: 500;
        }

        .page-header {
            background: linear-gradient(rgba(43, 57, 64, .5), rgba(43, 57, 64, .5)), url(img/pagana2.jpg) center center no-repeat;
            background-size: cover;
        }

        .container-xxl {
            padding: 0;
        }

        .room-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px;
        }

        .reservation-form {
            max-width: 400px;
            margin-left: auto;
        }

        label {
            font-size: 0.75rem;
        }
    </style>
</head>

<body>
    <!-- Room Reservation Start -->
    <div class="container">
        <div class="row" style="    margin-top: 25px;">
            <h2 style="text-align:center">Room Booking</h2>
            <div class="col-md-6">

                <h4>Room Information</h4>
                <div class="room-details" style="margin:0">
                    <?php
                    // Fetch rooms data from the database
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
                    $reservationId;
                    $stmt = $conn->prepare("SELECT * FROM room where id = '$id' or name = '$id'");
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {

                        $roomId = $row['id'];
                        $roomImage = $row['image'];
                        $roomName = $row['name'];
                        $roomType = $row['type'];
                        $roomDescription = $row['description'];
                        $roomRate = $row['rate'];
                        $newRate = $roomRate * $calc_days;
                        ?>
                        <div class="card mb-4">
                            <img src="../uploads/<?php echo $roomImage; ?>" style="max-height: 400px;" class="card-img-top"
                                alt="<?php echo $roomName; ?>">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo $roomName; ?>
                                </h5>
                                <p class="card-text"><strong>Room Type:</strong>
                                    <?php echo $roomType; ?>
                                </p>
                                <p class="card-text"><strong>Rate: ₱</strong>
                                    <?php echo $roomRate; ?>
                                </p>
                                <hr>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Description:
                                        <?php echo $roomDescription; ?>
                                    </li>
                                </ul>
                                <hr>
                                <div id="amenities-container">
                                    <ul class="list-group mt-4">
                                        <h4>Room Features</h4>
                                        <!-- Predefined Amenities with Delete Button -->

                                        <?php

                                        $i = 0;
                                        $stmt->close();
                                        $stmt = $conn->prepare("SELECT * FROM room_features WHERE room_id = '$id'");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while ($row = $result->fetch_assoc()) {
                                            $name = $row["description"];
                                            $amid = $row["id"];

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
                                        <h4>Room Amenities</h4>
                                        <!-- Predefined Amenities with Delete Button -->

                                        <?php

                                        $i = 0;
                                        $stmt->close();
                                        $stmt = $conn->prepare("SELECT * FROM room_amenities WHERE room_id = '$id'");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while ($row = $result->fetch_assoc()) {
                                            $name = $row["description"];
                                            $amid = $row["id"];

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
                                    <?php
                    }
                    ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Client Information</h4>
                <div class="reservation-form" style="margin:0">

                    <form action="" method="POST" onsubmit="return validateForm()" style="width:45vw">

                        <input type="text" class="form-control" id="rid" name="rid" value="<?php echo $rid; ?>" hidden>
                        <input type="hidden" name="room" value="<?php echo $roomName; ?>">
                        <input type="hidden" name="amenities1" id="amenities1">
                        <input type="hidden" name="amenities2" id="amenities2">
                        <div class="form-group">
                            <label for="name">Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="address">Address <span style="color: red;">*</span></label>
                            <textarea class="form-control" id="address" name="address" placeholder="Enter your address"
                                required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-8">


                                <div class="form-group">
                                    <label for="email">Email <span style="color: red;">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter your email" required>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="phone">Mobile Number <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Enter your phone" required>
                                </div>
                            </div>


                        </div>
                        <br>
                        <h4>Booking Summary</h4>
                        <div class="row">
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="checkin">Check-In</label>
                                    <input type="date" class="form-control" id="checkin" name="checkin"
                                        value="<?php echo $checkin; ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="checkout">Check-Out</label>
                                    <input type="date" class="form-control" id="checkout" name="checkout"
                                        value="<?php echo $checkout; ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="noOfdays">No. of Days</label>
                                    <input type="text" class="form-control" id="noOfdays" name="noOfdays"
                                        value="<?php echo $calc_days; ?>" readonly>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="rate">Total Amount: </label><input type="text" id="rate" class="form-control"
                                name="rate" value="<?php echo $newRate; ?>" readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-6">


                                <div class="form-group">
                                    <label for="cash">Cash Tendered: </label><input type="number" id="cash" step="any"
                                        class="form-control" name="cash" style="font-weight: 900;
    font-size: 3rem;">
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="chage">Change</label>
                                    <input type="text" class="form-control" id="change" name="change" readonly>
                                </div>
                            </div>


                        </div>


                        <br>
                        <button type="submit" name="submit" id="submit" class="form-control btn-secondary"
                            disabled>Book</button>
                        <a href="roomavail.php">Go Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Room Reservation End -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        // Function to calculate the total amount dynamically
        function calculateTotalAmount() {
            var roomRate = <?php echo $newRate; ?>;
            var amenitiesRate1 = 0;
            var amenitiesRate2 = 0;

            var amenities1Select = document.getElementById('am1');
            var amenities2Select = document.getElementById('am2');
            var totalAmountInput = document.getElementById('rate');

            if (amenities1Select.value !== '0') {
                var amenities1Option = amenities1Select.options[amenities1Select.selectedIndex];
                amenitiesRate1 = parseInt(amenities1Option.getAttribute('data-rate'));
            }

            if (amenities2Select.value !== '0') {
                var amenities2Option = amenities2Select.options[amenities2Select.selectedIndex];
                amenitiesRate2 = parseInt(amenities2Option.getAttribute('data-rate'));
            }

            var totalAmount = roomRate + amenitiesRate1 + amenitiesRate2;
            totalAmountInput.value = "₱ " + totalAmount.toFixed(2);
        }

        // Event listeners for the amenities select options
        document.getElementById('am1').addEventListener('change', calculateTotalAmount);
        document.getElementById('am2').addEventListener('change', calculateTotalAmount);
    </script>
    <script>
        var phoneInput = document.getElementById("phone");
        phoneInput.addEventListener("input", function () {
            var phoneNumber = phoneInput.value.trim();

            // Remove any non-digit characters
            phoneNumber = phoneNumber.replace(/\D/g, "");

            // Check if the number starts with "09" and has a maximum of 11 digits
            if (!phoneNumber.startsWith("09") || phoneNumber.length > 11 || phoneNumber.length < 11) {
                phoneInput.setCustomValidity("Please input your correct philippine phone number.");
            } else {
                phoneInput.setCustomValidity("");
            }
        });
    </script>
    <script>
        function validateForm() {
            var emailInput = document.getElementById('email');
            var email = emailInput.value.trim().toLowerCase();

            // Extract domain from email
            var domain = email.split('@')[1];

            // Define allowed domains
            var allowedDomains = ['yahoo.com', 'gmail.com', 'outlook.com'];

            // Check if the domain is in the allowed domains list
            if (allowedDomains.indexOf(domain) === -1) {
                alert('Please enter a valid email address with a Yahoo, Google, or Outlook domain.');
                emailInput.focus();
                return false;
            }

            return true;
        }
    </script>
    <script type="text/javascript">
        $('select').on('change', function () {
            $('option').prop('disabled', false); //reset all the disabled options on every change event
            $('select').each(function () { //loop through all the select elements
                var val = this.value;
                $('select').not(this).find('option').filter(function () { //filter option elements having value as selected option
                    return this.value === val;
                }).prop('disabled', true); //disable those option elements
            });
        }).change(); //trihgger change handler initially!
    </script>

    <script>
        function amenities1() {
            var am1 = document.getElementById("am1");
            var text1 = am1.options[am1.selectedIndex].text;
            document.getElementById("amenities1").value = text1;
            inputElement.value = 0;
        }
    </script>

    <script>
        function amenities2() {
            var am1 = document.getElementById("am2");
            var text1 = am1.options[am1.selectedIndex].text;
            document.getElementById("amenities2").value = text1;
            inputElement.value = 0;
        }
    </script>

    <script>
        function amenities3() {
            var am1 = document.getElementById("am3");
            var text1 = am1.options[am1.selectedIndex].text;
            document.getElementById("amenities3").value = text1;

        }

        var inputElement = document.getElementById('cash');
        var change = document.getElementById('change');
        var amount = document.getElementById('rate');
        var submit = document.getElementById('submit');
        inputElement.addEventListener('input', function () {
            if (inputElement.value.length === 0) {
                change.value = "₱ 0.00";
                return
            }
            // This function will be called when the input value changes
            // var inputValue = inputElement.value;
            // outputElement.textContent = 'Input value changed to: ' + inputValue;
            change.value = "₱ " + (parseFloat(inputElement.value.replace("₱", "").replace(/\s+/g, '')) - parseFloat(amount.value.replace("₱", "").replace(/\s+/g, ''))).toFixed(2);
            if ((parseFloat(amount.value.replace("₱", "").replace(/\s+/g, '')) - parseFloat(inputElement.value.replace("₱", "").replace(/\s+/g, ''))) > 0) {
                submit.disabled = true;
            } else {
                submit.disabled = false;
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>