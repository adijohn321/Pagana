<?php
session_start();

date_default_timezone_set('Asia/Singapore');
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest") {
    $id = $_SESSION['user_id'];
    include_once 'function/dbconnect.php';
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$id'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $firstName = $row['fname'];
    $middleName = $row['mname'];
    $lastName = $row['lname'];
    $Email = $row['email'];
    $Phone = $row['phone'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];
        if ($action == 'cancel') {
            $amId = $_POST['Tid'];
            $sql = "UPDATE `reservation` SET `status` = 'Cancelled' WHERE transaction_id = '$amId'";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['success'] = "RESERVATION has been CANCELLED.";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }
    }


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
        </style>
    </head>

    <body>
        <div class="container-xxl bg-white p-0" style="max-width: 95vw;">
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
                                    User Profile
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
            <section class="py-5 my-5">
                <div class="container" style="max-width: 93vw;">
                    <h1 class="mb-5">Account</h1>
                    <?php
                    if (isset($_SESSION['success'])) { ?>
                        <div class="alert alert-success" id="alert">
                            <?php echo $_SESSION['success']; ?>
                        </div>
                        <?php
                        unset($_SESSION['success']);
                    }
                    ?>
                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert alert-danger" id="alert">
                            <?php echo $_SESSION['error']; ?>
                        </div>
                        <?php
                        unset($_SESSION['error']);
                    }
                    ?>
                    <div class="bg-white shadow rounded-lg d-block d-sm-flex">
                        <div class="profile-tab-nav border-right col-md-2">
                            <div class="p-4">
                                <div class="img-circle text-center mb-3">
                                    <img src="img/user.png" alt="Image" width="100" height="100" class="shadow">
                                </div>
                                <h4 class="text-center">
                                    <?php echo $firstName; ?>
                                    <?php echo $middleName; ?>
                                    <?php echo $lastName; ?>
                                </h4>
                            </div>
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <a class="nav-link active" id="account-tab" data-toggle="pill" href="#account" role="tab"
                                    aria-controls="account" aria-selected="true">
                                    <i class="fa fa-home text-center mr-1"></i>
                                    Profile
                                </a>
                                <a class="nav-link" id="security-tab" data-toggle="pill" href="#security" role="tab"
                                    aria-controls="security" aria-selected="false">
                                    <i class="fa fa-user text-center mr-1"></i>
                                    Reservation History
                                </a>
                                <a class="nav-link" id="security-tab1" data-toggle="pill" href="#security1" role="tab"
                                    aria-controls="security1" aria-selected="false">
                                    <i class="fa fa-user text-center mr-1"></i>
                                    Pending Reservation
                                </a>
                                <a class="nav-link" id="password-tab" data-toggle="pill" href="#password" role="tab"
                                    aria-controls="password" aria-selected="false">
                                    <i class="fa fa-key text-center mr-1"></i>
                                    Change Password
                                </a>
                            </div>
                        </div>
                        <div class="tab-content p-4 p-md-5" id="v-pills-tabContent"
                            style="    width: -webkit-fill-available;">

                            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                <h3 class="mb-4">Reservation History</h3>
                                <div class="row">
                                    <div class="home-content" style="overflow: hidden;">
                                        <div class="tables table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center; width: 5%;">Transaction ID</th>
                                                        <th style="text-align: center;">Room</th>
                                                        <th style="text-align: center;">Amenities</th>
                                                        <th style="text-align: center;">Check-In</th>
                                                        <th style="text-align: center;">Check-Out</th>
                                                        <th style="text-align: center;">Paid</th>
                                                        <th style="text-align: center;">Total</th>
                                                        <th style="text-align: center;">Status</th>
                                                        <th style="text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Fetch rooms data from the database
                                                    include_once("function/dbconnect.php");
                                                    $conn = dbConnect();
                                                    $stmt = $conn->prepare("SELECT `reservation`.`name`, `room_id`, `room`.`id`, `email`,`total_rate`,`phone`,`address`,`checkin`,`checkout`,`amount_paid`,`reservation`.`status` as 'status',`transaction_id` FROM reservation RIGHT JOIN room on room_id=room.name where user_id = '$id' and (checkout < current_date() or reservation.status = 'Cancelled' or reservation.status = 'Rejected') ORDER BY checkout DESC");
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();

                                                    $sn = 0;
                                                    while ($row = $result->fetch_assoc()) {
                                                        $sn++;

                                                        $reservationName = $row['name'];
                                                        $reservationRoom = $row['room_id'];
                                                        $reservationRoomid = $row['id'];
                                                        $reservationEmail = $row['email'];
                                                        $reservationTotal = $row['total_rate'];
                                                        $reservationPhone = $row['phone'];
                                                        $reservationAddress = $row['address'];
                                                        $reservationIn = $row['checkin'];
                                                        $reservationOut = $row['checkout'];
                                                        $reservationAmount = $row['amount_paid'];
                                                        $reservationStatus = $row['status'];
                                                        $reservationTransaction = $row['transaction_id'];

                                                        ?>
                                                        <tr>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php echo $reservationTransaction; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php echo $reservationRoom; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php echo $reservationIn; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php echo $reservationOut; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">₱
                                                                <?php echo $reservationAmount; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">₱
                                                                <?php echo $reservationTotal; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php echo $reservationStatus; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                
                                                                    <a href="availability.php?id=<?php echo $reservationRoomid; ?>"
                                                                        class="btn btn-secondary">Re-Book</a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    $stmt->close();
                                                    $conn->close();
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="security1" role="tabpanel" aria-labelledby="security-tab1">
                                <h3 class="mb-4">Pending Reservations</h3>
                                <div class="row">
                                    <div class="home-content" style="overflow: hidden;">
                                        <div class="tables table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center; width: 5%;">Transaction ID</th>
                                                        <th style="text-align: center;">Room</th>
                                                        <th style="text-align: center;">Amenities</th>
                                                        <th style="text-align: center;">Check-In</th>
                                                        <th style="text-align: center;">Check-Out</th>
                                                        <th style="text-align: center;">Paid</th>
                                                        <th style="text-align: center;">Total</th>
                                                        <th style="text-align: center;">Status</th>
                                                        <th style="text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Fetch rooms data from the database
                                                    include_once("function/dbconnect.php");
                                                    $conn = dbConnect();
                                                    $stmt = $conn->prepare("SELECT `reservation`.`name`, `room_id`, `room`.`id`, `email`,`total_rate`,`phone`,`address`,`checkin`,`checkout`,`amount_paid`,`reservation`.`status` as 'status',`transaction_id` FROM reservation RIGHT JOIN room on room_id=room.name where user_id = '$id' and (checkout> current_date() and reservation.status != 'Cancelled' and reservation.status != 'Rejected') ");
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    $userStatus = "Checked-Out";
                                                    $userStatusin = "Checked-Out";
                                                    $userStatusout = "Checked-Out";
                                                    $userStatusroom = "Checked-Out";
                                                    $userStatustrans = "Checked-Out";


                                                    $cdate = new DateTime();

                                                    $sn = 0;
                                                    while ($row = $result->fetch_assoc()) {
                                                        $sn++;

                                                        $reservationName = $row['name'];
                                                        $reservationRoom = $row['room_id'];
                                                        $reservationRoomid = $row['id'];
                                                        $reservationEmail = $row['email'];
                                                        $reservationTotal = $row['total_rate'];
                                                        $reservationPhone = $row['phone'];
                                                        $reservationAddress = $row['address'];
                                                        $reservationIn = $row['checkin'];
                                                        $reservationOut = $row['checkout'];
                                                        $reservationAmount = $row['amount_paid'];
                                                        $reservationStatus = $row['status'];
                                                        $reservationTransaction = $row['transaction_id'];

                                                        ?>
                                                        <tr>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php echo $reservationTransaction; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php echo $reservationRoom; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php echo $reservationIn; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php echo $reservationOut; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">₱
                                                                <?php echo $reservationAmount; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">₱
                                                                <?php echo $reservationTotal; ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php echo $reservationStatus ?>
                                                            </td>
                                                            <td style="text-align: center; text-transform: capitalize ">
                                                                <?php
                                                                $fdate = $cdate->format('Y-m-d');
                                                                //15:00 is check in time
                                                                $current = date_diff($cdate, new DateTime($reservationIn . ' 15:00'))->format('%R%a days');
                                                                //11:00 is check out time
                                                                if (($current == "-0 days" || date_diff($cdate, new DateTime($reservationIn . ' 11:00'))->format('%R%a') < 0) && $reservationStatus == 'Reserved') {

                                                                    $userStatus = "Checked-In";
                                                                    $userStatusin = new DateTime($reservationIn);
                                                                    $userStatusout = new DateTime($reservationOut);
                                                                    $userStatusroom = $reservationRoom;
                                                                    $userStatustrans = $reservationTransaction;
                                                                    ?>

                                                                    <a href="availability.php?id=<?php echo $reservationRoomid; ?>"
                                                                        class="btn btn-primary">Extend</a>
                                                                <?php } else {
                                                                    ?>
                                                                    <a href="availability.php?id=<?php echo $reservationRoomid; ?>&reservation_id=<?php echo $reservationTransaction; ?>"
                                                                        class="btn btn-primary">Re-Schedule</a>
                                                                    <a class="btn btn-cancel" data-toggle="modal"
                                                                        data-target="#loginModal"
                                                                        onclick="setID('<?php echo $reservationTransaction ?>')">Cancel</a>
                                                                    <?php
                                                                } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    $stmt->close();
                                                    $conn->close();
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="account" role="tabpanel"
                                aria-labelledby="account-tab">
                                <h3 class="mb-4">Account Information</h3>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>First Name:</label>
                                            <h3>
                                                <?php echo $firstName; ?>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Middle Name:</label>
                                            <h3>
                                                <?php echo $middleName; ?>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Last Name:</label>
                                            <h3>
                                                <?php echo $lastName; ?>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email:</label>
                                            <h3>
                                                <?php echo $Email; ?>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone number:</label>
                                            <h3>
                                                <?php echo $Phone; ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <br>


                            </div>

                            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                <form action="" method="post">
                                    <h3 class="mb-4">Password Settings</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Old password</label>
                                                <input type="password" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>New password</label>
                                                <input type="password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Confirm new password</label>
                                                <input type="password" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <?php if ($userStatus == "Checked-In") {
                            ?>

                            <div class="col-md-3">
                                <div class="card-header">
                                    <h3>Status:
                                        <?php echo $userStatus ?>
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <div class="col-md-12">

                                        <div><label style="width: 50%;"><strong>Room Number:</strong> </label><label id="room"
                                                style="width: 50%;text-align: end;">
                                                <?php echo $userStatusroom ?>
                                            </label></div>
                                        <div><label style="width: 50%;"><strong>Check-In Date:</strong> </label><label id="room"
                                                style="width: 50%;text-align: end;">
                                                <?php echo $userStatusin->format('F d, y H:i') ?>
                                            </label></div>
                                        <div><label style="width: 50%;"><strong>Check-Out Date:</strong> </label><label
                                                id="room" style="width: 50%;text-align: end;">
                                                <?php echo $userStatusout->format('(l) F d, y') ?>
                                            </label></div>


                                    </div>
                                </div>

                                <div class="container mt-5">
                                    <div class="alert alert-info">
                                        <h5 class="alert-heading">Important Information</h5>
                                        <p><strong>Check-out Time:</strong> 11:00 AM</p>
                                        <p class="mb-0">Please note that late check-outs may be available upon request, subject
                                            to availability, and additional charges.</p>
                                        <p class="mb-0">Please note that extending your stay is subject to room availability</p>
                                        <a href="availability.php?id=<?php echo $reservationRoomid; ?>"
                                            class="btn btn-primary">Extend Check-In</a>
                                    </div>
                                </div>


                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>

            <!-- Custom Confirmation Modal -->
            <div class="modal" id="loginModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Cancelation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="message">Are you sure you want to cancel this reservation?</p>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data"
                            style="display:block;margin-bottom:10px">
                            <input type="hidden" name="action" value="cancel">
                            <input type="hidden" name="Tid" id="Tid">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                <button type="submit" class="btn btn-danger" id="confirmDelete">Yes</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            </script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('table').DataTable();
                });


                function setID(id) {

                    var inputElement = document.getElementById("Tid");
                    inputElement.value = id;

                }
            </script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

            <?php require("user_footer.php"); ?>

    </body>

    </html>
    <?php
    exit();
} elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
    header("Location: admin/dashboard.php");
}
?>