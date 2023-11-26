<?php

include_once("../function/dbconnect.php");

function updateAbout($newDescription, $newType, $id)
{
    $conn = dbConnect();
    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/', $newDescription)) {
        $errors['name'] = "Special characters and spaces as the first letter are not allowed for Amenities Name.";
    }    

    // Validate type: Only allow letters, spaces, and hyphens, and the first character should be a letter
    if (!preg_match('/^[a-zA-Z][a-zA-Z\s\-]*$/', $newType)) {
        $errors['type'] = "Special Characters, Numbers, and Spaces as the First Letter are not allowed for Room Type.";
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        return false;
    }
    
    // Check if the room name already exists
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM about_us WHERE type = ? and id != ?");
    $stmt->bind_param("si", $newType, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $roomCount = $row['count'];

    if ($roomCount > 0) {
        $errors['name'] = "About Type already exists.";
        $_SESSION['errors'] = $errors;
        return false;
    }
    
    $stmt = $conn->prepare("UPDATE `about_us` SET description = ?, type = ? WHERE id = ?");
    $stmt->bind_param('ssi', $newDescription, $newType, $id);
    $stmt->execute();
    
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