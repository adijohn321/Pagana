<?php

session_start();
if (!isset($_SESSION['myArrayOfObjects']) || !is_array($_SESSION['myArrayOfObjects'])) {
    $_SESSION['myArrayOfObjects'] = [];
}
// unset($_SESSION['voucher']);
if (!isset($_SESSION['voucher'])) {
    $_SESSION['voucher'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    include_once "../function/dashboard.php";
    $conn = dbConnect();
    foreach ($_SESSION['myArrayOfObjects'] as $index => $object) {
        $paid = $object->paid;
        $paid = $paid - ($paid * ($_SESSION['voucher'] / 100));
        $sql = "UPDATE `reservation` SET `status` = 'Reserved', `amount_paid` = '$paid' WHERE transaction_id = '$object->id'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "RESERVATION Completed.";
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $_SESSION['myArrayOfObjects'] = [];
        $_SESSION['voucher'] = 0;
        header("Location: checkout.php");
    }
}
if (true) {
    include_once "../function/dashboard.php";
    // Redirect the user to the dashboard or desired page
    ?>
    <!DOCTYPE html>
    <html lang="en" dir="ltr">

    <head>
        <meta charset="UTF-8">
        <title>Pagana Kutawato Hotel</title>
        <link rel="stylesheet" href="../css/admin_style.css">
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .box-link {
                text-align: center;
                margin-top: 10px;
                /* Adjust this value as needed */
            }

            tbody td {
                padding: 5px;
            }

            .items tr:nth-child(even) {
                background-color: #bac5d7;
                color: darkgreen;
            }
        </style>
        <!-- Customized Bootstrap Stylesheet -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="../css/style.css" rel="stylesheet">

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
                    <a href="roomavail.php">
                        <i class='bx bx-hotel'></i>
                        <span class="links_name">Room Booking</span>
                    </a>
                </li>
                <li>
                    <a href="reservations.php">
                        <i class='bx bx-list-ul'></i>
                        <span class="links_name">Reservation</span>
                    </a>
                </li>

                <li>
                    <a href="booking.php">
                        <i class='bx bx-list-ul'></i>
                        <span class="links_name">Booking</span>
                    </a>
                </li>

                <li>
                    <a href="report.php" class="">
                        <i class='bx bx-grid-alt'></i>
                        <span class="links_name">Reports</span>
                    </a>
                </li>

                <li>
                    <a href="checkout.php" class="active">
                        <i class='bx bx-grid-alt'></i>
                        <span class="links_name">Check Out</span>
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
                    <span class="dashboard">Check Out</span>
                </div>
                <div style="text-align: end;">
                    <h3>
                        <?php
                        echo $fullname;
                        ?>
                    </h3>
                    <small>Current User</small>
                </div>

            </nav>


            <div class="home-content">
                <div class="container">

                    <div class="form-group">
                        <label for="tID">Transaction ID: </label>
                        <input type="text" class="form-control" id="tID" name="tID" placeholder="Enter Transaction ID">
                    </div>
                </div>
            </div>
            <div id="objectsContainer" class="container mt-5">
                <?php
                include 'function/add_object.php';
                ?>
            </div>

        </section>


        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
            var $j = jQuery.noConflict();
            $j(document).ready(function () {
                $j("#tID").on("keyup", function (event) {
                    if (event.key === "Enter") {
                        addObject();
                    }
                });
                $j("#discount").on("keyup", function (event) {
                    if (event.key === "Enter") {
                        voucherCheck();
                    }
                });
                
            var inputElement12 = document.getElementById('cash');
            inputElement12.addEventListener('input', function () {

                var inputElement3 = document.getElementById('cashT');
                var change = document.getElementById('change');
                var changeT = document.getElementById('changeT');
                var amount = document.getElementById('gt');
                var submit = document.getElementById('submit');
                if (inputElement12.value.length === 0) {
                    change.value = "₱ 0.00";
                    changeT.textContent = "₱ 0.00";
                    inputElement3.textContent = "- ₱ 0.00";
                    submit.disabled = true;
                    return
                }
                // This function will be called when the input value changes
                // var inputValue = inputElement.value;
                // outputElement.textContent = 'Input value changed to: ' + inputValue;
                change.value = "₱ " + (parseFloat(inputElement12.value) - parseFloat(amount.value.replace("₱", "").replace(/\s+/g, ''))).toFixed(2);
                changeT.textContent = "₱ " + (parseFloat(inputElement12.value) - parseFloat(amount.value.replace("₱", "").replace(/\s+/g, ''))).toFixed(2);
                inputElement3.textContent = "- ₱ " + (parseFloat(inputElement12.value)).toFixed(2);
                if ((parseFloat(amount.value.replace("₱", "").replace(/\s+/g, '')) - parseFloat(inputElement12.value.replace("₱", "").replace(/\s+/g, ''))) > 0) {
                    submit.disabled = true;
                    changeT.textContent = "₱ 0.00";
                    change.value = "- ₱ 0.00";
                } else {
                    submit.disabled = false;
                }
            });
            });

            function addObject() {

                var tID = document.getElementById('tID').value;

                $j.ajax({
                    type: "POST",
                    url: "function/add_object.php",
                    data: {
                        tID: tID,
                    },
                    success: function (response) {
                        $j("#objectsContainer").html(response);
                    }
                });

            }

            function deleteAccount(index) {

                $j.ajax({
                    type: "POST",
                    url: "function/add_object.php",
                    data: {
                        index: index,
                        action: 'deleteAccount',
                    },
                    success: function (response) {
                        $j("#objectsContainer").html(response);
                    }
                });
            }

            function voucherCheck(index) {

                var discount = document.getElementById('discount').value;

                $j.ajax({
                    type: "POST",
                    url: "function/add_object.php",
                    data: {
                        discount: discount,
                        action: 'voucher',
                    },
                    success: function (response) {
                        $j("#objectsContainer").html(response);
                    }
                });
            }

            function removeErrors() {
                setInterval(function () {
                    if (document.getElementById('alert') != null)
                        document.getElementById('alert').style.display = 'none'
                }, 5000);
            }
            removeErrors();
        </script>

    </body>

    </html>
    <?php
    exit();
} elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest") {
    header("Location: ../index.php");
}
?>