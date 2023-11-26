<?php
session_start();

$error_message = "";
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator" ) {
    // Redirect the user to the dashboard or desired page
    header("Location: admin/dashboard.php");
    exit();
    
}elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest") {
    header("Location: index.php");
}
elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "InCharge") {
    header("Location: GIC/dashboard.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    include_once "function/login.php";

    $email = $_POST["email"];
    $password = $_POST["password"];

    if (isLoginLockedOut()) {
        // Handle login lockout here (e.g., show an error message)
        $error_message = "Too many failed login attempts. Please try again in 1 minute.";
    } else {
        $loginResult = loginUser($email, $password);
        if ($loginResult === true && $_SESSION['role'] == 'Administrator') {
            // Redirect the user to the dashboard or desired page
            header("Location: admin/dashboard.php");
            exit();
        }elseif ($loginResult === true && $_SESSION['role'] == 'Guest') {
            // Redirect the user to the dashboard or desired page
            header("Location: index.php");
            exit();
        }elseif ($loginResult === true && $_SESSION['role'] == 'InCharge') {
            // Redirect the user to the dashboard or desired page
            header("Location: GIC/dashboard.php");
            exit();
        } elseif ($loginResult === 'verify') {
            $error_message = "  Please verify your account first.";
        } else {
            // Handle login failure (e.g., show an error message)
            $error_message = "Invalid email or password.";
        }
    }

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
        .forgot-password{
            text-align: right;
            margin: 15px;
        }
        .card-header {
            font-size: 20px;
            font-weight: 800;
        }
        .navbar-light .navbar-nav .nav-link {
            color: white;
            font-weight: 500;
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
                    <a href="rooms.php" class="nav-item nav-link">Rooms</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                    <a href="login.php" class="btn btn-success rounded-0 py-4 px-lg-5 active">Login<i class="fa fa-arrow-right ms-3"></i></a>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->
      
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Oooppps!</div>
                        
                        <h1>Maximum Login attemps reached! Please try again in a minute.</h1>
                        
                        <p class="forgot-password">Don't have an account? <a href="registration.php">Register Now</a></p>
                    </div>
                </div>
            </div>
        </div>
    <?php require("user_footer.php"); ?>
</body>
</html>