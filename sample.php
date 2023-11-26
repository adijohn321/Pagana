<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Reservation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <div class="row">
        <?php
        // You can fetch room information from your database here and loop through the available rooms.
        // Replace the sample data with actual data from your system.

        // Sample Data (Replace with database fetch)
        $availableRooms = [
            [
                'name' => 'Standard Room',
                'description' => 'A comfortable room with a single bed.',
                'price' => '$100/night',
                'image' => 'room1.jpg',
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'A spacious room with a king-size bed and a view.',
                'price' => '$200/night',
                'image' => 'room2.jpg',
            ],
        ];

        foreach ($availableRooms as $room) {
            echo '<div class="col-md-6">';
            echo '<div class="card">';
            echo '<img src="' . $room['image'] . '" class="card-img-top" alt="' . $room['name'] . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $room['name'] . '</h5>';
            echo '<p class="card-text">' . $room['description'] . '</p>';
            echo '<p class="card-text"><strong>Price:</strong> ' . $room['price'] . '</p>';
            echo '<a href="#" class="btn btn-primary">Reserve</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
