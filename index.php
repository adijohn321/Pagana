<?php
session_start();
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : date('Y-m-d', strtotime(date('Y-m-d') . '1 days'));
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : date('Y-m-d', strtotime(date('Y-m-d') . '+ 1 days'));
$cdate = new DateTime("1 Day");
$fdate = $cdate->format('Y-m-d');

$rdate = new DateTime("3 Months");
$ddate = $rdate->format('Y-m-d');

if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest" || !isset($_SESSION['user_id'])) {
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
            /* Styling for the floating advertisement */
            .floating-ad {
                /* Initially hide the advertisement */
                position: fixed;
                bottom: 20px;
                /* Adjust the top position as needed */
                right: -1800px;
                /* Off the left side of the screen */
                background-color: #ff9900;
                padding: 10px;
                border-radius: 5px;
                z-index: 9999;
                transition: right 1s;
                /* Add a sliding transition effect */
            }

            /* Close button style */
            .close-button {
                cursor: pointer;
                position: absolute;
                top: 5px;
                right: 5px;
            }
        </style>
        <style>
            /* Custom CSS for setting a specific image height */
            .room-image {
                max-height: 250px;
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
                right: 20px;
                background-color: red;
                color: white;
                padding: 5px 10px;
                border-radius: 5px;
            }
        </style>
    </head>

    <body>
        <div class="container-xxl bg-white p-0" style="max-width: 90vw;">
            <!-- Spinner Start -->
            <div id="spinner"
                class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <!-- Spinner End -->


            <!-- Navbar Start -->
            <nav class="navbar navbar-expand-lg bg-dark navbar-light shadow sticky-top p-0">
                <a href="index.php" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
                    <h1 class="m-0 text-primary">Pagana Hotel</h1>
                </a>
                <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
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
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
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


            <!-- Carousel Start -->
            <div class="container-fluid py-5 bg-dark page-header">
                <div class="container my-5 pt-5">
                    <h1 class="display-3 text-white animated slideInDown mb-4">Welcome to Pagana Kutawato</h1>
                    <p class="fs-5 fw-medium text-white mb-4 pb-2">You can explore our beautiful hotel and its surroundings.
                    </p>
                    <a href="" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Explore Hotel</a>
                </div>
            </div>
            <!-- Carousel End -->


            <!-- Search Start -->
            <div class="container-fluid bg-dark mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
                <div class="container">
                    <form action="rooms.php" method="GET" onsubmit="return validateForm()">
                        <div class="row g-2">
                            <div class="col-md-10">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label style="color: white;">From:</label>
                                        <input type="date" id="checkin" name="checkin" min="<?php echo $fdate; ?>"
                                            max="<?php echo $ddate; ?>" class="form-control border-0"
                                            value="<?php echo $checkin; ?>" />
                                    </div>
                                    <div class="col-md-6">
                                        <label style="color: white;">To:</label>
                                        <input type="date" id="checkout" name="checkout" min="<?php echo $fdate; ?>"
                                            max="<?php echo $ddate; ?>" class="form-control border-0"
                                            value="<?php echo $checkout; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label></label>
                                <button class="btn btn-dark border-0 w-100">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Search End -->
            <?php
            if (!isset($_SESSION['user_id']) && !isset($_SESSION['role'])) {
                ?>
                <!-- //no user -->
                <div class="row">
                    <div class="col-md-9">
                        <div class=" mt-9">
                            <div class="">
                                <div class="card text-center">
                                    <div class="card-header">
                                        <h2>Explore</h2>
                                    </div>
                                    <div class="card-body">
                                        <div>

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
                                            $select = mysqli_query($conn, "SELECT * FROM `room`");
                                            $select_arr = array();
                                            if (mysqli_num_rows($select) > 0) {
                                                while ($fetch = mysqli_fetch_assoc($select)) {
                                                    $select_arr[$fetch['name']] = $fetch;
                                                    $book = explode(',', $select_arr[$fetch['name']]['name']);
                                                    foreach ($book as $booked) {
                                                    }
                                                }
                                            } else {
                                            }
                                            $fetch_booked = mysqli_query($conn, "SELECT distinct name from `room` oRDER BY rand() ASC limit 5 ");
                                            if (mysqli_num_rows($fetch_booked) > 0) {
                                                while ($fetch = mysqli_fetch_assoc($fetch_booked)) {

                                                    ?>

                                                    <div class="row" style="    margin: 10px;border: 1px solid;border-radius: 10px;">
                                                        <div class="col-md-4 card">
                                                            <img src="uploads/<?php echo $select_arr[$fetch['name']]['image']; ?>"
                                                                class="card-img-top room-image" alt="' . $room['name'] . '">
                                                            <?php
                                                            if ($select_arr[$fetch['name']]['status'] == 1) {
                                                                echo '<div class="unavailable-indicator">Out of Order</div>';
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-md-8" style="text-align: left;">

                                                            <div class="card-body">
                                                                <h5 class="card-title"
                                                                    style="margin-top: 0.75rem; padding-left: 0rem; font-size:30px">
                                                                    <?php echo $select_arr[$fetch['name']]['name']; ?>
                                                                </h5>

                                                                <p class="mb-2"><i class="fa fa-star me-3"></i> 5.0 (915 Reviews) |
                                                                    <?php echo $select_arr[$fetch['name']]['type'] ?>
                                                                </p>
                                                                <p class="card-text">Description:
                                                                    <?php echo substr($select_arr[$fetch['name']]['description'], 0, 100); ?>...
                                                                </p>
                                                                <p class="mb-2"><a
                                                                        onclick="fetchData('<?php echo $select_arr[$fetch['name']]['id'] ?>')"
                                                                        data-toggle="modal" data-target="#loginModal">View Room Details
                                                                    </a><i class="fa fa-arrow-right me-3"></i></p>
                                                                <!-- <p class="card-text"><strong>Price:</strong> ₱<?php echo $select_arr[$fetch['name']]['rate']; ?></p> -->
                                                                <hr>
                                                                <div style="    text-align: end; margin:10px; margin-bottom: 0px;">
                                                                    <?php
                                                                    if ($select_arr[$fetch['name']]['status'] == 0) {
                                                                        // echo '<a href="#" class="btn btn-primary">Reserve</a>';
                                                    
                                                                        ?>
                                                                        <!-- <a href="availability.php?id=<?php echo $select_arr[$fetch['name']]['id']; ?>" class="btn btn-secondary">Availability</a> -->
                                                                    <?php } ?>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>





                                                    <?php

                                                }
                                            } else {
                                                echo '<p class="empty" style="color: black; text-align: center;">No Available Room has been found!</p>';
                                            }

                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="container mt-5">
                            <div class="row">
                                <div class="">
                                    <div class="card text-center">
                                        <div class="card-header">
                                            <h2>Introducing Our Exclusive Sign-Up Offer</h2>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">10% Discount Gift Coupon!</h3>
                                            <p class="card-text">
                                                Are you ready to unlock amazing savings? Sign up with us today, and you'll
                                                receive a special gift from us - a <strong>10% Discount Gift Coupon</strong>!
                                            </p>
                                            <p class="card-text">At PAGANA Hotel, we believe in rewarding our valued customers
                                                right from the start.</p>
                                            <p class="card-text">When you create an account with us, you'll gain access to a
                                                world of fantastic products and services, all with an exclusive 10% discount.
                                            </p>
                                        </div>
                                        <div class="card-footer">
                                            <p class="card-text">
                                                Don't miss out on this incredible opportunity. Sign up today and claim your 10%
                                                Discount Gift Coupon. It's our way of saying "Thank you" for choosing PAGANA
                                                Hotel.
                                            </p>
                                            <!-- Add your Sign Up link here -->
                                            <a href="registration.php" class="btn btn-primary">Sign Up Now</a>
                                            <p class="mt-3">
                                                Discover, shop, and save with PAGANA Hotel. It's more than a coupon; it's a key
                                                to a world of possibilities.
                                            </p>
                                            <p>
                                                <em>Terms and Conditions apply. Offer valid for new sign-ups only. Discount
                                                    applied at checkout. Expires [Coupon Expiry Date].</em>
                                            </p>
                                            <!-- Add your company logo here -->
                                            <h1 class="m-0 text-primary">Pagana Hotel</h1>
                                            <!-- <img src="your-company-logo.png" alt="Your Company Logo" width="150"> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>





                <?php
            } else {
                ?>
                <!-- Yes user -->

                <div class="container mt-5">


                </div>


                <div class="container mt-4">
                    <h3>Explore Our Rooms</h3>

                    <div>

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
                        $select = mysqli_query($conn, "SELECT * FROM `room`");
                        $select_arr = array();
                        if (mysqli_num_rows($select) > 0) {
                            while ($fetch = mysqli_fetch_assoc($select)) {
                                $select_arr[$fetch['name']] = $fetch;
                                $book = explode(',', $select_arr[$fetch['name']]['name']);
                                foreach ($book as $booked) {
                                }
                            }
                        } else {
                        }
                        $fetch_booked = mysqli_query($conn, "SELECT distinct name from `room` oRDER BY `room`.`rate` ASC ");
                        if (mysqli_num_rows($fetch_booked) > 0) {
                            while ($fetch = mysqli_fetch_assoc($fetch_booked)) {

                                ?>

                                <div class="row" style="    margin: 10px;border: 1px solid;border-radius: 10px;">
                                    <div class="col-md-4 card">
                                        <img src="uploads/<?php echo $select_arr[$fetch['name']]['image']; ?>"
                                            class="card-img-top room-image" alt="' . $room['name'] . '">
                                        <?php
                                        if ($select_arr[$fetch['name']]['status'] == 1) {
                                            echo '<div class="unavailable-indicator">Out of Order</div>';
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-8">

                                        <div class="card-body">
                                            <a href="rating.php?id=<?php echo $select_arr[$fetch['name']]['id']; ?>">
                                                <h5 class="card-title" style="margin-top: 0.75rem; padding-left: 0rem; font-size:30px">
                                                    <?php echo $select_arr[$fetch['name']]['name']; ?>
                                                </h5>
                                            </a>

                                            <p class="mb-2"><i class="fa fa-star me-3"></i> 5.0 (915 Reviews) |
                                                <?php echo $select_arr[$fetch['name']]['type'] ?>
                                            </p>
                                            <p class="card-text">Description:
                                                <?php echo substr($select_arr[$fetch['name']]['description'], 0, 100); ?>...
                                            </p>
                                            <p class="mb-2"><a onclick="fetchData('<?php echo $select_arr[$fetch['name']]['id'] ?>')"
                                                    data-toggle="modal" data-target="#loginModal">View Room Details </a><i
                                                    class="fa fa-arrow-right me-3"></i></p>
                                            <!-- <p class="card-text"><strong>Price:</strong> ₱<?php echo $select_arr[$fetch['name']]['rate']; ?></p> -->
                                            <hr>
                                            <div style="    text-align: end; margin:10px; margin-bottom: 0px;">
                                                <?php
                                                if ($select_arr[$fetch['name']]['status'] == 0) {
                                                    // echo '<a href="#" class="btn btn-primary">Reserve</a>';
                                
                                                    ?>
                                                    <a href="availability.php?id=<?php echo $select_arr[$fetch['name']]['id']; ?>"
                                                        class="btn btn-secondary">Availability</a>
                                                <?php } ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>





                                <?php

                            }
                        } else {
                            echo '<p class="empty" style="color: black; text-align: center;">No Available Room has been found!</p>';
                        }

                        ?>

                    </div>
                </div>




            </div>
            <?php
            }
            ?>
        <div class="floating-ad" id="floatingAd">


            <div class="">
                <div class="col-md-12">
                    <div class="card text-center">
                        <div class="card-header">
                            <h2>Introducing Our Exclusive Extended Stay Discount! Offer</h2>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <p>Welcome to our website! Book now using our app for an amazing 3% discount on extended
                                    stays of 7 or more nights.</p>
                                <p>Don't miss out on this special offer, exclusively for our app users.</p>
                                <a href="#" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                        <div class="card-footer">

                            <p class="mt-3">
                                Discover, shop, and save with PAGANA Hotel. It's more than a coupon; it's a key to a world
                                of possibilities.
                            </p>
                            <p>
                                <em>Terms and Conditions apply. Discount applied at checkout. Expires [Coupon Expiry
                                    Date].</em>
                            </p>
                            <!-- Add your company logo here -->
                            <img src="your-company-logo.png" alt="Your Company Logo" width="150">
                            <br>
                            <span class="btn btn-primary" id="closeButton">Close Ad</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Contact Us</h1>
                <div class="row g-4">
                    <div class="col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15833.29941928197!2d124.2344376!3d7.2037293!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3256398f3c4e02ed%3A0xc6b05a5a5cbdafea!2sPagana%20Kutawato!5e0!3m2!1sen!2sph!4v1688297075418!5m2!1sen!2sph"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="wow fadeInUp" data-wow-delay="0.5s">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="name" placeholder="Your Name">
                                            <label for="name">Your Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="email" placeholder="Your Email">
                                            <label for="email">Your Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="subject" placeholder="Subject">
                                            <label for="subject">Subject</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a message here" id="message"
                                                style="height: 150px"></textarea>
                                            <label for="message">Message</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-3" type="submit">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
                        <h5 class="modal-title" id="loginModalLabel">Room Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        <main class="main-content" style="    width: -webkit-fill-available;">
                            <div class="container mt-4" id="roomInfo">

                            </div>

                        </main>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Modal -->
        <!-- Contact End -->
        <script>
            function fetchData(dataID) {
                document.getElementById('roomInfo').innerHTML = `
                                <div style="text-align:center"><div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div></div>
                                `;
                fetch('getRoomInfo.php?id=' + dataID) // Replace with the URL of your server script
                    .then(response => response.text())
                    .then(html => {
                        // Insert the received HTML into the page
                        document.getElementById('roomInfo').innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
            $(function () {
                var dtToday = new Date();

                var month = dtToday.getMonth() + 1;
                var day = dtToday.getDate() + 1;
                var year = dtToday.getFullYear();
                if (month < 10)
                    month = '0' + month.toString();
                if (day < 10)
                    day = '0' + day.toString();

                var maxDate = year + '-' + month + '-' + day;
                $('#checkin').attr('min', maxDate);
            });

            $(function () {
                var dtToday = new Date();

                var month = dtToday.getMonth() + 1;
                var day = dtToday.getDate() + 2;
                var year = dtToday.getFullYear();
                if (month < 10)
                    month = '0' + month.toString();
                if (day < 10)
                    day = '0' + day.toString();

                var maxDate = year + '-' + month + '-' + day;
                $('#checkout').attr('min', maxDate);
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                setTimeout(function () {
                    var floatingAd = document.getElementById("floatingAd");
                    floatingAd.style.display = "block";
                    floatingAd.style.right = "20px"; // Slide in from the left
                }, 3000); // 3000 milliseconds (3 seconds) delay
            });

            // JavaScript to close the floating advertisement
            document.getElementById("closeButton").addEventListener("click", function () {
                var floatingAd = document.getElementById("floatingAd");
                floatingAd.style.right = "-1800px"; // Slide out to the left
            });
        </script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
        <script>
            function validateForm() {
                var fromDate = new Date(document.getElementById("checkin").value);
                var toDate = new Date(document.getElementById("checkout").value);

                if (fromDate > toDate) {
                    alert("Check-In date cannot be higher than Check-Out date");
                    return false; // Prevent form submission
                }

                return true; // Allow form submission
            }
        </script>


        <?php require("user_footer.php"); ?>


    </body>

    </html>
    <?php
    exit();
} elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
    header("Location: admin/dashboard.php");
} elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "InCharge") {
    header("Location: GIC/dashboard.php");
}


?>