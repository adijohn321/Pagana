<?php

include_once("function/dbconnect.php");
$conn = dbConnect();

$id = $_GET['id'];
// Process data from the database
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
}





// Generate HTML with the processed data
$html = '
<div class="container">
<h1 class="mt-4">Room Information</h1>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <img src="uploads/'.$roomImage.'" class="card-img-top" alt="Room Image" style="max-height: 350px;">
            <div class="card-body">
                <h5 class="card-title">'.$roomName.'</h5>
                <p class="card-text">'.$roomDescription.'</p>
            </div>
        </div>
        <ul class="list-group mt-4">
            <li class="list-group-item"><i class="fas fa-bed"></i> Room Type: '.$roomType.'</li>
            <li class="list-group-item"><i class="fas fa-dollar-sign"></i> Price Rate: $'.number_format((float)$roomRate, 2, '.', '').'/night</li>
            <li class="list-group-item"><i class="fas fa-user-friends"></i> Max Occupancy: 2 adults (Static)</li>
            <li class="list-group-item"><i class="fas fa-bed"></i> Bed Type: '.$roomType.'</li>
        </ul>
    </div>
';
$html .='<div class="col-md-6">

<ul class="list-group mt-4">
    <h4>Room Features</h4>';

$i = 0;
$stmt->close();
$stmt = $conn->prepare("SELECT * FROM room_features WHERE room_id = '$id'");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $name = $row["description"];
    $amid = $row["id"];
    $i++;
    $html.='<li class="list-group-item">
    <i class="fas fa-check"></i> '.$name.'
</li>';
}
if ($i == 0) {
    $html .= 'N/A';
}
$html .= '</ul><ul class="list-group mt-4">
<h4>Room Amenities</h4>';


$i = 0;
$stmt->close();
$stmt = $conn->prepare("SELECT * FROM room_amenities WHERE room_id = '$id'");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $name = $row["description"];
    $amid = $row["id"];
    $i++;
    $html.='<li class="list-group-item">
    <i class="fas fa-check"></i> '.$name.'
</li>';
}
if ($i == 0) {
    $html .= 'N/A';
}


$html .= '</ul></div>';
$html .= '</div>';

// Send the processed HTML to the client
echo $html;
