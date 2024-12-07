<?php
require_once 'databaseConnect.php';
include('navbar.php');

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = $_POST['user_type'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $address = $_POST['address'];
    $subdistrict = $_POST['subdistrict'];
    $district = $_POST['district'];
    $province = $_POST['province'];
    $postal_code = $_POST['postal_code'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];

    // Check date of birth
    $current_date = date('Y-m-d'); // Current date in format YYYY-MM-DD
    if ($birthday >= $current_date) {
        header("Location: register.php?error=Invalid+birthdate.+Birthdate+must+be+before+today.");
        exit();
    }

    // Check the email to see if it ends with @[alphabet].[alphabet]
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]+$/', $user_email)) {
        header("Location: register.php?error=Invalid+email.+Email+must+be+a+valid+email+address.");
        exit();
    }

    // Convert user_type from integer to enum
    if ($user_type == 1) {
        $user_type = 'applicant';
    } elseif ($user_type == 2) {
        $user_type = 'company';
        $last_name = 'Company';
    } else {
        $user_type = 'admin';
    }

    // check for duplicate data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR user_email = ? OR user_phone = ? OR (first_name = ? AND last_name = ?)");
    $stmt->bind_param("sssss", $username, $user_email, $user_phone, $first_name, $last_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Username or Email has been used. Please try again.');
            window.location.href = 'register.php';
        </script>";
        exit();
    }

    $stmt->close();

    // save new row in database
    $stmt = $conn->prepare("INSERT INTO users (user_id, user_type, username, password, first_name, last_name, birthday, address, subdistrict, district,province, postal_code, user_email, user_phone) VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
    $stmt->bind_param("sssssssssssss", $user_type, $username, $password, $first_name, $last_name, $birthday, $address, $subdistrict, $district, $province, $postal_code, $user_email, $user_phone);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registration completed!');
            window.location.href = 'login.html';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Registration failed. Please try again.');
            window.location.href = 'register.php';
        </script>";
        exit();
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
    <title>TechJobBKK - Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f5d472]">
<?php generateNavbar(); ?>
<div class="max-w-lg mx-auto mt-12 bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-center text-2xl font-bold text-gray-800 mb-6">Register to TechJobBKK</h2>
    <form method="POST" class="space-y-4">
        <div>
            <label for="user_type" class="block text-sm font-medium text-gray-700">User Type:</label>
            <select name="user_type" id="user_type"
                    class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="1">Applicant</option>
                <option value="2">Company</option>
            </select>
        </div>
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username:</label>
            <input type="text" name="username" id="username"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Username" required>
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
            <input type="password" name="password" id="password"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Password" required>
        </div>
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name or Company Name:</label>
            <input type="text" name="first_name" id="first_name"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="First Name or Company Name" required>
        </div>
        <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
            <input type="text" name="last_name" id="last_name"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Last Name">
        </div>
        <div>
            <label for="birthday" class="block text-sm font-medium text-gray-700">Birthday:</label>
            <input type="date" name="birthday" id="birthday"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   required>
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-gray-700">Address:</label>
            <input type="text" name="address" id="address"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Address" required>
        </div>
        <div>
            <label for="subdistrict" class="block text-sm font-medium text-gray-700">Subdistrict:</label>
            <input type="text" name="subdistrict" id="subdistrict"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Subdistrict" required>
        </div>
        <div>
            <label for="district" class="block text-sm font-medium text-gray-700">District:</label>
            <input type="text" name="district" id="district"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="District" required>
        </div>
        <div>
            <label for="province" class="block text-sm font-medium text-gray-700">Province:</label>
            <input type="text" name="province" id="province"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Province" required>
        </div>
        <div>
            <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code:</label>
            <input type="text" name="postal_code" id="postal_code"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Postal Code" required>
        </div>
        <div>
            <label for="user_email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" name="user_email" id="user_email"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Email" required>
        </div>
        <div>
            <label for="user_phone" class="block text-sm font-medium text-gray-700">Phone:</label>
            <input type="text" name="user_phone" id="user_phone"
                   class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Phone" required>
        </div>
        <div>
            <button type="submit" class="w-full bg-blue-500 text-white font-semibold py-2 rounded-md hover:bg-blue-600">
                Register
            </button>
        </div>
        <p class="text-center text-sm text-gray-600">Already have an account? <a href="login.html"
                                                                                 class="text-blue-500 hover:underline">Login
                here</a></p>
    </form>
</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        alert(decodeURIComponent(urlParams.get('error')));
    }
    if (urlParams.has('success')) {
        alert(decodeURIComponent(urlParams.get('success')));
    }
</script>
</body>
</html>