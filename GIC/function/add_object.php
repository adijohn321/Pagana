<?php


$totalDue = 0;
$totalDuePayable = 0;

//unset($_SESSION['myArrayOfObjects']) ;
if (isset($_POST['action']) && $_POST['action'] === 'deleteAccount') {

    session_start();
    if (isset($_POST['index'])) {
        $deleteIndex = intval($_POST['index']);

        // Remove the object at the specified index
        if (isset($_SESSION['myArrayOfObjects'][$deleteIndex])) {
            unset($_SESSION['myArrayOfObjects'][$deleteIndex]);

            // Reset array keys after deletion
            $_SESSION['myArrayOfObjects'] = array_values($_SESSION['myArrayOfObjects']);
        }
        setItems();
    }
} else
    if (isset($_POST['action']) && $_POST['action'] === 'voucher') {

        session_start();
        include_once "../../function/dashboard.php";

        $discount = $_POST['discount'];

        $conn = dbConnect();
        $stmt = $conn->prepare("SELECT * FROM `discount` where code = '$discount'");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            echo '
            <div class="alert alert-danger" id="alert">
            Voucher Applied.
            </div>
            ';
            $_SESSION['voucher'] = $row['rate'];
        } else {
            echo '
            <div class="alert alert-danger" id="alert">
            Invalid Voucher.
            </div>
            ';
            $_SESSION['voucher'] = 0;
        }
        setItems();
    } else {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();
            include_once "../../function/dashboard.php";
            $tID = $_POST['tID'];


            $conn = dbConnect();
            $stmt = $conn->prepare("SELECT * FROM reservation  RIGHT JOIN room on room_id = room.name where reservation.status != 'Reserved'  and transaction_id = '$tID'");
            $stmt->execute();
            $result = $stmt->get_result();

            $sn = 0;
            while ($row = $result->fetch_assoc()) {
                $sn++;
                $id = $row['transaction_id'];
                $reservationName = $row['name'];
                $room_id = $row['room_id'];
                $reservationEmail = $row['email'];
                $total_rate = $row['total_rate'];
                $rate = $row['rate'];
                $reservationPhone = $row['phone'];
                $reservationAddress = $row['address'];
                $checkin = $row['checkin'];
                $checkout = $row['checkout'];
                $reservationStatus = $row['status'];
                $reservationTransaction = $row['transaction_id'];
            }
            if ($sn == 0) {
                echo '
    <div class="alert alert-danger" id="alert">
       Transaction ID Not Found!
    </div>
    ';
            } else {
                $checkinTimestamp = strtotime($checkin);
                $checkoutTimestamp = strtotime($checkout);

                if ($checkinTimestamp !== false && $checkoutTimestamp !== false) {
                    $calc_days = floor(($checkoutTimestamp - $checkinTimestamp) / (60 * 60 * 24));
                } else {
                    $calc_days = 0; // Default value in case of invalid dates
                }

                $newObject = (object) [
                    'id' => $id,
                    'room_id' => $room_id,
                    'total_rate' => $total_rate,
                    'rate' => $rate,
                    'checkin' => $checkin,
                    'checkout' => $checkout,
                    'calc_days' => $calc_days,
                    'paid' => calculatePaid($rate, $calc_days),
                    'exsd' => calculateDiscount($rate, $calc_days),
                    'tax' => calculateTax($rate, $calc_days),
                ];

                $objectExists = false;
                foreach ($_SESSION['myArrayOfObjects'] as $existingObject) {
                    if ($existingObject->id == $newObject->id) {
                        $objectExists = true;
                        break;
                    }
                }

                $_SESSION['error'] = 'Object already exists in the array!';
                if (!$objectExists) {
                    // Add the new object to the array
                    $_SESSION['myArrayOfObjects'][] = $newObject;
                } else {
                    // Object already exists, handle accordingly (maybe show an error message)
                    echo '
    <div class="alert alert-danger" id="alert">
       Transaction already added.
    </div>
    ';
                }
            }

            setItems();
        } else {

            setItems();
        }
    }
