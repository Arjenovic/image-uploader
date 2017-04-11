<?php
$servername = "localhost";
$username = "root"; //admin account is named root usually
$password = "";
$databasename = "php_image_uploader";

// Create connection
$con = mysqli_connect("$servername","$username","$password","$databasename");

// Check connection
if ($con->connect_error) {
    die("ERR: We are currently busy with website maintenance. We'll be back soon!" . $con->connect_error);
}

echo "Connected successfully.";
?>