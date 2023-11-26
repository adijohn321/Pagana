<?php

// Fetch rooms data from the database
include_once("function/dbconnect.php");
$conn = dbConnect();
session_start();
//
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest") {
    // Redirect the user to the dashboard or desired page
    $id = $_GET['id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];
        if ($action == 'delete') {
            $amId = $_POST['amid'];
            $type = $_POST['type'];
            $message = "";
            if($type=="am"){

                $sql = "DELETE FROM room_amenities WHERE id='$amId'";
                $message = "Amenity";
            }else
            {
                $sql = "DELETE FROM room_features WHERE id='$amId'";
                $message = "Feature";
            }

            if ($conn->query($sql) === TRUE) {
                $_SESSION['success'] =  $message." has been removed.";
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
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"> <!-- Font Awesome -->
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
        
        <section class="home-section">
            <div class="home-content">
                <!-- Rejected tab content here -->
                <div class="tables">
                    <div style="margin-bottom: 20px;">
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Room Information</div>
                                <?php if (isset($_SESSION['success'])) { ?>
                                    <div class="alert alert-success" id="alert"><?php echo $_SESSION['success']; ?></div>
                                <?php
                                    unset($_SESSION['success']);
                                }
                                ?>
                                <?php if (isset($_SESSION['error'])) { ?>
                                    <div class="alert alert-danger" id="alert"><?php echo $_SESSION['error']; ?></div>
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
                                                    <img src="uploads/<?php echo $roomImage; ?>" class="card-img-top" alt="Room Image" style="max-height: 350px;">
                                                    <div class="card-body">
                                                        <h5 class="card-title"><?php echo $roomName ?></h5>
                                                        <p class="card-text"><?php echo $roomDescription ?></p>
                                                    </div>
                                                </div>
                                                <ul class="list-group mt-4">
                                                    <li class="list-group-item"><i class="fas fa-bed"></i> Room Type: <?php echo $roomType ?></li>
                                                    <li class="list-group-item"><i class="fas fa-dollar-sign"></i> Price Rate: $<?php echo number_format((float)$roomRate, 2, '.', '') ?>/night</li>
                                                    <li class="list-group-item"><i class="fas fa-user-friends"></i> Max Occupancy: 2 adults (Static)</li>
                                                    <li class="list-group-item"><i class="fas fa-bed"></i> Bed Type: <?php echo $roomType ?></li>
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
                                                        <i class="fas fa-check"></i> <?php echo $name ?>
                                                        <!-- <i class="fas fa-trash-alt delete-amenity" onclick="setId(<?php echo $amid ?>,'Are you sure you want to delete this feature?','ft')"></i> -->
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
                                                            <i class="fas fa-check"></i> <?php echo $name ?>
                                                            <!-- <i class="fas fa-trash-alt delete-amenity" onclick="setId(<?php echo $amid ?>,'Are you sure you want to delete this amenity?','am')"></i> -->
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


        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    </body>

    </html>
<?php
    exit();
} elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
    header("Location: admin/index.php");
}
?>