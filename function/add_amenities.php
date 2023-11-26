<?php

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

function insertAmenities($name, $rate)
{
    $conn = dbConnect();

    // Validate name: Only allow letters and spaces, and the first character should be a letter
    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/', $name)) {
        $errors['name'] = "Special characters and spaces as the first letter are not allowed for Amenities Name.";
    } 

    // Validate rate: Only allow positive float numbers
    if (!preg_match('/^\d+(\.\d+)?$/', $rate) || $rate < 0) {
        $errors['rate'] = "Special Characters, Letters, and Spaces are not allowed for Rate.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        return false;
    }
    
    // Check if the room name already exists
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM amenities WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $roomCount = $row['count'];

    if ($roomCount > 0) {
        $errors['name'] = "Amenities Name already exists.";
        $_SESSION['errors'] = $errors;
        return false;
    }
    
    $stmt = $conn->prepare("INSERT INTO amenities (name, rate) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $rate);

    if ($stmt->execute()) {
        // Insert successful
        $stmt->close();
        $conn->close();
        return true;
    } else {
        // Insert failed
        $stmt->close();
        $conn->close();
        return false;
    }
}

?>
