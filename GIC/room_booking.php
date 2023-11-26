<?php
session_start();
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
if ($user_id == false || $role != "InCharge") {
    header('Location:../login.php');
    exit();
}
$id = $_GET['id'];
$Tid = $_GET['id'];
$amount = $_GET['total'];
$rid = $_GET['rid'];
$name = $_GET['name'];
$email = $_GET['email'];
$phone = $_GET['phone'];
$address = $_GET['address'];
$checkin = $_GET['checkin'];
$checkout = $_GET['checkout'];
$calc_days = abs(strtotime($_GET['checkout']) - strtotime($_GET['checkin']));
$calc_days = floor($calc_days / (60 * 60 * 24));
$conn = new mysqli('localhost', 'root', '', 'paganadb');

if (isset($_POST['submit'])) {
    $id = $_POST['rid'];
    $rate = $_POST['rate1'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $rate = trim(str_replace('₱', '', $rate));

    $stmt = $conn->prepare("UPDATE `reservation` SET status = 'Reserved', amount_paid = '$rate' WHERE id = '$id'");
    $stmt->execute();
    $_SESSION['success'] = 'Successfully been updated.';

    if ($stmt->execute()) {
        // Insert successful
        $stmt->close();
        $conn->close();

        header('Location:/pagana/gic/reservations.php');
        exit();
    } else {
        // Insert failed
        $stmt->close();
        $conn->close();
        return false;
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

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .navbar-light .navbar-nav .nav-link {
            color: white;
            font-weight: 500;
        }

        .page-header {
            background: linear-gradient(rgba(43, 57, 64, .5), rgba(43, 57, 64, .5)), url(img/pagana2.jpg) center center no-repeat;
            background-size: cover;
        }

        .container-xxl {
            padding: 0;
        }

        .room-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px;
        }

        .reservation-form {
            max-width: 400px;
            margin-left: auto;
        }

        label {
            font-size: 0.75rem;
        }
    </style>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>

<body>

    <!-- Custom Modal -->
    <div class="modal" id="info" tabindex="-1" role="dialog">
        <div style="margin:10%" role="document" class="mt-2 mb-2">
            <div class="modal-content card mt-4">
                <div class="modal-header">
                    <h2>Room Information</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <main class="main-content" style="    width: -webkit-fill-available;">
                            <div class="col-md-12">

                                <h4>Room Information</h4>
                                <div class="room-details" style="margin:0">
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
                                    $reservationId;
                                    $stmt = $conn->prepare("SELECT * FROM room where id = '$id' or name = '$id'");
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($row = $result->fetch_assoc()) {

                                        $roomId = $row['id'];
                                        $roomImage = $row['image'];
                                        $roomName = $row['name'];
                                        $roomType = $row['type'];
                                        $roomDescription = $row['description'];
                                        $roomRate = $row['rate'];
                                        $newRate = $roomRate * $calc_days;
                                        ?>
                                        <div class="card mb-4">
                                            <img src="../uploads/<?php echo $roomImage; ?>" style="max-height: 400px;"
                                                class="card-img-top" alt="<?php echo $roomName; ?>">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <?php echo $roomName; ?>
                                                </h5>
                                                <p class="card-text"><strong>Room Type:</strong>
                                                    <?php echo $roomType; ?>
                                                </p>
                                                <p class="card-text"><strong>Rate: ₱</strong>
                                                    <?php echo $roomRate; ?>
                                                </p>
                                                <hr>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">Description:
                                                        <?php echo $roomDescription; ?>
                                                    </li>
                                                </ul>
                                                <hr>

                                                <?php
                                    }
                                    ?>

                                            <ul class="list-group mt-4">
                                                <h4>Features</h4>
                                                <!-- Predefined Amenities with Delete Button -->

                                                <?php

                                                $i = 0;
                                                $stmt = $conn->prepare("SELECT * FROM room_features WHERE room_id = '$roomId'");
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                while ($row = $result->fetch_assoc()) {
                                                    $desc = $row["description"];

                                                    ?>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-check"></i>
                                                        <?php echo $desc ?>
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
                                                    $desc = $row["description"];

                                                    ?>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-check"></i>
                                                        <?php echo $desc ?>
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
                                </div>
                            </div>

                        </main>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <!-- Room Reservation Start -->
    <div class="container">
        <div class="row" style="    margin-top: 25px;">
            <h2 style="text-align:center">Book Reservation</h2>

            <div class="col-md-12">

                <div class="" style="margin:0">

                    <form action="" method="POST" onsubmit="return validateForm()" style="width:100%">

                        <input type="text" class="form-control" id="rid" name="rid" value="<?php echo $rid; ?>" hidden>
                        <input type="hidden" name="room" value="<?php echo $roomName; ?>">
                        <input type="hidden" name="amenities1" id="amenities1">
                        <input type="hidden" name="amenities2" id="amenities2">


                        <br>
                        <h4>Reservation Summary</h4>

                        <a data-toggle="modal" data-target="#info" style="cursor: pointer;">
                            <i class="fas fa-info"></i> Room Info
                        </a>
                        <div class="row">
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="checkin">Check-In</label>
                                    <input type="date" class="form-control" id="checkin" name="checkin" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="checkout">Check-Out</label>
                                    <input type="date" class="form-control" id="checkout" name="checkout" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="checkin">No. of Days</label>
                                    <input type="text" class="form-control" id="checkin" name="checkin"
                                        value="<?php echo $calc_days; ?>" readonly>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="totalAmount">Total Amount: </label><input type="text" id="totalAmount"
                                class="form-control" name="rate"
                                value="₱ <?php echo number_format((float) $amount, 2, '.', ''); ?>" readonly>
                        </div>
                        <input type="text" id="rate1" name="rate1" hidden>
                        <div class="form-group">
                            <label for="voucher">Discount Voucher: </label><input type="text" id="voucher"
                                class="form-control" name="voucher">
                        </div>

                        <div class="row">
                            <div class="col-md-6">


                                <div class="form-group">
                                    <label for="cash">Cash Tendered: </label><input type="number" name="cash" id="cash"
                                        step="any" class="form-control" name="cash"
                                        style="font-weight: 900;font-size: 3rem;">
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="chage">Change</label>
                                    <input type="text" class="form-control" id="change" name="change" readonly>
                                </div>
                            </div>


                        </div>
                        <div class="container mt-2">
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="alert-heading">Client Information</h5>
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td width="15%">
                                                        <label style="width: 50%;"><strong>Name:</strong> </label>
                                                    </td>
                                                    <td width="90%"> <label>
                                                            <?php echo $name; ?>
                                                        </label></td>
                                                </tr>
                                                <tr>
                                                    <td width="15%">
                                                        <label style="width: 50%;"><strong>Address:</strong> </label>
                                                    </td>
                                                    <td width="90%"> <label>
                                                            <?php echo $address; ?>
                                                        </label></td>
                                                </tr>
                                                <tr>
                                                    <td width="15%">
                                                        <label style="width: 50%;"><strong>Phone:</strong> </label>
                                                    </td>
                                                    <td width="90%"> <label>
                                                            <?php echo $phone; ?>
                                                        </label></td>
                                                </tr>
                                                <tr>
                                                    <td width="15%">
                                                        <label style="width: 50%;"><strong>Email:</strong> </label>
                                                    </td>
                                                    <td width="90%"> <label>
                                                            <?php echo $email; ?>
                                                        </label></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="alert-heading">Transaction Details</h5>
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td width="40%">
                                                        <label><strong>Transaction ID:</strong> </label>
                                                    </td>
                                                    <td width="60%"> <label>
                                                            <?php echo $rid; ?>
                                                        </label></td>
                                                </tr>
                                                <tr>
                                                    <td width="25%">
                                                        <label><strong>Room Name:</strong> </label>
                                                    </td>
                                                    <td width="75%"> <label>
                                                            <?php echo $roomName; ?>
                                                        </label></td>
                                                </tr>
                                                <tr>
                                                    <td width="25%">
                                                        <label><strong>Check-In:</strong> </label>
                                                    </td>
                                                    <td width="75%"> <label>
                                                            <?php echo $checkin; ?>
                                                        </label></td>
                                                </tr>
                                                <tr>
                                                    <td width="25%">
                                                        <label><strong>Check-Out:</strong> </label>
                                                    </td>
                                                    <td width="75%"> <label>
                                                            <?php echo $checkout; ?>
                                                        </label></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="container mt-5">
                            <div class="alert alert-info">

                                <h5 class="alert-heading">Payment Summary</h5>
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width: 50%;">Transaction ID</td>
                                            <td style="width: 10%;">No. of Nights</td>
                                            <td style="width: 20%;text-align:right;">Rate</td>
                                            <td style="width: 20%; text-align:right;">Total</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50%;"><?php echo $rid ?></td>
                                            <td style="width: 10%;"><label
                                        id="night" style="width: 50%;text-align: end;">x 5</label></td>
                                            <td style="width: 20%;text-align:right;"><label id="roomRate"
                                        style="width: 50%;text-align: end;">500.00</label></td>
                                            <td style="width: 20%; text-align:right;"><label id="total"
                                        style="width: 50%;text-align: end;">2500.00</label></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <div><label style="width: 50%;"><strong>Total Cost:</strong> </label><label id="Gtotal"
                                        style="width: 50%;text-align: end;">2500.00</label></div>
                                <div style="display: none;" id="disDIV"><label style="width: 50%;"><strong>Extended stay
                                            discount (3%):</strong> </label><label id="discount"
                                        style="width: 50%;text-align: end;">2500.00</label></div>
                                <div style="display: none;" id="disDIV2"><label style="width: 50%;"><strong>Voucher
                                            discount (3%):</strong> </label><label id="discount"
                                        style="width: 50%;text-align: end;">2500.00</label></div>
                                <div><label style="width: 50%;"><strong>Tax (12%):</strong> </label><label id="tax"
                                        style="width: 50%;text-align: end;">+ 300.00</label></div>
                                <hr>

                                <div><label style="width: 50%;"><strong>Grand total:</strong> </label><label id="gt"
                                        style="width: 50%;text-align: end;">2800.00</label></div>
                                <div><label style="width: 50%;"><strong>Cash:</strong> </label><label id="cashT"
                                        style="width: 50%;text-align: end;">0.00</label></div>
                                <hr>
                                <div><label style="width: 50%;"><strong>Change:</strong> </label><label id="changeT"
                                        style="width: 50%;text-align: end;">0.00</label></div>
                            </div>
                        </div>


                        <br>
                        <button type="submit" name="submit" id="submit" class="form-control btn-secondary"
                            disabled>Book</button>
                        <a href="roomavail.php">Go Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Reservation End -->
    <script>

    </script>
    <script>
        function validateForm() {
            var emailInput = document.getElementById('email');
            var email = emailInput.value.trim().toLowerCase();

            // Extract domain from email
            var domain = email.split('@')[1];

            // Define allowed domains
            var allowedDomains = ['yahoo.com', 'gmail.com', 'outlook.com'];

            // Check if the domain is in the allowed domains list
            if (allowedDomains.indexOf(domain) === -1) {
                alert('Please enter a valid email address with a Yahoo, Google, or Outlook domain.');
                emailInput.focus();
                return false;
            }

            return true;
        }
    </script>
    <script type="text/javascript">
        $('select').on('change', function () {
            $('option').prop('disabled', false); //reset all the disabled options on every change event
            $('select').each(function () { //loop through all the select elements
                var val = this.value;
                $('select').not(this).find('option').filter(function () { //filter option elements having value as selected option
                    return this.value === val;
                }).prop('disabled', true); //disable those option elements
            });
        }).change(); //trihgger change handler initially!
    </script>

    <script>
        function amenities1() {
            var am1 = document.getElementById("am1");
            var text1 = am1.options[am1.selectedIndex].text;
            document.getElementById("amenities1").value = text1;
        }
    </script>

    <script>
        function amenities2() {
            var am1 = document.getElementById("am2");
            var text1 = am1.options[am1.selectedIndex].text;
            document.getElementById("amenities2").value = text1;
        }
    </script>

    <script>
        function amenities3() {
            var am1 = document.getElementById("am3");
            var text1 = am1.options[am1.selectedIndex].text;
            document.getElementById("amenities3").value = text1;
        }

        var inputElement12 = document.getElementById('cash');
        var inputElement3 = document.getElementById('cashT');
        var change = document.getElementById('change');
        var changeT = document.getElementById('changeT');
        var amount = document.getElementById('totalAmount');
        var submit = document.getElementById('submit');
        inputElement12.addEventListener('input', function () {
            if (inputElement12.value.length === 0) {
                change.value = "₱ 0.00";
                changeT.textContent = "₱ 0.00";
                inputElement3.textContent = "₱ 0.00";
                submit.disabled = true;
                return
            }
            // This function will be called when the input value changes
            // var inputValue = inputElement.value;
            // outputElement.textContent = 'Input value changed to: ' + inputValue;
            change.value = "₱ " + (parseFloat(inputElement12.value) - parseFloat(amount.value.replace("₱", "").replace(/\s+/g, ''))).toFixed(2);
            changeT.textContent = "₱ " + (parseFloat(inputElement12.value) - parseFloat(amount.value.replace("₱", "").replace(/\s+/g, ''))).toFixed(2);
            inputElement3.textContent = "₱ " + (parseFloat(inputElement12.value)).toFixed(2);
            if ((parseFloat(amount.value.replace("₱", "").replace(/\s+/g, '')) - parseFloat(inputElement12.value.replace("₱", "").replace(/\s+/g, ''))) > 0) {
                submit.disabled = true;

                changeT.textContent = "₱ 0.00";
                change.value = "₱ 0.00";
            } else {
                submit.disabled = false;
            }
        });
        var inputElement = document.getElementById("checkin");
        var inputElement1 = document.getElementById("checkout");

        function changeInputText(date, date1) {
            // Get the input element by its ID

            // Change the value (text) of the input element
            inputElement.value = date;
            inputElement1.value = date1;
            inputElement.setAttribute('max', date1)
            inputElement.setAttribute('min', date)
            inputElement1.setAttribute('max', date1)
            inputElement1.setAttribute('min', date)
            calculatePayment();
        }

        const checkinDateInput = document.getElementById('checkin');
        const checkoutDateInput = document.getElementById('checkout');
        document.addEventListener('DOMContentLoaded', function () {
            // Get references to your date inputs

            // Add event listeners to date inputs
            checkinDateInput.addEventListener('change', calculatePayment);
            checkoutDateInput.addEventListener('change', calculatePayment);
            checkinDateInput.value = '<?php echo $checkin ?>';
            checkoutDateInput.value = '<?php echo $checkout ?>';
            calculatePayment();

            // Function to calculate payment
        });
        var element = document.getElementById("disDIV");


        function calculatePayment() {
            // Get the values of the date inputs
            const checkinDate = new Date(checkinDateInput.value);
            const checkoutDate = new Date(checkoutDateInput.value);

            // Perform your payment calculation logic here
            // For this example, we'll assume a simple calculation
            const pricePerNight = <?php echo $roomRate ?>; // Change this to your room's price per night
            const taxRate = 0.12; // 10% tax rate
            var discountRate = 0.0; // 10% discount rate

            // Calculate the number of nights
            const oneDay = 24 * 60 * 60 * 1000; // hours * minutes * seconds * milliseconds
            const nights = Math.round(Math.abs((checkinDate - checkoutDate) / oneDay));
            if (nights >= 7) {
                discountRate = 0.03;
                element.style.display = "block";
            }
            else {
                discountRate = 0.0;
                element.style.display = "none";
            }

            // Calculate the payment information
            const totalAmount = pricePerNight * nights;
            const taxAmount = totalAmount * taxRate;
            const discountAmount = totalAmount * discountRate;
            const grandTotal = totalAmount + taxAmount - discountAmount;

            // Display the payment information
            document.getElementById('roomRate').textContent = '₱ ' + pricePerNight.toFixed(2);
            document.getElementById('night').textContent = 'x ' + nights;
            document.getElementById('tax').textContent = '+ ₱ ' + taxAmount.toFixed(2);
            document.getElementById('discount').textContent = '- ₱ ' + discountAmount.toFixed(2);
            document.getElementById('total').textContent = '₱ ' + totalAmount.toFixed(2);
            document.getElementById('Gtotal').textContent = '₱ ' + totalAmount.toFixed(2);
            document.getElementById('gt').textContent = '₱ ' + grandTotal.toFixed(2);
            document.getElementById('totalAmount').value = '₱ ' + grandTotal.toFixed(2);
            document.getElementById('rate1').value = grandTotal.toFixed(2);
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</body>

</html>