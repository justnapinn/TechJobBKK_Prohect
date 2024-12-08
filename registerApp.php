<?php
require 'databaseConnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = 2;
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $birthday = $_POST['birthday'];
    $address = trim($_POST['address']);
    $subdistrict = trim($_POST['subdistrict']);
    $district = trim($_POST['district']);
    $province = trim($_POST['province']);
    $postal_code = trim($_POST['postal_code']);
    $user_email = trim($_POST['user_email']);
    $user_phone = trim($_POST['user_phone']);

    // Validate Birthday
    if ($birthday >= date('Y-m-d')) {
        echo "<script>
            alert('Invalid birthdate. Please select a valid date before today.');
            window.history.back();
        </script>";
        exit();
    }

    // Validate Email
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('Invalid email address. Please try again.');
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
            alert('Username, Email, or Phone number is already in use. Please try again.');
            window.history.back();
        </script>";
        exit();
    }

    $stmt->close();

    // Insert new record
    $stmt = $conn->prepare("INSERT INTO users (user_id, user_type, username, password, first_name, last_name, birthday, address, subdistrict, district, province, postal_code, user_email, user_phone) VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssss", $user_type, $username, $password, $first_name, $last_name, $birthday, $address, $subdistrict, $district, $province, $postal_code, $user_email, $user_phone);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registration completed successfully!');
            window.location.href = 'login.html';
        </script>";
    } else {
        error_log("Insert Error: " . $stmt->error);
        echo "<script>
            alert('Registration failed. Please contact support.');
            window.location.href = 'register.php';
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
    <title>Register Applicant</title>
</head>
<body>
<div class="container mt-5">
        <h2 class="text-center mb-4">Register as Applicant</h2>
        <form action="registerApp.php" method="POST">
            <input type="hidden" name="user_type" value="1"> <!-- User type for Applicant -->

            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your first name" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter your last name" required>
            </div>
            <div class="mb-3">
                <label for="birthday" class="form-label">Birthday:</label>
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
