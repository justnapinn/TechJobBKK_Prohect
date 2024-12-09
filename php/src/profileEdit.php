<?php
session_start();
require_once 'databaseConnect.php';
include('navbar.php');
include('checkLogin.php');

$user_id = $_SESSION['user_id'];
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
    // Sanitize and validate input (with additional validations)
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = !empty(trim($_POST['last_name'])) ? htmlspecialchars(trim($_POST['last_name'])) : $user_data['last_name'];
    $birthday = $_POST['birthday'];
    $address = htmlspecialchars(trim($_POST['address']));
    $subdistrict = htmlspecialchars(trim($_POST['subdistrict']));
    $district = htmlspecialchars(trim($_POST['district']));
    $province = htmlspecialchars(trim($_POST['province']));
    $postal_code = htmlspecialchars(trim($_POST['postal_code'])) ? htmlspecialchars(trim($_POST['postal_code'])) : $user_data['postal_code'];
    $user_email = filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL);
    $user_phone = htmlspecialchars(trim($_POST['user_phone']));

    // Comprehensive validations
    $errors = [];
    if (empty($first_name)) $errors[] = "First name is required.";
    if (empty($last_name)) $errors[] = "Last name is required.";
    if (empty($birthday) || strtotime($birthday) > time()) $errors[] = "Invalid birthday.";
    if (empty($address)) $errors[] = "Address is required.";
    if (!$user_email) $errors[] = "Invalid email address.";
    if (!preg_match('/^[0-9]{10}$/', $user_phone)) $errors[] = "Invalid phone number.";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE users SET 
        first_name = ?, 
        last_name = ?, 
        birthday = ?, 
        address = ?, 
        subdistrict = ?, 
        district = ?, 
        province = ?, 
        postal_code = ?, 
        user_email = ?, 
        user_phone = ? 
        WHERE user_id = ?");

        $stmt->bind_param("sssssssssss",
            $first_name, $last_name, $birthday, $address, $subdistrict,
            $district, $province, $postal_code, $user_email,
            $user_phone, $user_id);

        if ($stmt->execute()) {
            // Refresh the $user_data variable to fetch the updated data
            $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();

            // Redirect to profile.php after successful update
            header("Location: profile.php");
            exit();
        } else {
            $error_message = "Update failed: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
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

<body class="bg-gray-100 min-h-screen items-center justify-center">
<div class="w-full max-w-xl bg-white p-8 rounded-lg shadow-md mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Profile</h2>
    <form method="POST" id="profileForm" class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
    <?php if ($user_data['user_type'] === 'company'): ?>
        <!-- If user is company -->
        <div class="col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">Company Name</label>
            <input type="text" name="first_name"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   value="<?php echo htmlspecialchars($user_data['first_name']); ?>" required>
        </div>
    <?php else: ?>
        <!-- If user is applicant -->
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
            <input type="text" name="first_name"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   value="<?php echo htmlspecialchars($user_data['first_name']); ?>" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
            <input type="text" name="last_name"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   value="<?php echo htmlspecialchars($user_data['last_name']); ?>" required>
        </div>
    <?php endif; ?>
</div>


        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Birthday</label>
                <input type="date" name="birthday"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo $user_data['birthday']; ?>" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="user_email"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo htmlspecialchars($user_data['user_email']); ?>" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                <input type="tel" name="user_phone"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo htmlspecialchars($user_data['user_phone']); ?>" required>
            </div>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Address</label>
            <textarea name="address" rows="3"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      required><?php echo htmlspecialchars($user_data['address']); ?></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Sub district</label>
                <input type="text" name="subdistrict"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="district"
                       value="<?php echo htmlspecialchars($user_data['subdistrict']); ?>" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">District</label>
                <input type="text" name="district"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="amphoe"
                       value="<?php echo htmlspecialchars($user_data['district']); ?>" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Province</label>
                <input type="text" name="province"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="province"
                       value="<?php echo htmlspecialchars($user_data['province'] ?? ''); ?>" required>
            </div>
            <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Postal Code</label>
            <input type="text" name="postal_code"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="zipcode"
                       value="<?php echo htmlspecialchars($user_data['postal_code'] ?? ''); ?>" required>
                
            </div>
        </div>
        
        <div class="flex items-center justify-center">
            <button type="submit" id="updateButton"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    disabled>
                Save
            </button>
        </div>
    </form>
</div>

<!-- Notification Popup -->
<div id="notification" class="fixed top-4 right-4 z-50 hidden">
    <div id="notificationContent"
         class="py-4 px-6 rounded-lg shadow-lg text-white transition-all duration-300 ease-in-out"></div>
</div>

<script>
    // Thailand.js integration
    $.Thailand({
    $district: $('#district'),
    $amphoe: $('#amphoe'),
    $province: $('#province'),
    $zipcode: $('#zipcode')
    });

    // Function to check if any form values have changed
    function checkFormChanges() {
        const form = document.getElementById('profileForm');
        const updateButton = document.getElementById('updateButton');

        // Get all input and textarea elements
        const inputs = form.querySelectorAll('input, textarea');
        let hasChanges = false;

        // Check each input for changes
        inputs.forEach(input => {
            const originalValue = input.getAttribute('data-original-value');
            const currentValue = input.value.trim();

            if (originalValue !== currentValue) {
                hasChanges = true;
            }
        });

        // Enable/disable button based on changes
        updateButton.disabled = !hasChanges;

        // Update button styling
        if (hasChanges) {
            updateButton.classList.remove('opacity-50', 'cursor-not-allowed');
            updateButton.classList.add('hover:bg-blue-700');
        } else {
            updateButton.classList.add('opacity-50', 'cursor-not-allowed');
            updateButton.classList.remove('hover:bg-blue-700');
        }
    }

    // Add event listeners to all form inputs
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('profileForm');
        const inputs = form.querySelectorAll('input, textarea');

        inputs.forEach(input => {
            // Store original value as a data attribute
            input.setAttribute('data-original-value', input.value.trim());

            // Add input event listener
            input.addEventListener('input', checkFormChanges);
        });

        // Initial check
        checkFormChanges();
    });

    // Function to show notification
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const notificationContent = document.getElementById('notificationContent');

        // Reset classes
        notificationContent.classList.remove('bg-green-500', 'bg-red-500');

        // Set color based on type
        if (type === 'success') {
            notificationContent.classList.add('bg-green-500');
        } else {
            notificationContent.classList.add('bg-red-500');
        }

        // Set message
        notificationContent.textContent = message;

        // Show notification
        notification.classList.remove('hidden');

        // Auto-hide after 3 seconds
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 3000);
    }

    // Check for PHP messages and show notification
    <?php if (!empty($success_message)): ?>
    showNotification('<?php echo htmlspecialchars($success_message, ENT_QUOTES); ?>', 'success');
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
    showNotification('<?php echo htmlspecialchars($error_message, ENT_QUOTES); ?>', 'error');
    <?php endif; ?>
</script>
</body>
</html>