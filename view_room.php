<?php 
session_start();
$id = $_GET['id'];
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
            background: linear-gradient(rgba(43, 57, 64, .5), rgba(43, 57, 64, .5)), url(img/cubes.png) center center no-repeat;
            background-size: cover;
        }
        .map-container {
            position: relative;
            overflow: hidden;
            padding-top: 56.25%; /* 16:9 aspect ratio (height / width) */
        }

        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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


        <!-- Carousel Start -->
        <div class="container-xxl py-5 bg-dark page-header mb-5">
            <div class="container my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Room Description</h1>
            </div>
        </div>
        <!-- Carousel End -->
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
            $Image = explode(', ', $row['images']);
            $roomName = $row['name'];
            $roomType = $row['type'];
            $roomDescription = $row['description'];
            $roomRate = $row['rate'];
        ?>
        <!-- Room View Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Room View</h1>
                <div class="row g-5 align-items-center">
                    <div class="col-lg-7 wow fadeIn" data-wow-delay="0.1s">
                        <div class="row">
                            <?php foreach ($Image as $images) { ?>
                                <div class="col-6 text-start">
                                    <a href="uploads/<?php echo $images; ?>" data-lightbox="room-images">
                                        <img class="img-fluid" src="uploads/<?php echo $images; ?>" style="width: 85%; margin-top: 15%;">
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-5 wow fadeIn" data-wow-delay="0.5s">
                        <h4 class="mb-4">Room Name</h4>
                        <p class="mb-4"><?php echo $roomName; ?></p>
                        <h4 class="mb-4">Room Type</h4>
                        <p class="mb-4"><?php echo $roomType; ?></p>
                        <h4 class="mb-4">Rate</h4>
                        <p class="mb-4">₱<?php echo $roomRate; ?></p>
                        <h4 class="mb-4">Room Description</h4>
                        <p class="mb-4"><?php echo $roomDescription; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Room View End -->
        <?php } ?>    
        <?php require("user_footer.php"); ?>

</body>
</html>
<?php
exit();
}elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
  header("Location: admin/dashboard.php");
}
?>