<?php
session_start();
require_once 'databaseConnect.php';

// Check if user is logged in
//if (!isset($_SESSION['user_id'])) {
//    header("Location: login.php");
//    exit();
//}

$user_id = '1';
//$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $birthday = $_POST['birthday'];
    $address = htmlspecialchars(trim($_POST['address']));
    $subdistrict = htmlspecialchars(trim($_POST['subdistrict']));
    $district = htmlspecialchars(trim($_POST['district']));
    $postal_code = htmlspecialchars(trim($_POST['postal_code']));
    $user_email = filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL);
    $user_phone = htmlspecialchars(trim($_POST['user_phone']));

    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($birthday) ||
        empty($address) || empty($subdistrict) || empty($district) ||
        empty($postal_code) || !$user_email || empty($user_phone)) {
        $error_message = "All fields are required.";
    } else {
        // Update user data
        $stmt = $conn->prepare("UPDATE users SET 
            first_name = ?, 
            last_name = ?, 
            birthday = ?, 
            address = ?, 
            subdistrict = ?, 
            district = ?, 
            postal_code = ?, 
            user_email = ?, 
            user_phone = ? 
            WHERE user_id = ?");

        $stmt->bind_param("ssssssssss",
            $first_name, $last_name, $birthday,
            $address, $subdistrict, $district,
            $postal_code, $user_email, $user_phone, $user_id);

        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
            // Refresh user data
            $user_data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'birthday' => $birthday,
                'address' => $address,
                'subdistrict' => $subdistrict,
                'district' => $district,
                'postal_code' => $postal_code,
                'user_email' => $user_email,
                'user_phone' => $user_phone
            ];
        } else {
            $error_message = "Update failed. " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Profile</h2>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control"
                       value="<?php echo htmlspecialchars($user_data['first_name']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control"
                       value="<?php echo htmlspecialchars($user_data['last_name']); ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Birthday</label>
                <input type="date" name="birthday" class="form-control"
                       value="<?php echo $user_data['birthday']; ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="user_email" class="form-control"
                       value="<?php echo htmlspecialchars($user_data['user_email']); ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Phone</label>
                <input type="tel" name="user_phone" class="form-control"
                       value="<?php echo htmlspecialchars($user_data['user_phone']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Postal Code</label>
                <input type="text" name="postal_code" class="form-control"
                       value="<?php echo htmlspecialchars($user_data['postal_code']); ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Address</label>
            <input type="text" name="address" class="form-control"
                   value="<?php echo htmlspecialchars($user_data['address']); ?>" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Subdistrict</label>
                <input type="text" name="subdistrict" class="form-control"
                       value="<?php echo htmlspecialchars($user_data['subdistrict']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>District</label>
                <input type="text" name="district" class="form-control"
                       value="<?php echo htmlspecialchars($user_data['district']); ?>" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
</body>
</html>