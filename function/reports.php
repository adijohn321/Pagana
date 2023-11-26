<?php
// Assuming you have a function named dbConnect() for establishing the database connection
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

function getDailyIncome()
{
    $conn = dbConnect();

    $sql = "SELECT SUM(amount_paid) AS total FROM reservation WHERE DATE(datecreated) = CURDATE()";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $dailyIncome = $row['total'];

    $conn->close();

    return $dailyIncome;
}

function getMonthlyIncome()
{
    $conn = dbConnect();

    $sql = "SELECT SUM(amount_paid) AS total FROM reservation WHERE MONTH(datecreated) = MONTH(CURDATE()) AND YEAR(datecreated) = YEAR(CURDATE())";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $monthlyIncome = $row['total'];

    $conn->close();

    return $monthlyIncome;
}

function getYearlyIncome()
{
    $conn = dbConnect();

    $sql = "SELECT SUM(amount_paid) AS total FROM reservation WHERE YEAR(datecreated) = YEAR(CURDATE())";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $yearlyIncome = $row['total'];

    $conn->close();

    return $yearlyIncome;
}

function getTotalIncome()
{
    $conn = dbConnect();

    $sql = "SELECT SUM(amount_paid) AS total FROM reservation";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $totalIncome = $row['total'];

    $conn->close();

    return $totalIncome;
}

?>
