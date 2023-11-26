<?php

include_once("../function/dbconnect.php");

function updateRoom($newName, $newType, $newDescription, $newRate, $id)
{
    $conn = dbConnect();
    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/', $newName)) {
        $errors['name'] = "Special characters and spaces as the first letter are not allowed for Room Name.";
    }    

    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/', $newDescription)) {
        $errors['description'] = "Special characters and spaces as the first letter are not allowed for Room Name.";
    }    

    // Validate type: Only allow letters, spaces, and hyphens, and the first character should be a letter
    if (!preg_match('/^[a-zA-Z][a-zA-Z\s\-]*$/', $newType)) {
        $errors['type'] = "Special Characters, Numbers, and Spaces as the First Letter are not allowed for Room Type.";
    }

    // Validate rate: Only allow positive float numbers
    if (!preg_match('/^\d+(\.\d+)?$/', $newRate) || $newRate < 0) {
        $errors['rate'] = "Special Characters, Letters, and Spaces are not allowed for Rate.";
    }
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
    
        // Extract file information
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
    
        // Check for errors
        if ($fileError === UPLOAD_ERR_OK) {
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $file['type'];
    
            if (!in_array($fileType, $allowedTypes)) {
                $errors['image'] = "Invalid file type. Only JPEG, PNG, and GIF images are allowed.";
                $_SESSION['errors'] = $errors;
            }
    
            // Process the uploaded file
            // Move the file to a desired location
            $destination = "../uploads/" . $fileName;
            move_uploaded_file($fileTmpName, $destination);
            $stmt = $conn->prepare("UPDATE `room` SET image = ? WHERE id = ?");
            $stmt->bind_param('si', $fileName, $id);
            $stmt->execute();
            $_SESSION['success'] = 'Room Main Image has been updated.';
            // Save the file path in the database or perform other operations
        } else {
            // Handle file upload error
        }
    }
    if (isset($_FILES['images'])) {
        $files = $_FILES['images'];
        $uploadedFileNames = []; // Initialize the array to store file names

        foreach ($files['tmp_name'] as $index => $fileTmpName) {
            $fileName = $files['name'][$index];
            $fileSize = $files['size'][$index];
            $fileError = $files['error'][$index];
            $fileType = $files['type'][$index];
    
            if ($fileError === UPLOAD_ERR_OK) {
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
                if (!in_array($fileType, $allowedTypes)) {
                    $errors['images'] = "Invalid file type. Only JPEG, PNG, and GIF images are allowed.";
                    $_SESSION['errors'] = $errors;
                }
        
                // Process the uploaded file
                // Move the file to a desired location
                $destination = "../uploads/" . $fileName;
                move_uploaded_file($fileTmpName, $destination);
                $uploadedFileNames[] = $fileName;
                $imageUpload = implode(', ', $uploadedFileNames);
                $stmt = $conn->prepare("UPDATE `room` SET images = ? WHERE id = ?");
                $stmt->bind_param('si', $imageUpload, $id);
                $stmt->execute();
                $_SESSION['success'] = 'Room Image has been updated.';

                // Save the file path in the database or perform other operations
            } else {
                // Handle file upload error
                $_SESSION['errors']['images'] = "Error uploading the file.";
            }
        }
    }

    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        return false;
    }
    
    // Check if the room name already exists
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM room WHERE name = ? and id != ?");
    $stmt->bind_param("si", $newName, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $roomCount = $row['count'];

    if ($roomCount > 0) {
        $errors['name'] = "Room Name already exists.";
        $_SESSION['errors'] = $errors;
        return false;
    }
    
    $stmt = $conn->prepare("UPDATE `room` SET name = ?, type = ?, description = ?, rate = ? WHERE id = ?");
    $stmt->bind_param('ssssi', $newName, $newType, $newDescription, $newRate, $id);
    $stmt->execute();
    $_SESSION['success'] = 'Room has been updated.';
    
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