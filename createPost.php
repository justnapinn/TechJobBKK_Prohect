<?php
session_start();
require_once 'databaseConnect.php';

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

    if (empty($title) || empty($job_type) || empty($description)) {
        $error_message = "All fields are required.";
    } elseif (strlen($description) > 2000) {
        $error_message = "Job description cannot exceed 2000 characters.";
    } else {
        $job_id = uniqid('JOB_');

        $stmt = $conn->prepare("INSERT INTO jobs (job_id, user_id, title, job_type, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $job_id, $user_id, $title, $job_type, $description);

        if ($stmt->execute()) {
            $success_message = "Job post created successfully!";
            $title = $job_type = $description = '';
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
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="w-full max-w-xl bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Create Job Post</h2>

    <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

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
                <option value="Hybrid">Hybrid</option>
                <option value="Work-From-Home">Work-From-Home</option>
                <option value="Onsite">Onsite</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Job Description</label>
            <textarea name="description" rows="5"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      maxlength="2000" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
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
</body>
</html>
