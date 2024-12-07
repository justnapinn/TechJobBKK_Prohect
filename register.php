<?php
require 'databaseConnect.php';

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
    $postal_code = $_POST['postal_code'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];

    // ตรวจสอบวันเกิด
    $current_date = date('Y-m-d'); // วันที่ปัจจุบันในรูปแบบ YYYY-MM-DD
    if ($birthday >= $current_date) {
        header("Location: register.html?error=Invalid+birthdate.+Birthdate+must+be+before+today.");
        exit();
    }

    // ตรวจสอบอีเมลว่าลงท้ายด้วย @gmail.com 
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $user_email)) {
        header("Location: register.html?error=Invalid+email.+Email+must+be+a+valid+@gmail.com+address.");
        exit();
    }


    if ($user_type == 1) {
        $user_type = 'applicant';
    } elseif ($user_type == 2) {
        $user_type = 'company';
    } else {
        $user_type = 'admin';
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR user_email = ? OR user_phone = ? OR (first_name = ? AND last_name = ?)");
    $stmt->bind_param("sssss", $username, $user_email, $user_phone, $first_name, $last_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Username or Email has been used. Please try again.');
            window.location.href = 'register.html';
        </script>";
        exit();
    } else {
        if ($stmt->execute()) {
            echo "<script>
                alert('Registration completed!');
                window.location.href = 'login.html';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('Registration failed. Please try again.');
                window.location.href = 'register.html';
            </script>";
            exit();
        }
    }

    $stmt->close();
    $conn->close();
}
?>
