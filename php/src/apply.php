<?php
session_start();
require 'databaseConnect.php';
include('navbar.php');
include('checkLogin.php');

$job_id = isset($_GET['job_id']) ? $_GET['job_id'] : '';
$user_id = $_SESSION['user_id'];

if (empty($job_id) || empty($user_id)) {
    die("Job ID or User ID is missing.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<h2 class="text-3xl font-bold text-center text-gray-800 my-8">Apply for a Job</h2>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
    <form action="uploadCV.php" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_id); ?>">

        <div>
            <label for="cv" class="block text-lg font-semibold text-gray-700">Upload Your CV</label>
            <input type="file" name="cv" accept=".pdf,.doc,.docx" required
                   class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <small class="text-red-500 block mt-2">Only PDF, DOC, and DOCX files are allowed.</small>
        </div>

        <button type="submit"
                class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Submit Application
        </button>
    </form>
</div>

</body>
</html>
