<?php
session_start();
include("../connection.php"); 

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check the database directly using PHP
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify Password
        if (password_verify($password, $user['password'])) {

            // ✅ Restrict to only 'user' role
            if ($user['role'] !== 'user') {
                header('Location: ../login.php?response=' . urlencode('Access denied: Admins must log in from admin page.'));
                exit();
            }

            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['confirm'] = true;
            $_SESSION['id'] = $user['id'];

            header('Location: ../index.php?selectedDate=' . date("d-m-Y"));
            exit();
        }
    }

    header('Location: ../login.php?response=' . urlencode('Password does not match or account not found!'));
    exit();
}
?>