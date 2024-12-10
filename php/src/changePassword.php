<?php
ob_start(); 
session_start();
require_once 'databaseConnect.php';
include('navbar.php');
include('checkLogin.php');

$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = htmlspecialchars(trim($_POST['old_password']));
    $new_password = htmlspecialchars(trim($_POST['new_password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    if (!$user_data || !password_verify($old_password, $user_data['password'])) {
        $error_message = "Old password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "The new password and confirmation password do not match.";
    } elseif ($old_password === $new_password) {
        $error_message = "New password must not be the same as the old password.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $hashed_password, $user_id);

        if ($stmt->execute()) {
            header("Location: profile.php");
            exit();
        } else {
            $error_message = "An error occurred while changing the password: " . $stmt->error;
        }
    }
}

ob_end_flush(); 
?>


<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen items-center justify-center">
<div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-center">Change Password</h2>
    <form method="POST" class="space-y-4">
        <?php if ($error_message): ?>
            <div class="text-red-500 text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="text-green-500 text-center"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Old Password</label>
            <input type="password" name="old_password" 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">New Password</label>
            <input type="password" name="new_password" 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password</label>
            <input type="password" name="confirm_password" 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   required>
        </div>
        <div class="flex items-center justify-center">
            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Change Password
            </button>
        </div>
    </form>
</div>
</body>
</html>
