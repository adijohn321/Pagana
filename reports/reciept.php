<?php


require __DIR__ . "../../vendor/autoload.php";
use Dompdf\Dompdf;
$doomPDF = new Dompdf();

session_start();
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
    }
}


ob_start();
require('template/reciept.php');
$html = ob_get_contents();
ob_get_clean();
$doomPDF->set_paper('A4', 'portrait');

$doomPDF->load_html($html);
$doomPDF->render();
$doomPDF->stream("Reciept.pdf", ['Attachment' => false]);

$_SESSION['myArrayOfObjects'] = [];
$_SESSION['voucher'] = 0;
?>