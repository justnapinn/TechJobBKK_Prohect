<?php
session_start();
require 'databaseConnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, username, password, user_type FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];

            if ($user['user_type'] == 'admin') {
                header("Location: index.html");  // go to dashboard for admin
            } elseif ($user['user_type'] == 'applicant') {
                header("Location: index.html"); // go to dashboard for applicant
            } elseif ($user['user_type'] == 'company') {
                header("Location: index.html"); // go to dashboard for company
            }
            exit();
        } else {
            // wrong password
            header("Location: login.html?error=Incorrect+password");
            exit();
        }
    } else {
        // not found username
        header("Location: login.html?error=Username+not+found");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
