<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipt</title>
    
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

    <?php
    session_start();
    
    include_once "../function/dashboard.php";
    if (isset($_SESSION['voucher'])) {
        $voucherRate = $_SESSION['voucher'];
    }
    if (!empty($_SESSION['myArrayOfObjects'])) {

        $totalDue = 0;
        $totalDuePayable = 0;

        $uid = '';
        foreach ($_SESSION['myArrayOfObjects'] as $index => $object) {
            $uid = $object->uid;
            $totalDue += $object->total_rate;
            $totalDuePayable += $object->paid;


        }
        $voucherDiscount = ($totalDuePayable * ($voucherRate / 100));
        $totalDuePayable = $totalDuePayable - ($totalDuePayable * ($voucherRate / 100));


        ?>
        <div action="" method="post" class="mb-5 mt-5 container">

            <div class="container mt-2">
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="alert-heading">Client Information</h5>
                            <?php

                            $conn = dbConnect();
                            $stmt = $conn->prepare("SELECT * FROM users  where id = '$uid'");
                            $stmt->execute();
                            $result = $stmt->get_result();

                            $sn = 0;
                            if ($row = $result->fetch_assoc()) {
                                $fullname = $row["fname"] . ' ' . $row["mname"] . ' ' . $row["lname"];
                                $email = $row['email'];
                                $number = $row['phone'];
                                ?>
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td width="15%">
                                                <label style="width: 50%;"><strong>Name:</strong> </label>
                                            </td>
                                            <td width="90%"> <label>
                                                    <?php echo $fullname ?>
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
                                                    <?php echo $number ?>
                                                </label></td>
                                        </tr>
                                        <tr>
                                            <td width="15%">
                                                <label style="width: 50%;"><strong>Email:</strong> </label>
                                            </td>
                                            <td width="90%"> <label>
                                                    <?php echo $email ?>
                                                </label></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php
                            }
                            ?>

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
                            <?php echo number_format($totalDiscounted, 2, '.', ',') ?>
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
                            style="width: 50%;text-align: end;">₱ <?php echo number_format( $_POST['cash'],2,'.',',')?></label></div>
                    <hr>
                    <div><label style="width: 50%;"><strong>Change:</strong> </label><label id="changeT"
                            style="width: 50%;text-align: end;"><?php echo $_POST['change']?></label></div>
                </div>
            </div>


            <br>

        </div>

        <?php
    } else {
        echo '<p>No Data</p>';
    }
    ?>

</body>

</html>