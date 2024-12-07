<?php
session_start();

function generateNavbar(): void
{
    if (isset($_SESSION['user_id'])) {
        // Logged-in navbar
        echo '
        <nav class="bg-gray-800 p-4">
            <div class="container mx-auto flex justify-between items-center">
                <a href="index.php" class="text-white text-xl font-bold">Your Logo</a>
                <div class="space-x-4">
                    <a href="profile.php" class="text-white hover:bg-gray-700 px-3 py-2 rounded">Profile</a>
                    <a href="logout.php" class="text-white hover:bg-red-700 bg-red-600 px-3 py-2 rounded">Logout</a>
                </div>
            </div>
        </nav>';
    } else {
        // Not logged-in navbar
        echo '
        <nav class="bg-gray-800 p-4">
            <div class="container mx-auto flex justify-between items-center">
                <a href="index.php" class="text-white text-xl font-bold">Your Logo</a>
                <div class="space-x-4">
                    <a href="login.php" class="text-white hover:bg-gray-700 px-3 py-2 rounded">Login</a>
                    <a href="register.php" class="text-white hover:bg-blue-700 bg-blue-600 px-3 py-2 rounded">Register</a>
                </div>
            </div>
        </nav>';
    }
}

?>