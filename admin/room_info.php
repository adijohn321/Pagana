<?php

// Fetch rooms data from the database
include_once("../function/dbconnect.php");
$conn = dbConnect();
session_start();
//
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
    // Redirect the user to the dashboard or desired page
    $id = $_GET['id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];
        if ($action == 'delete') {
            $amId = $_POST['amid'];
            $type = $_POST['type'];
            $message = "";
            if ($type == "am") {

                $sql = "DELETE FROM room_amenities WHERE id='$amId'";
                $message = "Amenity";
            } else {
                $sql = "DELETE FROM room_features WHERE id='$amId'";
                $message = "Feature";
            }

            if ($conn->query($sql) === TRUE) {
                $_SESSION['success'] = $message . " has been removed.";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        } else {
            if ($action == "am") {
                $name = $_POST["name"];
                //$desc = $_POST["desc"];
                $stmt = $conn->prepare("INSERT INTO room_amenities (`id`, `room_id`, `description`, `count`, `status`) VALUES ( NULL, ?, ?, '1', '1')");
                $stmt->bind_param("ss", $id, $name);
                if ($stmt->execute()) {
                    // Insert successful
                    $stmt->close();
                } else {
                    // Insert failed
                    $stmt->close();
                }
                $_SESSION['success'] = $name . " has been added to amenities";
            } else {
                $name = $_POST["name"];
                //$desc = $_POST["desc"];
                $stmt = $conn->prepare("INSERT INTO room_features (`id`, `room_id`, `description`, `status`) VALUES ( NULL, ?, ?, '1')");
                $stmt->bind_param("ss", $id, $name);
                if ($stmt->execute()) {
                    // Insert successful
                    $stmt->close();
                } else {
                    // Insert failed
                    $stmt->close();
                }
                $_SESSION['success'] = $name . " has been added to room features";
            }
        }
    }
    $id = $_GET['id'];
    ?>

    <!DOCTYPE html>
    <html lang="en" dir="ltr">

    <head>
        <meta charset="UTF-8">
        <title>Pagana Kutawato Hotel</title>
        <link rel="stylesheet" href="../css/admin_style.css">
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css"
            rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        <!-- Font Awesome -->
        <style>
            .card-header {
                font-size: 20px;
                font-weight: 800;
            }

            .img-fluid {
                height: -300%;
            }

            .delete-amenity {
                float: right;
                color: #dc3545;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <div class="sidebar">
            <div class="logo-details">
                <img src="../img/Pagana_logo.png" width="40" height="40" style="margin-left: 10px;" alt="">
                <span class="logo_name">Pagana Kutawato</span>
            </div>
            <ul class="nav-links">
                <li>
                    <a href="dashboard.php">
                        <i class='bx bx-grid-alt'></i>
                        <span class="links_name">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="user.php">
                        <i class='bx bx-user'></i>
                        <span class="links_name">Guests</span>
                    </a>
                </li>

                <li>
                    <a href="guests.php" class="">
                        <i class='bx bx-user'></i>
                        <span class="links_name">Users</span>
                    </a>
                </li>
                <li>
                    <a href="room.php" class="active">
                        <i class='bx bx-hotel'></i>
                        <span class="links_name">Room</span>
                    </a>
                </li>
                <li>
                    <a href="roomavail.php">
                        <i class='bx bx-hotel'></i>
                        <span class="links_name">Room Booking</span>
                    </a>
                </li>
                <li>
                    <a href="amenities.php">
                        <i class='bx bx-list-ul'></i>
                        <span class="links_name">Amenities</span>
                    </a>
                </li>
                <li>
                    <a href="reservations.php">
                        <i class='bx bx-list-ul'></i>
                        <span class="links_name">Reservation</span>
                    </a>
                </li>
                <li>
                    <a href="reports.php">
                        <i class='bx bx-list-ul'></i>
                        <span class="links_name">Reports</span>
                    </a>
                </li>
                <li>
                    <a href="help_desk.php">
                        <i class='bx bx-message'></i>
                        <span class="links_name">Help Desk</span>
                    </a>
                </li>
                <li>
                    <a href="settings.php">
                        <i class='bx bx-cog'></i>
                        <span class="links_name">Setting</span>
                    </a>
                </li>
                <li class="log_out">
                    <a href="logout.php">
                        <i class='bx bx-log-out'></i>
                        <span class="links_name">Log out</span>
                    </a>
                </li>
            </ul>
        </div>
        <section class="home-section">
            <nav>
                <div class="sidebar-button">
                    <i class='bx bx-menu sidebarBtn'></i>
                    <span class="dashboard">Room</span>
                </div>
            </nav>

            <div class="home-content">
                <!-- Rejected tab content here -->
                <div class="tables">

                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Room Information</div>
                                <?php if (isset($_SESSION['success'])) { ?>
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
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM room WHERE id = '$id'");
                                $stmt->execute();
                                $result = $stmt->get_result();

                                $sn = 0;
                                if ($row = $result->fetch_assoc()) {
                                    $sn++;

                                    $roomId = $row['id'];
                                    $status = $row['status'];
                                    $roomImage = $row['image'];
                                    $roomName = $row['name'];
                                    $roomType = $row['type'];
                                    $roomDescription = $row['description'];
                                    $roomRate = $row['rate'];

                                    ?>
                                    <div class="container">
                                        <h1 class="mt-4">Room Information</h1>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <img src="../uploads/<?php echo $roomImage; ?>" class="card-img-top"
                                                        alt="Room Image" style="max-height: 350px;">
                                                    <div class="card-body">
                                                        <h5 class="card-title">
                                                            <?php echo $roomName ?>
                                                        </h5>
                                                        <p class="card-text">
                                                            <?php echo $roomDescription ?>
                                                        </p>
                                                        <a href="edit_room.php?id=<?php echo $id ?>"><button
                                                                class="btn btn-primary">Edit Room Details</button></a>
                                                        <?php if ($status === 0) { ?>
                                                            <a href="outoforder.php?id=<?php echo $roomId; ?>&status=1"
                                                                class="btn btn-danger">Out of order</a>
                                                        <?php } else { ?>
                                                            <a href="outoforder.php?id=<?php echo $roomId; ?>&status=0"
                                                                class="btn btn-danger">Back in Business</a>

                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <ul class="list-group mt-4">
                                                    <li class="list-group-item"><i class="fas fa-bed"></i> Room Type:
                                                        <?php echo $roomType ?>
                                                    </li>
                                                    <li class="list-group-item"><i class="fas fa-dollar-sign"></i> Price Rate: $
                                                        <?php echo number_format((float) $roomRate, 2, '.', '') ?>/night
                                                    </li>
                                                    <li class="list-group-item"><i class="fas fa-user-friends"></i> Max
                                                        Occupancy: 2 adults (Static)</li>
                                                    <li class="list-group-item"><i class="fas fa-bed"></i> Bed Type:
                                                        <?php echo $roomType ?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">

                                                <ul class="list-group mt-4">
                                                    <h4>Room Features</h4>
                                                    <!-- Predefined Amenities with Delete Button -->

                                                    <?php
                                                    }
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
                                                                            <i class="fas fa-trash-alt delete-amenity"
                                                                                onclick="setId(<?php echo $amid ?>,'Are you sure you want to delete this feature?','ft')"></i>
                                                                        </li>
                                                                        <?php
                                                                        $i++;
                                                    }
                                                    if ($i == 0) {
                                                        echo "N/A";
                                                    }
                                                    ?>

                                            </ul>
                                            <div class="text-center mt-4">
                                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                                    data-target="#loginModal" onclick="setAction('ft')">Add Room
                                                    Features</button>
                                            </div>

                                            <ul class="list-group mt-4">
                                                <h4>Amenities</h4>
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
                                                        <i class="fas fa-trash-alt delete-amenity"
                                                            onclick="setId(<?php echo $amid ?>,'Are you sure you want to delete this amenity?','am')"></i>
                                                    </li>
                                                    <?php
                                                    $i++;
                                                }
                                                if ($i == 0) {
                                                    echo "N/A";
                                                }
                                                ?>

                                            </ul>
                                            <div class="text-center mt-4">
                                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                                    data-target="#loginModal" onclick="setAction('am')">Add
                                                    Amenities</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>


        <!-- Custom Confirmation Modal -->
        <div class="modal" id="deleteAmenityModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="message">Are you sure you want to delete this amenity?</p>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data" style="display:block;margin-bottom:10px">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="type" id="type">
                        <input type="hidden" name="amid" id="amid">
                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger" id="confirmDelete">Delete</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


        <!-- Start Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width:900px;width:900px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Add Room Amenities/Features</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        <main class="main-content" style="    width: -webkit-fill-available;">
                            <div class="container mt-4">
                                <form action="" method="post" enctype="multipart/form-data"
                                    style="display:block;margin-bottom:10px">
                                    <input type="hidden" name="action" value="add" id="action">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="form-group">
                                                <label for="name">Name</label>

                                                <input type="text" class="form-control" id="name" name="name" required>
                                            </div>
                                        </div>


                                    </div>

                                    <div style="text-align:center">
                                        <button type="submit" class="btn"
                                            style="color: #fff;background-color: #0d6efd;border-color: #0d6efd;border-radius:5px;width:300px">Add</button>

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



        <script>
            let sidebar = document.querySelector(".sidebar");
            let sidebarBtn = document.querySelector(".sidebarBtn");
            sidebarBtn.onclick = function () {
                sidebar.classList.toggle("active");
                if (sidebar.classList.contains("active")) {
                    sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
                } else
                    sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
            }
        </script>
        <script>
            // Delete amenity
            let amenityToDelete;

            $('.delete-amenity').click(function () {
                amenityToDelete = $(this).data('amenity');
                $('#deleteAmenityModal').modal('show');
            });

            $('#confirmDelete').click(function () {
                if (amenityToDelete) {
                    const amenityListItem = $(`.delete-amenity[data-amenity="${amenityToDelete}"]`).closest('li');
                    amenityListItem.remove();
                    $('#deleteAmenityModal').modal('hide');
                }
            });
        </script>
        <script>
            // Function to remove error messages after 10 seconds
            function removeErrors() {
                setTimeout(function () {
                    document.getElementById('alert').style.display = 'none';
                    document.getElementById('name-error').style.display = 'none';
                    document.getElementById('description-error').style.display = 'none';
                    document.getElementById('type-error').style.display = 'none';
                    document.getElementById('rate-error').style.display = 'none';
                    document.getElementById('image-error').style.display = 'none';
                    document.getElementById('images-error').style.display = 'none';
                }, 3000);
            }

            removeErrors();

            function setId(id, message, type) {

                var inputElement = document.getElementById("amid");
                var ty = document.getElementById("type");
                var mess = document.getElementById("message");
                inputElement.value = id;
                ty.value = type;
                mess.textContent = message;

            }

            function setAction(id) {

                var inputElement = document.getElementById("action");
                inputElement.value = id;

            }

            function goBack() {
                window.history.back(); // This will navigate back to the previous page in the browser's history.
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    </body>

    </html>
    <?php
    exit();
} elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest") {
    header("Location: ../index.php");
}
?>