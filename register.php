<?php
session_start();
require_once 'databaseConnect.php';
include('navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechJobBKK - Choose Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f5d472] min-h-screen flex flex-col">
    <div class="fixed top-0 left-0 w-full z-10">
        <?php generateNavbar(); ?> 
    </div>

    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg text-center mt-36">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Choose Registration Type</h1>
        <button 
            class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg mb-4 text-lg font-medium transition-colors"
            onclick="window.location.href='registerApp.php'">
            Register as Applicant
        </button>
        <button 
            class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg text-lg font-medium transition-colors"
            onclick="window.location.href='registerCom.php'">
            Register as Company
        </button>

        
        <p class="mt-6 text-gray-600">
            Already have an account? 
            <a href="login.php" class="text-blue-500 hover:text-blue-700 font-medium">Login</a>
        </p>
    </div>
</body>
</html>
