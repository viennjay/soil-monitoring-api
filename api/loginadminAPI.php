<?php
session_start();
include("../connection.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        header("Location: ../loginadmin.php?response=" . urlencode("Username and password required."));
        exit();
    }

    // ✅ FIXED: Changed 'users' to 'user' to match the database
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    if (!$stmt) {
        header("Location: ../loginadmin.php?response=" . urlencode("Database error."));
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();

        // Check password
        if (password_verify($password, $row['password'])) {
            // Save session data
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Redirect only if admin
            if ($row['role'] === 'admin') {
                header("Location: ../admin.php");
                exit();
            } else {
                // Not admin: go back to user login page
                header("Location: ../login.php?response=" . urlencode("You are not authorized to access the admin dashboard."));
                exit();
            }
        } else {
            header("Location: ../loginadmin.php?response=" . urlencode("Invalid password."));
            exit();
        }
    } else {
        header("Location: ../loginadmin.php?response=" . urlencode("No such user found."));
        exit();
    }
} else {
    // Fallback if accessed without POST
    header("Location: ../loginadmin.php?response=" . urlencode("Invalid request."));
    exit();
}
?>