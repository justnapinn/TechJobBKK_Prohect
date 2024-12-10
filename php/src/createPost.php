<?php
session_start();
require_once 'databaseConnect.php';
include('navbar.php');

// Check if user is logged in or not authorized
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $job_type = $_POST['job_type'];
    $description = htmlspecialchars(trim($_POST['description']));
    $welfare = htmlspecialchars(trim($_POST['welfare']));
    $contact = htmlspecialchars(trim($_POST['contact']));

    if (empty($title) || empty($job_type) || empty($description) || empty($welfare) || empty($contact)) {
        $error_message = "All fields are required.";
    } elseif (strlen($description) > 2000 || strlen($welfare) > 2000 || strlen($contact) > 2000) {
        $error_message = "Text fields cannot exceed 2000 characters.";
    } else {
        $job_id = uniqid('JOB_');

        $stmt = $conn->prepare("INSERT INTO jobs (job_id, user_id, title, job_type, description, welfare, contact) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $job_id, $user_id, $title, $job_type, $description, $welfare, $contact);

        if ($stmt->execute()) {
            $success_message = "Job post created successfully!";
            $title = $job_type = $description = $welfare = $contact = '';
        } else {
            $error_message = "Job post creation failed. " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Job Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="w-full max-w-xl bg-white p-8 rounded-lg shadow-md mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-center">Create Job Post</h2>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Job Title</label>
            <input type="text" name="title"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   value="<?php echo htmlspecialchars($title ?? ''); ?>" required>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Job Type</label>
            <select name="job_type"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                <option value="">Select Job Type</option>
                <option value="hybrid">Hybrid</option>
                <option value="remote">Work-From-Home</option>
                <option value="onsite">Onsite</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Job Description</label>
            <textarea name="description" rows="5"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      maxlength="2000" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
            <p class="text-gray-600 text-xs italic">Maximum 2000 characters</p>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Welfare Benefits</label>
            <textarea name="welfare" rows="4"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      maxlength="2000" required><?php echo htmlspecialchars($welfare ?? ''); ?></textarea>
            <p class="text-gray-600 text-xs italic">Maximum 2000 characters</p>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Contact Information</label>
            <textarea name="contact" rows="3"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      maxlength="2000" required><?php echo htmlspecialchars($contact ?? ''); ?></textarea>
            <p class="text-gray-600 text-xs italic">Maximum 2000 characters</p>
        </div>

        <div class="flex items-center justify-center">
            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Create Job Post
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
    showNotification('<?php echo $success_message; ?>', 'success');
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
    showNotification('<?php echo $error_message; ?>', 'error');
    <?php endif; ?>
</script>
</body>
</html>