<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest" || !isset($_SESSION['user_id'])) {

    $name = '';
    $email = '';
    $subject = '';
    $message = '';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include_once "function/contact.php";

        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        if(insertContact($name, $email, $subject, $message)){
            $_SESSION['success'] = 'Your message has been sent.';
        }
        else{
            $_SESSION['error'] = 'Failed to send you message.';
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
        .navbar-light .navbar-nav .nav-link {
            color: white;
            font-weight: 500;
        }
        .page-header {
            background: linear-gradient(rgba(43, 57, 64, .5), rgba(43, 57, 64, .5)), url(img/pagana2.jpg) center center no-repeat;
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
                    <a href="rooms.php" class="nav-item nav-link">Rooms</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
                    <a href="contact.php" class="nav-item nav-link active">Contact</a>
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
                <h1 class="display-3 text-white mb-3 animated slideInDown">Contact</h1>
            </div>
        </div>
        <!-- Header End -->


        <!-- Contact Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Contact For Any Query</h1>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="row gy-4">
                            <div class="col-md-4 wow fadeIn" data-wow-delay="0.1s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-map-marker-alt text-primary"></i>
                                    </div>
                                    <span>Rufo Mañara St., RH XI, Cotabato City, near Grecco Gas. Station.</span>
                                </div>
                            </div>
                            <div class="col-md-4 wow fadeIn" data-wow-delay="0.3s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-envelope-open text-primary"></i>
                                    </div>
                                    <span>paganakutawato@gmail.com</span>
                                </div>
                            </div>
                            <div class="col-md-4 wow fadeIn" data-wow-delay="0.5s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-phone-alt text-primary"></i>
                                    </div>
                                    <span>(064) 552 0592</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15833.29941928197!2d124.2344376!3d7.2037293!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3256398f3c4e02ed%3A0xc6b05a5a5cbdafea!2sPagana%20Kutawato!5e0!3m2!1sen!2sph!4v1688297075418!5m2!1sen!2sph" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="wow fadeInUp" data-wow-delay="0.5s">
                            <p class="mb-4">If you have any related concern about the services, rooms, and website we provided for you can you contact us using the website or our telephone number.
                                  </a></p>
                            <form action="" method="POST">
                            <?php if (isset($_SESSION['success'])) { ?>
                                <div class="alert alert-success"><?php echo $_SESSION['success']; ?></div>
                                <?php
                                unset($_SESSION['success']);
                                }
                            ?>
                            <?php if (isset($_SESSION['error'])) { ?>
                                <div class="alert alert-danger"><?php echo $_SESSION['error']; ?></div>
                                <?php
                                unset($_SESSION['error']);
                                }
                            ?>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $name; ?>" placeholder="Your Name">
                                            <label for="name">Your Name</label>
                                            <?php if (isset($_SESSION['errors']['name'])) { ?>
                                                <div id="name-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['name']; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>" placeholder="Your Email">
                                            <label for="email">Your Email</label>
                                            <?php if (isset($_SESSION['errors']['email'])) { ?>
                                                <div id="email-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['email']; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="subject" id="subject" value="<?php echo $subject; ?>" placeholder="Subject">
                                            <label for="subject">Subject</label>
                                            <?php if (isset($_SESSION['errors']['subject'])) { ?>
                                                <div id="subject-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['subject']; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a message here" name="message" id="message" style="height: 150px"><?php echo $message; ?></textarea>
                                            <label for="message">Message</label>
                                            <?php if (isset($_SESSION['errors']['message'])) { ?>
                                                <div id="message-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['message']; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php unset($_SESSION['errors']); ?>
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
        <!-- Contact End -->
    <script>
    // Function to remove error messages after 10 seconds
    function removeErrors() {
        setTimeout(function() {
            document.getElementById('name-error').style.display = 'none';
            document.getElementById('message-error').style.display = 'none';
            document.getElementById('subject-error').style.display = 'none';
            document.getElementById('email-error').style.display = 'none';
        }, 10000);
    }

    removeErrors();
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <?php require("user_footer.php"); ?>
</body>
</html>
<?php
exit();
}elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
  header("Location: admin/dashboard.php");
}
?>