<?php
require_once 'databaseConnect.php';
include('navbar.php');

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = 1; // รับประเภทผู้ใช้
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = ''; // กำหนดค่าเริ่มต้น

    // ตรวจสอบประเภทผู้ใช้และจัดการ last_name
    if ($user_type == 1) { // Applicant
        $last_name = 'Company'; // เก็บ last_name ตามปกติ
    } 

    $birthday = $_POST['birthday'];
    $address = $_POST['address'];
    $subdistrict = $_POST['subdistrict'];
    $district = $_POST['district'];
    $province = $_POST['province'];
    $postal_code = $_POST['postal_code'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];

    // Validate email format
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('Invalid email format. Please try again.');
            window.history.back();
        </script>";
        exit();
    }

    // ตรวจสอบข้อมูลซ้ำ
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR user_email = ? OR user_phone = ?");
    $stmt->bind_param("sss", $username, $user_email, $user_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Username, email, or phone number is already taken.');
            window.history.back();
        </script>";
        exit();
    }

    $stmt->close();

    // บันทึกข้อมูลในฐานข้อมูล
    $stmt = $conn->prepare("
        INSERT INTO users 
        (user_id, user_type, username, password, first_name, last_name, birthday, address, subdistrict, district, province, postal_code, user_email, user_phone) 
        VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssss", $user_type, $username, $password, $first_name, $last_name, $birthday, $address, $subdistrict, $district, $province, $postal_code, $user_email, $user_phone);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registration successful!');
            window.location.href = 'login.html';
        </script>";
    } else {
        echo "<script>
            alert('Registration failed. Please try again.');
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Register as Company</h2>
    <form action="registerCom.php" method="POST">
        <input type="hidden" name="user_type" value="2"> <!-- User type for Company -->

        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <div class="mb-3">
            <label for="first_name" class="form-label">Company Name:</label>
            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your company name" required>
        </div>
        <div class="mb-3">
            <label for="birthday" class="form-label">Established Date:</label>
            <input type="date" name="birthday" id="birthday" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <input type="text" name="address" id="address" class="form-control" placeholder="Enter your address" required>
        </div>
        <div class="mb-3">
            <label for="subdistrict" class="form-label">Subdistrict:</label>
            <input type="text" name="subdistrict" id="subdistrict" class="form-control" placeholder="Enter your subdistrict" required>
        </div>
        <div class="mb-3">
            <label for="district" class="form-label">District:</label>
            <input type="text" name="district" id="district" class="form-control" placeholder="Enter your district" required>
        </div>
        <div class="mb-3">
            <label for="province" class="form-label">Province:</label>
            <input type="text" name="province" id="province" class="form-control" placeholder="Enter your province" required>
        </div>
        <div class="mb-3">
            <label for="postal_code" class="form-label">Postal Code:</label>
            <input type="text" name="postal_code" id="postal_code" class="form-control" placeholder="Enter your postal code" required>
        </div>
        <div class="mb-3">
            <label for="user_email" class="form-label">Email:</label>
            <input type="email" name="user_email" id="user_email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label for="user_phone" class="form-label">Phone:</label>
            <input type="text" name="user_phone" id="user_phone" class="form-control" placeholder="Enter your phone number" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
</div>
</body>
</html>