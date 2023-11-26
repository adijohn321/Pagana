<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "function/registration.php";

    $fname = $_POST["fname"];
    $mname = $_POST["mname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = "Guest";



    // For better security, consider using password_hash() to hash the password before storing it in the database.
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (registerUser($fname, $mname, $lname, $email, $phone, $password, $confirm_password, $role)) {
        // Registration successful, you can redirect to a success page or perform any other actions.
        $_SESSION['success'] = "Registration Successful.";
    } else {
        // Registration failed, you can display an error message or redirect to an error page.
        $_SESSION['error'] = "Registration Failed.";
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
        .forgot-password {
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
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
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
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon" style="background-color: white;"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="rooms.php" class="nav-item nav-link">Rooms</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                    <a href="login.php" class="btn btn-success rounded-0 py-4 px-lg-5 active">Login<i
                            class="fa fa-arrow-right ms-3"></i></a>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Registration</div>
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
                        <div class="card-body">
                            <form action="registration.php" method="POST" onsubmit="return validateForm()">
                                <div class="mb-3">
                                    <label for="username" class="form-label">First Name <span
                                            style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="firstname" name="fname" required
                                        value="<?php echo isset($_SESSION['errors']['fname']) ? '' : (isset($_POST['fname']) ? $_POST['fname'] : ''); ?>">
                                    <?php if (isset($_SESSION['errors']['fname'])) { ?>
                                        <div id="fname-error" style="color: red; font-size: 14px;">
                                            <?php echo $_SESSION['errors']['fname']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Middle Initial</label>
                                    <input type="text" class="form-control" id="middlename" name="mname"
                                        value="<?php echo isset($_SESSION['errors']['mname']) ? '' : (isset($_POST['mname']) ? $_POST['mname'] : ''); ?>">
                                    <?php if (isset($_SESSION['errors']['mname'])) { ?>
                                        <div id="mname-error" style="color: red; font-size: 14px;">
                                            <?php echo $_SESSION['errors']['mname']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Last Name <span
                                            style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="lastname" name="lname" required
                                        value="<?php echo isset($_SESSION['errors']['lname']) ? '' : (isset($_POST['lname']) ? $_POST['lname'] : ''); ?>">
                                    <?php if (isset($_SESSION['errors']['lname'])) { ?>
                                        <div id="lname-error" style="color: red; font-size: 14px;">
                                            <?php echo $_SESSION['errors']['lname']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span
                                            style="color: red;">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                        value="<?php echo isset($_SESSION['errors']['email']) ? '' : (isset($_POST['email']) ? $_POST['email'] : ''); ?>">
                                    <?php if (isset($_SESSION['errors']['email'])) { ?>
                                        <div id="email-error" style="color: red; font-size: 14px;">
                                            <?php echo $_SESSION['errors']['email']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone number <span
                                            style="color: red;">*</span></label>
                                    <input type="number" class="form-control" id="phone" name="phone" required
                                        value="<?php echo isset($_SESSION['errors']['phone']) ? '' : (isset($_POST['phone']) ? $_POST['phone'] : ''); ?>">
                                    <?php if (isset($_SESSION['errors']['phone'])) { ?>
                                        <div id="phone-error" style="color: red; font-size: 14px;">
                                            <?php echo $_SESSION['errors']['phone']; ?>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span
                                            style="color: red;">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <?php if (isset($_SESSION['errors']['password'])) { ?>
                                        <div id="password-error" style="color: red; font-size: 14px;">
                                            <?php echo $_SESSION['errors']['password']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password <span
                                            style="color: red;">*</span></label>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" required>
                                    <?php if (isset($_SESSION['errors']['match'])) { ?>
                                        <div id="match-error" style="color: red; font-size: 14px;">
                                            <?php echo $_SESSION['errors']['match']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php unset($_SESSION['errors']); ?>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        // Function to remove error messages after 10 seconds
        function removeErrors() {
            setTimeout(function () {
                document.getElementById('fname-error').style.display = 'none';
                document.getElementById('mname-error').style.display = 'none';
                document.getElementById('lname-error').style.display = 'none';
                document.getElementById('email-error').style.display = 'none';
                document.getElementById('phone-error').style.display = 'none';
                document.getElementById('password-error').style.display = 'none';
                document.getElementById('match-error').style.display = 'none';
            }, 10000);
        }

        removeErrors();
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php require("user_footer.php"); ?>

</body>

</html>