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

function insertAbout($type, $description)
{
    $conn = dbConnect();

    // Validate description: Only allow letters and spaces, and the first character should be a letter
    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/', $description)) {
        $errors['description'] = "Special characters and spaces as the first letter are not allowed for Description.";
    }    

    // Validate type: Only allow letters, spaces, and hyphens, and the first character should be a letter
    if (!preg_match('/^[a-zA-Z][a-zA-Z\s\-]*$/', $type)) {
        $errors['type'] = "Special Characters, Numbers, and Spaces as the First Letter are not allowed for Type.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        return false;
    }
    
    // Check if the room name already exists
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM about_us WHERE type = ?");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $roomCount = $row['count'];

    if ($roomCount > 0) {
        $errors['type'] = "About us Type already exists.";
        $_SESSION['errors'] = $errors;
        return false;
    }
    
    $stmt = $conn->prepare("INSERT INTO about_us (description, type) VALUES (?, ?)");
    $stmt->bind_param("ss", $description, $type);

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
