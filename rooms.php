<?php 
session_start();

$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : date('Y-m-d',strtotime(date('Y-m-d'). '1 days'));
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : date('Y-m-d',strtotime(date('Y-m-d'). '+ 1 days'));
$cdate = new DateTime("3 Months");
$fdate = $cdate->format('Y-m-d');
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
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
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
                            <li><a class="dropdown-item" href="user_profile.php">Profile</a></li>
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


        <!-- Header End -->
        <div class="container-xxl py-5 bg-dark page-header mb-5">
            <div class="container my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Room</h1>
            </div>
        </div>
        <!-- Header End -->

        <!-- Search Start -->
        <div class="container-fluid bg-dark mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
            <div class="container">
                <form action="rooms.php" method="GET" onsubmit="return validateForm()">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label style="color: white;">From:</label>
                                    <input type="date" id="checkin" name="checkin" max="<?php echo $fdate; ?>" class="form-control border-0" value="<?php echo $checkin; ?>" />
                                </div>
                                <div class="col-md-6">
                                    <label style="color: white;">To:</label>
                                    <input type="date" id="checkout" name="checkout" max="<?php echo $fdate; ?>" class="form-control border-0" value="<?php echo $checkout; ?>" />
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

        <!-- Room Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Room</h1>
                <div class="row g-4">
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
                           if(mysqli_num_rows($select)>0){
                              while($fetch = mysqli_fetch_assoc($select)){
                                 $select_arr[$fetch['name']]= $fetch;
                                 $book = explode(',', $select_arr[$fetch['name']]['name']);
                                 foreach($book as $booked){
                                 }
      
                              }
                           }else{
                           }
                        $fetch_booked = mysqli_query($conn, "SELECT distinct name from `room` where name not in (SELECT room_id from `reservation` where '$checkin' BETWEEN date(checkin) and date(checkout) OR '$checkout' BETWEEN date(checkin) and date(checkout) OR (checkin <= '$checkout' AND checkout >= '$checkin')
                        OR (checkin <= '$checkin' AND checkout >= '$checkout')
                        OR (checkin >= '$checkin' AND checkout <= '$checkout'))");
                        if(mysqli_num_rows($fetch_booked)>0){
                        while($fetch = mysqli_fetch_assoc($fetch_booked)){
                              
                    ?>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                        <a class="cat-item rounded p-4" href="availability.php?id=<?php echo $select_arr[$fetch['name']]['id']; ?>">
                            <img src="uploads/<?php echo $select_arr[$fetch['name']]['image']; ?>" class="img-fluid" style="width:1000px">
                            <h5 class="mb-3"><?php echo $select_arr[$fetch['name']]['type']; ?></h5>
                            <h6 class="mb-3"><?php echo $select_arr[$fetch['name']]['name']; ?></h6>
                            <h6 class="mb-3">₱<?php echo $select_arr[$fetch['name']]['rate']; ?></h6>
                            <p class="mb-0">Description: <?php echo substr($select_arr[$fetch['name']]['description'], 0, 100); ?>...</p>
                        </a>
                        <center>
                        <a href="view_room.php?id=<?php echo $select_arr[$fetch['name']]['id']; ?>" class="btn btn-primary">View Room</a>
                        </center>
                    </div>
                    <?php
                           
                        }
                    }else{
                    echo '<p class="empty" style="color: black; text-align: center;">No Available Room has been found!</p>';
            
                    }
                      
                     ?>
                </div>
            </div>
        </div>
        <!-- Room End -->
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

        <script>
        $(function(){
            var dtToday = new Date();
            
            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate() + 1;
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();
            
            var maxDate = year + '-' + month + '-' + day;
            $('#checkin').attr('min', maxDate);
        });

        $(function(){
            var dtToday = new Date();
            
            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate() + 1 ;
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();

            var maxDate = year + '-' + month + '-' + day;
            $('#checkout').attr('min', maxDate);
        });
        </script>
    <?php require("user_footer.php"); ?>
</body>
</html>
<?php
exit();
}elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
  header("Location: admin/dashboard.php");
}
?>