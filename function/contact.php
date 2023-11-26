<?php
include_once "dbconnect.php";

function insertContact($name, $email, $subject, $message){
    $conn = dbConnect();

    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/', $name)) {
        $errors['name'] = "Special characters and spaces as the first letter are not allowed for Name.";
    }

    if (preg_match('/[^A-Za-z0-9@._-]/', $email) || strpos($email, ' ') !== false) {
        $errors['email'] = "Special Characters and Spaces are not allowed for Email.";
    }

    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/', $subject)) {
        $errors['name'] = "Special characters and spaces as the first letter are not allowed for Subject.";
    }  

    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/', $message)) {
        $errors['message'] = "Special characters and spaces as the first letter are not allowed for Message.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        return false;
    }

    $stmt = $conn->prepare("INSERT INTO inquiries (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true; // Message send successfully
    } else {
        $stmt->close();
        $conn->close();
        return false; // Failed to send Message
    }

    $stmt->close();
    $conn->close();
    
}
?>