<?php
session_start();
require_once 'databaseConnect.php';
include('navbar.php');

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
                header("Location: index.php");  // go to dashboard for admin
            } elseif ($user['user_type'] == 'applicant') {
                header("Location:  index.php"); // go to dashboard for applicant
            } elseif ($user['user_type'] == 'company') {
                header("Location: index.php"); // go to dashboard for company
            }
        } else {
            // wrong password
            header("Location: login.php?error=Incorrect+password");
        }
    } else {
        // not found username
        header("Location: login.php?error=Username+not+found");
    }
    exit();

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechJob BKK - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f5d472]">
<?php generateNavbar(); ?>
<div class="max-w-md mx-auto mt-12 bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Login</h2>
    <form method="POST">
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-medium mb-2">Username:</label>
            <input
                    type="text"
                    name="username"
                    id="username"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-medium mb-2">Password:</label>
            <input
                    type="password"
                    name="password"
                    id="password"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
        </div>
        <div class="mb-4">
            <button
                    type="submit"
                    class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Login
            </button>
        </div>
        <p class="text-center text-gray-700">
            Don't have an account?
            <a href="registerApp.php" class="text-blue-500 hover:underline">Register here</a>
        </p>
    </form>
</div>

<script>
    // Check parameter "error" in URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        const errorMessage = urlParams.get('error');
        alert(errorMessage); // Show alert
    }
</script>
</body>
</html>