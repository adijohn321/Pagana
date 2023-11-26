<?php

session_start();

$user_id = $_SESSION['user_id'];
if (isset($_GET["id"])) {
    $roomId = $_GET["id"];
} else {
    $roomId = "";
}
if (isset($_GET["reservation_id"])) {
    $reservation = $_GET["reservation_id"];
} else {
    $reservation = "";
}

include_once("function/dbconnect.php");
$conn = dbConnect();

$stmt = $conn->prepare("SELECT * FROM room WHERE id = '$roomId'");
$stmt->execute();
$result = $stmt->get_result();

$sn = 0;
if ($row = $result->fetch_assoc()) {
    $sn++;

    $roomId = $row['id'];
    $roomImage = $row['image'];
    $roomName = $row['name'];
    $roomType = $row['type'];
    $roomDescription = $row['description'];
    $roomRate = $row['rate'];
}
$stmt->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //   $dateS = new DateTime($_POST['date1']);
    //   $dateE = new DateTime($_POST['date2']);
    //   if ($dateS < ($dateE)) {

    //     //proceed save to database
    //     $name = $_POST['fname'] . ' ' . $_POST['mname'] . ' ' . $_POST['lname'] . ' ' . $_POST['ename'];
    //     $rate = ($dateS->diff($dateE)->format('%a')) * $roomRate;
    //     $email = $_POST['email'];
    //     $number = $_POST['number'];
    //     $dateE = $dateE->format('y-m-d');
    //     $dateS = $dateS->format('y-m-d');
    //     $ref_no = rand(999999999, 000000000);

    //     $stmt = mysqli_query($conn, "INSERT INTO `reservation` (user_id,room_id, name, email, phone, address, checkin, checkout, amount_paid, total_rate, transaction_id, status, datecreated)
    //          VALUES('$user_id','$roomName', '$name', '$email', '$number', '', '$dateS', '$dateE', '0.00', '$rate', '$ref_no', 'Pending', current_date())");


    //     $_SESSION['success'] = 'Your reservation has been sent. ' . $rate;
    //   } else {
    //     $_SESSION['error'] = 'dates are not the same.';
    //   }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Rate Our Rooms</title>

    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            width: 600px;
            margin: 0 auto;
            flex-grow: 1;
        }

        .rating-form {
            background-color: #f7f7f7;
            padding: 20px;
            border-radius: 10px;
            position: sticky;
            bottom: 0px;
        }

        .rating-label {
            font-weight: bold;
        }

        .stars {
            margin: 10px 0;
        }

        .stars input[type="radio"] {
            display: none;
        }

        .stars label {
            font-size: 24px;
            cursor: pointer;
        }

        .stars label:before {
            content: "\2605";
            color: #ccc;
        }

        .stars input[type="radio"]:checked+label:before {
            color: #f39c12;
        }

        .comment-box {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .comment-box textarea {
            width: 100%;
            max-width: 100%;
            height: 80px;
            margin-top: 10px;
            border-radius: 5px;
            padding: 10px;
            resize: none;
        }

        .submit-button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .previous-comments {
            text-align: left;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            flex-grow: 0;
        }

        .user-comment {
            background-color: #f2f2f2;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: left;
        }

        .timeline-container {
            display: flex;
            align-items: flex-start;
        }

        .room-info {
            flex: 1;
        }

        .timeline {
            position: relative;
            margin-top: 30px;
            margin-bottom: 30px;
            flex: 2;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 2px;
            background: #007bff;
        }

        .timeline-event {
            position: relative;
            margin-bottom: 30px;
            background: #fff;
            border: 2px solid #007bff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            background-color: aqua;
        }

        .timeline-event::before {
            content: '';
            position: absolute;
            top: 5px;
            left: -6px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #007bff;
        }

        .reserved {
            border-color: #dc3545;
            background-color: #f5cccc;
        }

        .reserved .timeline-event::before {
            background: #dc3545;
        }

        .timeline-content {
            position: relative;
            z-index: 1;
        }

        .timeline-content p {
            margin: 0;
            padding: 0;
        }

        .timeline-content p:first-child {
            font-size: 20px;
            font-weight: bold;
        }

        .active1 {
            background-color: #e1fb45;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial;
            margin: 0 auto;

            padding: 20px;
        }

        .heading {
            font-size: 25px;
            margin-right: 25px;
        }

        .fa {
            font-size: 25px;
        }

        .checked {
            color: orange;
        }

        /* Three column layout */
        .side {
            float: left;
            width: 15%;
            margin-top: 10px;
        }

        .middle {
            float: left;
            width: 70%;
            margin-top: 10px;
        }

        /* Place text to the right */
        .right {
            text-align: right;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* The bar container */
        .bar-container {
            width: 100%;
            background-color: #f1f1f1;
            text-align: center;
            color: white;
        }

        /* Individual bars */
        .bar-5 {
            width: 60%;
            height: 18px;
            background-color: #04AA6D;
        }

        .bar-4 {
            width: 30%;
            height: 18px;
            background-color: #2196F3;
        }

        .bar-3 {
            width: 10%;
            height: 18px;
            background-color: #00bcd4;
        }

        .bar-2 {
            width: 4%;
            height: 18px;
            background-color: #ff9800;
        }

        .bar-1 {
            width: 15%;
            height: 18px;
            background-color: #f44336;
        }

        /* Responsive layout - make the columns stack on top of each other instead of next to each other */
        @media (max-width: 400px) {

            .side,
            .middle {
                width: 100%;
            }

            /* Hide the right column on small screens */
            .right {
                display: none;
            }
        }
    </style>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="row col-md-9" style="margin: auto;">
        <div class="col-md-8">
            <div class="card" style="padding:10px; position:sticky;top :0px">
                <h4 style="margin:10px">Room Details</h4>
                <img src="uploads/<?php echo $roomImage ?>" class="card-img-top room-image" alt="' . $room['name'] . '" style="height: 400px;">
                <?php
                // if ($select_arr[$fetch['name']]['status'] == 1) {
                //     echo '<div class="unavailable-indicator">Out of Order</div>';
                // }
                ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $roomName ?></h5>
                    <p class="card-text">Description: <?php echo ($roomDescription); ?></p>
                    <p>Starting from <strong> ₱<?php echo number_format((float)$roomRate, 2, '.', ''); ?></strong> per night (taxes and fees not included). Special rates available for extended stays.</p>
                    <?php
                    // if ($select_arr[$fetch['name']]['status'] == 0) {
                    //     echo '<a href="#" class="btn btn-primary">Reserve</a>';
                    // }
                    ?>
                </div>
                <ul class="list-group mt-4">
                    <h4>Features</h4>
                    <!-- Predefined Amenities with Delete Button -->

                    <?php

                    $i = 0;
                    $stmt = $conn->prepare("SELECT * FROM room_features WHERE room_id = '$roomId'");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $name = $row["description"];

                    ?>
                        <li class="list-group-item">
                            <i class="fas fa-check"></i> <?php echo $name ?>
                        </li>
                    <?php
                        $i++;
                    }
                    if ($i == 0) {
                        echo "N/A";
                    }
                    ?>

                </ul>
                <ul class="list-group mt-4">
                    <h4>Amenities</h4>
                    <p>Guests enjoy access to a range of amenities, including:</p>
                    <!-- Predefined Amenities with Delete Button -->

                    <?php

                    $i = 0;
                    $stmt = $conn->prepare("SELECT * FROM room_amenities WHERE room_id = '$roomId'");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $name = $row["description"];

                    ?>
                        <li class="list-group-item">
                            <i class="fas fa-check"></i> <?php echo $name ?>
                        </li>
                    <?php
                        $i++;
                    }
                    if ($i == 0) {
                        echo "N/A";
                    }
                    ?>

                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <h2>Rate Our Rooms</h2>
            <p>Share your experience by rating our rooms below:</p>

            <div class="previous-comments">
                <div style="margin: 20px;">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

                    <span class="heading">Room Rating</span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star"></span>
                    <p>4.1 average based on 254 reviews.</p>
                    <hr style="border:3px solid #f1f1f1">

                    <div class="row">
                        <div class="side">
                            <div>5 star</div>
                        </div>
                        <div class="middle">
                            <div class="bar-container">
                                <div class="bar-5"></div>
                            </div>
                        </div>
                        <div class="side right">
                            <div>150</div>
                        </div>
                        <div class="side">
                            <div>4 star</div>
                        </div>
                        <div class="middle">
                            <div class="bar-container">
                                <div class="bar-4"></div>
                            </div>
                        </div>
                        <div class="side right">
                            <div>63</div>
                        </div>
                        <div class="side">
                            <div>3 star</div>
                        </div>
                        <div class="middle">
                            <div class="bar-container">
                                <div class="bar-3"></div>
                            </div>
                        </div>
                        <div class="side right">
                            <div>15</div>
                        </div>
                        <div class="side">
                            <div>2 star</div>
                        </div>
                        <div class="middle">
                            <div class="bar-container">
                                <div class="bar-2"></div>
                            </div>
                        </div>
                        <div class="side right">
                            <div>6</div>
                        </div>
                        <div class="side">
                            <div>1 star</div>
                        </div>
                        <div class="middle">
                            <div class="bar-container">
                                <div class="bar-1"></div>
                            </div>
                        </div>
                        <div class="side right">
                            <div>20</div>
                        </div>
                    </div>
                </div>
                <h3>Previous Comments:</h3>
                <div class="user-comment">
                    <p><strong>Rating: 5 stars</strong></p>
                    <p>This room was amazing! I had a great experience.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 4 stars</strong></p>
                    <p>The room was comfortable, but I expected more amenities.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 5 stars</strong></p>
                    <p>This room was amazing! I had a great experience.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 4 stars</strong></p>
                    <p>The room was comfortable, but I expected more amenities.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 5 stars</strong></p>
                    <p>This room was amazing! I had a great experience.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 4 stars</strong></p>
                    <p>The room was comfortable, but I expected more amenities.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 5 stars</strong></p>
                    <p>This room was amazing! I had a great experience.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 4 stars</strong></p>
                    <p>The room was comfortable, but I expected more amenities.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 5 stars</strong></p>
                    <p>This room was amazing! I had a great experience.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 4 stars</strong></p>
                    <p>The room was comfortable, but I expected more amenities.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 5 stars</strong></p>
                    <p>This room was amazing! I had a great experience.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 4 stars</strong></p>
                    <p>The room was comfortable, but I expected more amenities.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 5 stars</strong></p>
                    <p>This room was amazing! I had a great experience.</p>
                </div>
                <div class="user-comment">
                    <p><strong>Rating: 4 stars</strong></p>
                    <p>The room was comfortable, but I expected more amenities.</p>
                </div>
                <!-- Add more previous comments here -->
            </div>
            <div class="rating-form">
                <form action="submit_rating.php" method="post">
                    <div class="rating-label">Rate the room:</div>
                    <div class="stars">
                        <input type="radio" id="star5" name="rating" value="5" />
                        <label for="star5">5 stars</label>
                        <input type="radio" id="star4" name="rating" value="4" />
                        <label for="star4">4 stars</label>
                        <input type="radio" id="star3" name="rating" value="3" />
                        <label for="star3">3 stars</label>
                        <input type="radio" id="star2" name="rating" value="2" />
                        <label for="star2">2 stars</label>
                        <input type="radio" id="star1" name="rating" value="1" />
                        <label for="star1">1 star</label>
                    </div>
                    <div class="comment-box">
                        <textarea id="comments" name="comments" placeholder="Type your comments here..."></textarea>
                        <input type="submit" class="submit-button" value="Submit Rating">
                    </div>
                </form>
            </div>
        </div>

    </div>

</body>

</html>