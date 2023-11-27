<?php
// session_start(); // Start the session

require_once "vendor/autoload.php";

use Omnipay\Omnipay;

define('CLIENT_ID', 'AQan7QqSsozlZ6AYh37u6VVQxAlu0A9INWSeC3IXa_AO7Kk2JXyaTYW603xs2poGInRNy6hpYC8OAQvI');
define('CLIENT_SECRET', 'EBxUaAaMtSH3MmsGW0-8XVMjC8jk0-nWnylL2vivsABfMShAzT09xNK5v0zV_hw632pVAnAwG0Lj88Wy');

define('PAYPAL_RETURN_URL', 'http://localhost/pagana/function/reservation.php');
define('PAYPAL_CANCEL_URL', 'http://localhost/pagana/rooms.php');
define('PAYPAL_CURRENCY', 'PHP'); // set your currency here

// Connect with the database
$conn = new mysqli('localhost', 'root', '', 'paganadb');

if ($conn->connect_errno) {
    die("Connect failed: ". $conn->connect_error);
}

$gateway = Omnipay::create('PayPal_Rest');
$gateway->setClientId(CLIENT_ID);
$gateway->setSecret(CLIENT_SECRET);
$gateway->setTestMode(true); //set it to 'false' when go live