<?php
include("../connection.php"); 

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $email = trim($_POST['email']);
    $adminCode = $_POST['adminCode'] ?? '';

    if ($password != $confirmPassword) {
        header('location: ../login.php?response=' . urlencode('Password does not match!'));
        exit;
    }

    // Check how many times this email was used (Anti-spam)
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    
    if ($res['count'] >= 5) {
        header('location: ../login.php?response=' . urlencode('Spam account detected!'));
        exit;
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        header('location: ../login.php?response=' . urlencode('Account Already Exist'));
        exit;
    }

    // Determine Role
    $role = ($adminCode === "12345") ? 'admin' : 'user';
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user into database directly via PHP
    $stmt = $conn->prepare("INSERT INTO user (username, password, role, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hashedPassword, $role, $email);
    
    if ($stmt->execute()) {
        $msg = ($role === 'admin') ? 'Admin registration complete' : 'Registration complete';
        header('location: ../login.php?response=' . urlencode($msg));
    } else {
        header('location: ../login.php?response=' . urlencode('Error creating account.'));
    }
    
    $stmt->close();
    $conn->close();
    exit;
}
?>