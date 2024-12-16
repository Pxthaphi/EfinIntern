<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "efinintern";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");

// Ensure connection is active before executing any queries
if (!$conn->ping()) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Reconnection failed: " . mysqli_connect_error());
    }
}
?>
