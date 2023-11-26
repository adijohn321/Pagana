<?php

include_once("../function/dbconnect.php");

function updateAmenities($newName, $newRate, $id)
{
    $conn = dbConnect();
    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/', $newName)) {
        $errors['name'] = "Special characters and spaces as the first letter are not allowed for Amenities Name.";
    }    

    // Validate rate: Only allow positive float numbers
    if (!preg_match('/^\d+(\.\d+)?$/', $newRate) || $newRate < 0) {
        $errors['rate'] = "Special Characters, Letters, and Spaces are not allowed for Rate.";
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        return false;
    }
    
    // Check if the room name already exists
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM amenities WHERE name = ? and id != ?");
    $stmt->bind_param("si", $newName, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $roomCount = $row['count'];

    if ($roomCount > 0) {
        $errors['name'] = "Amenities Name already exists.";
        $_SESSION['errors'] = $errors;
        return false;
    }
    
    $stmt = $conn->prepare("UPDATE `amenities` SET name = ?, rate = ? WHERE id = ?");
    $stmt->bind_param('sii', $newName, $newRate, $id);
    $stmt->execute();
    $_SESSION['success'] = 'Amenities has been updated.';
    
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