function setItems()
{

    if (isset($_SESSION['voucher'])) {
        $voucherRate = $_SESSION['voucher'];
    }
    if (!empty($_SESSION['myArrayOfObjects'])) {

        $totalDue = 0;
        $totalDuePayable = 0;
        ?>
        <h2>Check Out Items/Transaction</h2>
        <table style="width: 100%;">
            <tbody class="items">
                <tr>
                    <td style="width: 10%;font-weight: 900; ">Transaction ID</td>
                    <td style="width: 40%;font-weight: 900;">Room</td>
                    <td style="width: 10%;font-weight: 900;">Check In</td>
                    <td style="width: 10%;font-weight: 900;">Check Out</td>
                    <td style="width: 10%;font-weight: 900;text-align: right;">Rate</td>
                    <td style="width: 10%;font-weight: 900; text-align: center;">No. of Nights</td>
                    <td style="width: 10%;font-weight: 900;text-align: right;">Total</td>
                </tr>

                <?php

                foreach ($_SESSION['myArrayOfObjects'] as $index => $object) {
                    ?>
                    <tr>
                        <td style="width: 10%;">
                            <a onclick="deleteAccount('<?php echo $index ?>')" class="btn btn-danger btn-sm"><i
                                    class="fas fa-trash"></i></a>
                            <?php echo $object->id ?>
                        </td>
                        <td style="width: 40%;">
                            <?php echo $object->room_id ?>
                        </td>
                        <td style="width: 10%;">
                            <?php echo $object->checkin ?>
                        </td>
                        <td style="width: 10%;">
                            <?php echo $object->checkout ?>
                        </td>
                        <td style="width: 10%;text-align: right;">
                            <?php echo $object->rate ?>
                        </td>
                        <td style="width: 10%; text-align: center;">
                            <?php echo $object->calc_days ?>
                        </td>
                        <td style="width: 10%;text-align: right;">P
                            <?php echo number_format($object->total_rate, 2, '.', ',');
                            $totalDue += $object->total_rate;
                            $totalDuePayable += $object->paid;
                            ?>
                        </td>
                    </tr>
                    <?php

                }
                $voucherDiscount = ($totalDuePayable * ($voucherRate / 100));
                $totalDuePayable = $totalDuePayable - ($totalDuePayable * ($voucherRate / 100));
                ?>

            </tbody>

        </table>
        <form action="" method="post" class="mb-5 mt-5 container">
            <div class="row">
                <div class="col-md-3">
                    <!-- Total -->
                    <div class="form-group">
                        <label for="gt">Total Amount Due </label>
                        <input type="text" class="form-control" id="gt" name="gt"
                            value="<?php echo number_format($totalDuePayable, 2, '.', ',') ?>" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <!-- discount -->
                    <div class="form-group">
                        <label for="discount">Discount </label>
                        <input type="text" class="form-control" id="discount" name="discount"
                            placeholder="Enter discount voucher">
                    </div>
                </div>
                <div class="col-md-3">
                    <!-- Cash -->
                    <div class="form-group">
                        <label for="cash">Cash </label>
                        <input type="text" class="form-control" id="cash" name="cash" placeholder="Enter Cash">
                    </div>
                </div>
                <div class="col-md-3">
                    <!-- Change -->
                    <div class="form-group">
                        <label for="change">Change </label>
                        <input type="text" class="form-control" id="change" name="change" readonly>
                    </div>
                </div>
            </div>
            <div class="container mt-2">
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="alert-heading">Client Information</h5>
                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td width="15%">
                                            <label style="width: 50%;"><strong>Name:</strong> </label>
                                        </td>
                                        <td width="90%"> <label>

                                            </label></td>
                                    </tr>
                                    <tr>
                                        <td width="15%">
                                            <label style="width: 50%;"><strong>Address:</strong> </label>
                                        </td>
                                        <td width="90%"> <label>

                                            </label></td>
                                    </tr>
                                    <tr>
                                        <td width="15%">
                                            <label style="width: 50%;"><strong>Phone:</strong> </label>
                                        </td>
                                        <td width="90%"> <label>

                                            </label></td>
                                    </tr>
                                    <tr>
                                        <td width="15%">
                                            <label style="width: 50%;"><strong>Email:</strong> </label>
                                        </td>
                                        <td width="90%"> <label>

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
                                <td style="width: 20%;">Transaction ID</td>
                                <td style="width: 10%;">No. of Nights</td>
                                <td style="width: 15%;text-align:right;">Rate</td>
                                <td style="width: 10%;text-align:right;">Sub-Total</td>
                                <td style="width: 10%; text-align:right;">TAX (12%)</td>
                                <td style="width: 15%; text-align:right;">Discount</td>
                                <td style="width: 20%; text-align:right;">Total</td>
                            </tr>
                            <?php
                            $totalDiscount = 0;
                            $totalDiscounted = 0;
                            $totalTax = 0;
                            foreach ($_SESSION['myArrayOfObjects'] as $index => $object) {
                                $totalDiscount += $object->exsd;
                                $totalDiscounted += $object->paid;
                                $totalTax += $object->tax;
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $object->id ?>
                                    </td>
                                    <td><label id="night" style="width: 50%;text-align: end;">
                                            <?php echo $object->calc_days ?>
                                        </label></td>
                                    <td style="text-align:right;"><label id="roomRate" style="width: 50%;text-align: end;">
                                            <?php echo number_format($object->rate, 2, '.', ',') ?>
                                        </label></td>
                                    <td style="text-align:right;"><label id="roomRateT" style="width: 50%;text-align: end;">
                                            <?php echo number_format($object->total_rate, 2, '.', ',') ?>
                                        </label></td>
                                    <td style="text-align:right;"><label id="roomRateT" style="width: 50%;text-align: end;">
                                            <?php echo number_format($object->tax, 2, '.', ',') ?>
                                        </label></td>
                                    <td style="text-align:right;"><label id="disc" style="width: 50%;text-align: end;">
                                            <?php echo number_format($object->exsd, 2, '.', ',') ?>
                                        </label></td>
                                    <td style="text-align:right;"><label id="total" style="width: 50%;text-align: end;">
                                            <?php echo number_format($object->paid, 2, '.', ',') ?>
                                        </label></td>
                                </tr>
                                <?php
                            }

                            ?>
                            <tr>
                                <td></td>
                                <td><label id="night" style="width: 50%;text-align: end;"></label></td>
                                <td style="text-align:right; border-top: solid 1px"><label id="roomRate"
                                        style="width: 50%;text-align: end; font-weight:900">TOTAL</label></td>
                                <td style="text-align:right;border-top: solid 1px"><label id="disc"
                                        style="width: 50%;text-align: end;">
                                        <?php echo number_format($totalDue, 2, '.', ',') ?>
                                    </label></td>
                                <td style="text-align:right;border-top: solid 1px"><label id="disc"
                                        style="width: 50%;text-align: end;">
                                        <?php echo number_format($totalTax, 2, '.', ',') ?>
                                    </label></td>
                                <td style="text-align:right;border-top: solid 1px"><label id="disc"
                                        style="width: 50%;text-align: end;">
                                        <?php echo number_format($totalDiscount, 2, '.', ',') ?>
                                    </label></td>
                                <td style=" text-align:right;border-top: solid 1px"><label id="total"
                                        style="width: 50%;text-align: end;">P
                                        <?php echo number_format($totalDiscounted, 2, '.', ',') ?>
                                    </label></td>
                            </tr>

                        </tbody>
                    </table>
                    <hr>
                    <div><label style="width: 50%;"><strong>Total Cost:</strong> </label><label id="Gtotal"
                            style="width: 50%;text-align: end;">P
                            <?php echo number_format($totalDue, 2, '.', ',') ?>
                        </label></div>
                    <div style="" id="disDIV"><label style="width: 50%;"><strong>Extended stay
                                discount (3%):</strong> </label><label id="discount" style="width: 50%;text-align: end;">-
                            <?php echo number_format($totalDiscount, 2, '.', ',') ?>
                        </label></div>
                    <div style="" id="disDIV2"><label style="width: 50%;"><strong>Voucher
                                discount (
                                <?php echo $voucherRate ?>%):
                            </strong> </label><label id="discount" style="width: 50%;text-align: end;">-
                            <?php echo number_format($voucherDiscount, 2, '.', ',') ?>
                        </label></div>
                    <div><label style="width: 50%;"><strong>Tax (12%):</strong> </label><label id="tax"
                            style="width: 50%;text-align: end;">+
                            <?php echo number_format($totalTax, 2, '.', ',');
                            $totalDiscounted -= $voucherDiscount; ?>
                        </label></div>
                    <hr>

                    <div><label style="width: 50%;"><strong>Grand total:</strong> </label><label id="gt"
                            style="width: 50%;text-align: end;">
                            <?php echo number_format($totalDiscounted, 2, '.', ',') ?>
                        </label></div>
                    <div><label style="width: 50%;"><strong>Cash:</strong> </label><label id="cashT"
                            style="width: 50%;text-align: end;">0.00</label></div>
                    <hr>
                    <div><label style="width: 50%;"><strong>Change:</strong> </label><label id="changeT"
                            style="width: 50%;text-align: end;">0.00</label></div>
                </div>
            </div>


            <br>
            <button type="submit" name="submit" id="submit" class="form-control btn-secondary" disabled>Submit</button>

        </form>

        <?php
    } else {
        echo '<p>No Data</p>';
    }
    ?>
    <script>
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

    </script>
    <?php
}
function calculatePaid($rate, $nights)
{
    $ret = $nights * $rate * 1.12;
    $discountRate = 0.03;
    if ($nights >= 7) {
        $ret = $ret - ($ret * $discountRate);
    }
    return $ret;
}
function calculateDiscount($rate, $nights)
{
    $ret = $nights * $rate * 1.12;
    $discountRate = 0.03;
    if ($nights >= 7) {
        return ($ret * $discountRate);
    }
    return 0;
}

function calculateTax($rate, $nights)
{
    $ret = $nights * $rate;
    $tax = 0.12;
    return ($ret * $tax);
}
?>