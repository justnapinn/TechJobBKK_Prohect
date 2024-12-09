<?php
ob_start();
session_start();
require_once 'databaseConnect.php';
include('navbar.php');
include('checkLogin.php');

// Disable caching for this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $file_tmp = $_FILES['profile_picture']['tmp_name'];
    $file_name = basename($_FILES['profile_picture']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Validate file
    if (in_array($file_ext, $allowed_extensions)) {
        $upload_dir = 'uploads/logos/';

        // Ensure upload directory exists
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $new_file_name = $user_id . '_logo.' . $file_ext;
        $logo_path = $upload_dir . $new_file_name;

        // Remove old logo if it exists
        if (!empty($user_data['logo']) && file_exists($user_data['logo'])) {
            unlink($user_data['logo']);
        }

        // Move uploaded file
        if (move_uploaded_file($file_tmp, $logo_path)) {
            // Update database with new logo path
            $stmt = $conn->prepare("UPDATE users SET logo = ? WHERE user_id = ?");
            $stmt->bind_param("ss", $logo_path, $user_id);

            if ($stmt->execute()) {
                // Refresh user data
                $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user_data = $result->fetch_assoc();

                $success_message = "Profile picture updated successfully!";
            } else {
                $error_message = "Failed to update profile picture in database.";
            }
        } else {
            $error_message = "Failed to upload profile picture.";
        }
    } else {
        $error_message = "Invalid file format. Allowed formats: jpg, jpeg, png, gif.";
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add meta tags to further prevent caching -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- When loading profile photo, add a timestamp to force reload -->
    <script>
        function loadProfileImage() {
            var profileImg = document.getElementById('profileImage');
            if (profileImg) {
                // Add current timestamp to image source to prevent caching
                profileImg.src = profileImg.src + '?t=' + new Date().getTime();
            }
        }

        // Call on page load
        window.onload = loadProfileImage;
    </script>
</head>
<body class="bg-pink-100 min-h-screen items-center justify-center">
<div class="w-[30rem] h-[25rem] fixed top-[60px] bg-white p-8 shadow-md z-10 ml-[32rem] mt-[7rem] rounded-lg">
    <div class="flex items-center ml-[10rem]">
        <div class="ml-[-0.9rem] relative group ">
            <?php if (!empty($user_data['logo'])): ?>
                <img
                        src="<?php echo htmlspecialchars($user_data['logo']); ?>"
                        alt="Profile Picture"
                        class="w-32 h-32 rounded-full object-cover border-4 border-blue-500"
                        id="profileImage"
                >
            <?php else: ?>
                <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-gray-500">No Image</span>
                </div>
            <?php endif; ?>

            <!-- Upload Button Overlay -->
            <label for="profile_picture" class="absolute inset-0 bg-black bg-opacity-50 rounded-full
                flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                <span class="text-white text-sm">Change Photo</span>
                <input type="file" name="profile_picture" id="profile_picture"
                       class="hidden" accept="image/*" onchange="uploadProfilePicture(event)">
            </label>
        </div>


    </div>

    <div class="flex flex-col items-center justify-center text-center">
        <h2 class="mt-[0.5rem] text-2xl font-bold text-gray-800">
            <?php echo htmlspecialchars($user_data['first_name']); ?>
        </h2>
        <h2 class="mt-[1rem] text-2xl font-bold text-gray-800">
            <?php echo htmlspecialchars($user_data['last_name']); ?>
        </h2>
        <p class="mt-[1rem] text-gray-600">
            <?php echo htmlspecialchars($user_data['user_email']); ?>
        </p>
    </div>


    <a href="profileEdit.php"
       class="mt-[1.5rem] ml-[9.5rem] inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
        Edit Profile
    </a>

    <!-- Notification Area -->
    <div id="notification" class="fixed top-4 right-4 z-50 hidden">
        <div id="notificationContent"
             class="py-4 px-6 rounded-lg shadow-lg text-white transition-all duration-300 ease-in-out">
        </div>
    </div>
</div>

<script>
    function uploadProfilePicture(event) {
        const file = event.target.files[0];
        const formData = new FormData();
        formData.append('profile_picture', file);

        fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(result => {
                // Reload the page to reflect changes
                window.location.reload();
            })
            .catch(error => {
                showNotification('Upload failed', 'error');
            });
    }

    // Notification function
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

    // Show PHP messages if any
    <?php if (!empty($success_message)): ?>
    showNotification('<?php echo htmlspecialchars($success_message); ?>', 'success');
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
    showNotification('<?php echo htmlspecialchars($error_message); ?>', 'error');
    <?php endif; ?>
</script>
</body>
</html>