<?php
session_start();
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
if($user_id == false || $role != "Guest"){
      header('Location:login.php');
      exit();
}
$id = $_GET['id'];
$checkin = $_GET['checkin'];
$checkout = $_GET['checkout'];

$checkinTimestamp = strtotime($checkin);
$checkoutTimestamp = strtotime($checkout);

if ($checkinTimestamp !== false && $checkoutTimestamp !== false) {
    $calc_days = floor(($checkoutTimestamp - $checkinTimestamp) / (60 * 60 * 24)) + 1;
} else {
    $calc_days = 0; // Default value in case of invalid dates
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Pagana Hotel</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

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
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <!-- <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
        <!-- Spinner End -->


        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-dark navbar-light shadow sticky-top p-0">
            <a href="index.html" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
                <h1 class="m-0 text-primary">Pagana Hotel</h1>
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon" style="background-color: white;"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="rooms.php" class="nav-item nav-link active">Rooms</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                    <?php
                     if(!isset($_SESSION['user_id']) && !isset($_SESSION['role'])){
                    ?>
                    <a href="login.php" class="btn btn-success rounded-0 py-4 px-lg-5">Login<i class="fa fa-arrow-right ms-3"></i></a>
                    <?php
                     }else{
                    ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            User Profile
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
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


        <!-- Header Start -->
        <div class="container-xxl py-5 bg-dark page-header mb-5">
            <div class="container my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Booking</h1>
            </div>
        </div>
        <!-- Header End -->

        <!-- Room Reservation Start -->
        <div class="container">
        <div class="row">
            <div class="col-md-7">
            <div class="room-details">
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
                
                $stmt = $conn->prepare("SELECT * FROM room where id = '$id'");
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                $roomId = $row['id'];
                $roomImage = $row['image'];
                $roomName = $row['name'];
                $roomType = $row['type'];
                $roomDescription = $row['description'];
                $roomRate = $row['rate'];
                $newRate = $roomRate * $calc_days;    
                ?>
                <div class="card mb-4">
                    <img src="uploads/<?php echo $roomImage; ?>" style="max-height: 400px;" class="card-img-top" alt="<?php echo $roomName; ?>">
                    <div class="card-body">
                    <h5 class="card-title"><?php echo $roomName; ?></h5>
                    <p class="card-text"><strong>Room Type:</strong> <?php echo $roomType; ?></p>
                    <p class="card-text"><strong>Rate: ₱</strong><?php echo $newRate; ?></p>
                    <hr>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Description: <?php echo $roomDescription; ?></li>
                    </ul>
                    <hr>
                        <div id="amenities-container">
                            <h1>Amenities</h1>
                            <select id="am1" onchange="amenities1()" class="amenities-select">
                                <option value="0">Select Amenities</option>
                                <?php
                                 $select = mysqli_query($conn, "SELECT * FROM `amenities`");
                                 $select_arr = array(); 
                                    if(mysqli_num_rows($select)>0){
                                       while($fetch = mysqli_fetch_assoc($select)){
                                          $select_arr[$fetch['name']]= $fetch;
                                          $book = explode(',', $select_arr[$fetch['name']]['name']);
                                          foreach($book as $booked){
                                          }
               
                                       }
                                    }else{
                                        echo '<p class="empty" style="color: black; text-align: center;">No Available Amenities has been found!</p>';
                                    }
                                 $fetch_booked = mysqli_query($conn, "SELECT distinct name from `amenities` where name not in (SELECT name from `amenities_reservation` where '$checkin' BETWEEN date(checkin) and date(checkout) OR '$checkout' BETWEEN date(checkin) and date(checkout) OR (checkin <= '$checkout' AND checkout >= '$checkin')
                                 OR (checkin <= '$checkin' AND checkout >= '$checkout')
                                 OR (checkin >= '$checkin' AND checkout <= '$checkout'))");
                                 if(mysqli_num_rows($fetch_booked)>0){
                                 while($fetch = mysqli_fetch_assoc($fetch_booked)){
                                    $am1 = $select_arr[$fetch['name']]['rate'] * $calc_days;
                                    ?>
                                    <option value="<?php echo $select_arr[$fetch['name']]['name']; ?>" data-rate="<?php echo $am1; ?>"><?php echo $select_arr[$fetch['name']]['name']; ?></option>
                                <?php
                                 }
                                }
                                else{
                                    echo '<p class="empty" style="color: black; text-align: center;">No Available Amenities has been found!</p>';
                            
                                    }
                                ?>
                            </select>
                            <select id="am2" onchange="amenities2()" class="amenities-select">
                                <option value="0">Select Amenities</option>
                                <?php
                                 $select = mysqli_query($conn, "SELECT * FROM `amenities`");
                                 $select_arr = array(); 
                                    if(mysqli_num_rows($select)>0){
                                       while($fetch = mysqli_fetch_assoc($select)){
                                          $select_arr[$fetch['name']]= $fetch;
                                          $book = explode(',', $select_arr[$fetch['name']]['name']);
                                          foreach($book as $booked){
                                          }
               
                                       }
                                    }else{
                                        echo '<p class="empty" style="color: black; text-align: center;">No Available Amenities has been found!</p>';
                                    }
                                 $fetch_booked = mysqli_query($conn, "SELECT distinct name from `amenities` where name not in (SELECT name from `amenities_reservation` where '$checkin' BETWEEN date(checkin) and date(checkout) OR '$checkout' BETWEEN date(checkin) and date(checkout) OR (checkin <= '$checkout' AND checkout >= '$checkin')
                                 OR (checkin <= '$checkin' AND checkout >= '$checkout')
                                 OR (checkin >= '$checkin' AND checkout <= '$checkout'))");
                                 if(mysqli_num_rows($fetch_booked)>0){
                                 while($fetch = mysqli_fetch_assoc($fetch_booked)){
                                    $am2 = $select_arr[$fetch['name']]['rate'] * $calc_days;

                                    ?>
                                    <option value="<?php echo $select_arr[$fetch['name']]['name']; ?>" data-rate="<?php echo $am2; ?>"><?php echo $select_arr[$fetch['name']]['name']; ?></option>
                                <?php
                                 }
                                }
                                else{
                                    echo '<p class="empty" style="color: black; text-align: center;">No Available Amenities has been found!</p>';
                            
                                    }
                                ?>
                            </select>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="col-md-4">
            <div class="reservation-form">
                <h2>Reservation Form</h2>
                <?php $user = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'");
                      $users = mysqli_fetch_assoc($user);
                ?>
                <form action="charge.php" method="POST" id="paypal_form" onsubmit="return validateForm()">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="room" value="<?php echo $roomName; ?>">
                    <input type="hidden" name="amenities1" id="amenities1">
                    <input type="hidden" name="amenities2" id="amenities2">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $users['fname']; echo ' '; echo $users['mname']; echo ' '; echo $users['lname']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $users['email']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="phone">Mobile Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $users['phone']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="address">Address <span style="color: red;">*</span></label>
                    <textarea class="form-control" id="address" name="address" placeholder="Enter your address" required></textarea>
                </div>
                <div class="form-group">
                    <label for="checkin">Check-In</label>
                    <input type="date" class="form-control" id="checkin" name="checkin" value="<?php echo $checkin; ?>"  readonly>
                </div>
                <div class="form-group">
                    <label for="checkout">Check-Out</label>
                    <input type="date" class="form-control" id="checkout" name="checkout" value="<?php echo $checkout; ?>" readonly>
                </div>
                <br>
                <div class="form-group">
                    <label for="">Total Amount: ₱</label>
                    <label><input type="text" id="totalAmount" class="form-control" name="rate" value="<?php echo $newRate; ?>" readonly></label>
                </div>

                <br>
                <button type="submit" name="submit" class="form-control" style="background-image: linear-gradient(#FFF0A8, #F9B421); font-weight:1000;"><a style="font-size:20px; font-weight:2000; color: #253b80;">Pay</a><a style="font-size:20px; font-weight:2000; color: #179bd7;">Pal</a></button>
                <button type="submit" name="walkin" class="form-control">Walk-In</button>
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
        var totalAmountInput = document.getElementById('totalAmount');

        if (amenities1Select.value !== '0') {
            var amenities1Option = amenities1Select.options[amenities1Select.selectedIndex];
            amenitiesRate1 = parseInt(amenities1Option.getAttribute('data-rate'));
        }

        if (amenities2Select.value !== '0') {
            var amenities2Option = amenities2Select.options[amenities2Select.selectedIndex];
            amenitiesRate2 = parseInt(amenities2Option.getAttribute('data-rate'));
        }

        var totalAmount = roomRate + amenitiesRate1 + amenitiesRate2;
        totalAmountInput.value = totalAmount.toFixed(2);
    }

    // Event listeners for the amenities select options
    document.getElementById('am1').addEventListener('change', calculateTotalAmount);
    document.getElementById('am2').addEventListener('change', calculateTotalAmount);
</script>
        <script>
            var phoneInput = document.getElementById("phone");
            phoneInput.addEventListener("input", function() {
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
                var allowedDomains = ['yahoo.com', 'ymail', 'gmail.com', 'outlook.com', 'sti.edu.ph'];

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
            $('select').on('change', function() {
            $('option').prop('disabled', false); //reset all the disabled options on every change event
            $('select').each(function() { //loop through all the select elements
                var val = this.value;
                $('select').not(this).find('option').filter(function() { //filter option elements having value as selected option
                return this.value === val;
                }).prop('disabled', true); //disable those option elements
            });
            }).change(); //trihgger change handler initially!
            </script>

            <script>
            function amenities1(){
            var am1 = document.getElementById("am1");
            var text1 = am1.options[am1.selectedIndex].text;
            document.getElementById("amenities1").value=text1;
            }
            </script>

            <script>
            function amenities2(){
            var am1 = document.getElementById("am2");
            var text1 = am1.options[am1.selectedIndex].text;
            document.getElementById("amenities2").value=text1;
            }
            </script>

            <script>
            function amenities3(){
            var am1 = document.getElementById("am3");
            var text1 = am1.options[am1.selectedIndex].text;
            document.getElementById("amenities3").value=text1;
            }
            </script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php require("user_footer.php"); ?>

</body>
</html>
