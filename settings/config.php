<?php
$servername = "localhost";  // Your database server (e.g., localhost)
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "ashform";  // Your database name

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
