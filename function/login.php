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

function loginUser($email, $password)
{
    $conn = dbConnect();

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = '$email'");

    $stmt->execute();
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    if ($numRows > 0) {
        // Rows were found, you can fetch the data and process it
        $user = $result->fetch_assoc();
        $currentUser = $user['id'];
        // ...
    } else {
        $_SESSION['error']  = "No Account found for email: $email.";
        incrementLoginAttempts();
        return false;
    }

    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] == '1') {
            // Password matches, login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user['fname'];
            $_SESSION['role'] = $user['user_level'];
            $_SESSION['login_attempts'] = 0;
            return true;
        } else {
            $_SESSION['error']  = "Account needs verification. Please check the e-mail we've sent to: ".$email;
            return false;
        }
    } else {
        // Invalid email or password
        $_SESSION['error']  = "Invalid or Incorrect Password.";
        incrementLoginAttempts();
        return false;
    }
}
function incrementLoginAttempts()
{
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    } else {
        $_SESSION['login_attempts']++;
    }
    if (!isset($_SESSION['multiplier'])) {
        $_SESSION['multiplier'] = 1;
    }

    if ($_SESSION['login_attempts'] > 1) {
        // Lock the user out for 1 minute
        $_SESSION['login_attempts'] = 0;
        $_SESSION['login_lockout'] = (time() + 60 * $_SESSION['multiplier']);
    }

    // Reset login attempts if lockout expired
    if (isset($_SESSION['login_lockout']) && $_SESSION['login_lockout'] <= time()) {
        $_SESSION['multiplier'] *= 5;
        $_SESSION['login_attempts'] = 0; // Reset the login attempts counter
        unset($_SESSION['login_lockout']);
    }
}


function isLoginLockedOut()
{
    return isset($_SESSION['login_lockout']) && $_SESSION['login_lockout'] > time();
}
