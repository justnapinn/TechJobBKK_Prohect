<?php
session_start();
require_once 'databaseConnect.php';
include('navbar.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = 1; // User type for Company
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $first_name = trim($_POST['first_name']);
    $last_name = 'Company'; // Default last name for company accounts
    $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '2023-10-21';
    $address = trim($_POST['address']);
    $subdistrict = trim($_POST['subdistrict']);
    $district = trim($_POST['district']);
    $province = trim($_POST['province']);
    $postal_code = trim($_POST['postal_code']);
    $user_email = trim($_POST['user_email']);
    $user_phone = trim($_POST['user_phone']);

    // Validate Email
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('Invalid email format. Please try again.');
            window.history.back();
        </script>";
        exit();
    }

    // Check for duplicate entries
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR user_email = ? OR user_phone = ?");
    $stmt->bind_param("sss", $username, $user_email, $user_phone);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    if ($count > 0) {
        echo "<script>
            alert('Username, email, or phone number is already in use. Please try again.');
            window.history.back();
        </script>";
        exit();
    }

    $stmt->close();

    // Insert new record
    $stmt = $conn->prepare("
        INSERT INTO users 
        (user_id, user_type, username, password, first_name, last_name, birthday, address, subdistrict, district, province, postal_code, user_email, user_phone) 
        VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssss", $user_type, $username, $password, $first_name, $last_name, $birthday, $address, $subdistrict, $district, $province, $postal_code, $user_email, $user_phone);
    
    function containsEnglish($text) {
        // ถ้ามีตัวอักษรภาษาอังกฤษในข้อความ ให้คืนค่า true
        return preg_match('/[a-zA-Z]/', $text);
    }
    
    if(10 !== strlen($user_phone)) {
        echo "<script>
            alert('Phone number must be 10 number.');
            window.history.back();
        </script>";
        exit();
    }
    
    // ตรวจสอบ Subdistrict
    if (containsEnglish($subdistrict)) {
        echo "<script>
            alert('Subdistrict must not contain English letters.');
            window.history.back();
        </script>";
        exit();
    }
    
    // ตรวจสอบ District
    if (containsEnglish($district)) {
        echo "<script>
            alert('District must not contain English letters.');
            window.history.back();
        </script>";
        exit();
    }
    
    // ตรวจสอบ Province
    if (containsEnglish($province)) {
        echo "<script>
            alert('Province must not contain English letters.');
            window.history.back();
        </script>";
        exit();
    }


    if ($stmt->execute()) {
        echo "<script>
            alert('Registration completed successfully!');
            window.location.href = 'login.php';
        </script>";
    } else {
        echo "<script>
            alert('Registration failed. Please contact support.');
            window.history.back();
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Company</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript"
            src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
    <script type="text/javascript"
            src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
    <link rel="stylesheet"
          href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
    <script type="text/javascript"
            src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>
</head>
<body class="bg-[#f5d472]">
<div class="max-w-lg mx-auto mt-12 bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Register as Company</h2>
    <form action="registerCom.php" method="POST">
        <input type="hidden" name="user_type" value="2">

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
            <label for="first_name" class="block text-gray-700 font-medium mb-2">Company Name:</label>
            <input
                    type="text"
                    name="first_name"
                    id="first_name"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
        </div>

        <div class="mb-4">
            <label for="user_email" class="block text-gray-700 font-medium mb-2">Email:</label>
            <input
                    type="email"
                    name="user_email"
                    id="user_email"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
        </div>

        <div class="mb-4">
            <label for="user_phone" class="block text-gray-700 font-medium mb-2">Phone:</label>
            <input
                    type="text"
                    name="user_phone"
                    id="user_phone"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
        </div>

        <div class="mb-4">
            <label for="address" class="block text-gray-700 font-medium mb-2">Address:</label>
            <input
                    type="text"
                    name="address"
                    id="address"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
        </div>

        <div class="mb-4">
            <label for="subdistrict" class="block text-gray-700 font-medium mb-2">Subdistrict:</label>
            <input
                    type="text"
                    name="subdistrict"
                    id="district"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
        </div>

        <div class="mb-4">
            <label for="district" class="block text-gray-700 font-medium mb-2">District:</label>
            <input
                    type="text"
                    name="district"
                    id="amphoe"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
        </div>

        <div class="mb-4">
            <label for="province" class="block text-gray-700 font-medium mb-2">Province:</label>
            <input
                    type="text"
                    name="province"
                    id="province"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
        </div>

        <div class="mb-4">
            <label for="postal_code" class="block text-gray-700 font-medium mb-2">Postal Code:</label>
            <input
                    type="text"
                    name="postal_code"
                    id="zipcode"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
        </div>

        <div class="mb-4">
            <button
                    type="submit"
                    class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Register
            </button>
        </div>
    </form>
</div>
<script>
    $.Thailand({
        $district: $('#district'),
        $amphoe: $('#amphoe'),
        $province: $('#province'),
        $zipcode: $('#zipcode'),
    });
</script>
</body>
</html>
