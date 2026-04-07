<?php
$servername = "localhost";
$username = "u238830166_admin";
$password = "pL@nt51lt!"; 
$dbname = "u238830166_soil";  

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>