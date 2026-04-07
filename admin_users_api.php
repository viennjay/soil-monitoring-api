<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Go up one folder to find connection.php
include("../connection.php"); 

$query = "SELECT id, username, email, role FROM user";
$result = $conn->query($query);

$users = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode($users);
$conn->close();
?>