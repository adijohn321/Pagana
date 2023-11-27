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

function registerUser($fname, $mname, $lname, $email, $phone, $password, $confirm_password, $role)
{
    $errors = array();

    // Validate first name
    if (!preg_match("/^[A-Za-z][A-Za-z\s]*$/", $fname)) {
        $errors['fname'] = "Special Characters, Numbers, and Spaces as the First Letter are not allowed for First Name.";
    }

    if (!empty($mname)) {
        if (!preg_match("/^[A-Za-z][A-Za-z\s]*$/", $mname)) {
            $errors['mname'] = "Special Characters, Numbers, and Spaces as the First Letter are not allowed for Middle Name.";
        }
    }
    if (!preg_match("/^[A-Za-z][A-Za-z\s]*$/", $lname)) {
        $errors['lname'] = "Special Characters, Numbers, and Spaces as the First Letter are not allowed for Last Name.";
    }

    if (preg_match('/[^A-Za-z0-9]/', $password) || strpos($password, ' ') !== false) {
        $errors['password'] = "Special Characters and Spaces are not allowed for Password.";
    }
    // if($password > 5){
    //   $errors['password'] = "Password should be higher than 8 letters";
    //}

    // Validate email: Check if it contains special characters or spaces
    if (preg_match('/[^A-Za-z0-9@._-]/', $email) || strpos($email, ' ') !== false) {
        $errors['email'] = "Special Characters and Spaces are not allowed for Email.";
    }

    if ($password !== $confirm_password) {
        $errors['match'] = "Password and Confirm Password do not match.";
    }


    $status = '0';
    if ($role != "Guest") {
        $status = '1';
    } else {

        $conn = dbConnect();
    }
    $emailCheckQuery = $conn->prepare("SELECT COUNT(*) AS count FROM users WHERE email = ?");
    $emailCheckQuery->bind_param("s", $email);
    $emailCheckQuery->execute();
    $result = $emailCheckQuery->get_result();
    $row = $result->fetch_assoc();
    $emailCount = $row['count'];

    if ($emailCount > 0) {
        $errors['email'] = "Email is already registered.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        return false;
    }
    $verificationToken = bin2hex(random_bytes(32)); // Generates a random 32-byte hex string

    $stmt = $conn->prepare("INSERT INTO users (fname, mname, lname, email, phone, password, status, verification_token,  user_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $stmt->bind_param("ssssisiss", $fname, $mname, $lname, $email, $phone, $hashedPassword, $status, $verificationToken, $role);

    if ($stmt->execute()) {
        $subject = "Account Registration - Email Verification";
        $message = "Dear $fname,\n\nThank you for registering an account with Pagana Hotel. Please click the following link to verify your email address:\n\n";
        $verificationLink = "https://localhost/pagana/verify.php?token=$verificationToken"; // Update with your verification URL
        $message .= "$verificationLink\n\n";
        $message .= "If you did not register an account, please ignore this email.\n\n";
        $sender = "From: Pagana Hotel";
        mail($email, $subject, $message, $sender);
        $stmt->close();
        $conn->close();
        return true;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }

}




?>