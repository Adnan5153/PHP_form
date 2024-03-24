<?php
$host = 'localhost'; // DB host
$dbname = 'form'; // database name
$username = 'root'; // database username
$password = ''; // database password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
