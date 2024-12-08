<?php
session_start();
require 'databaseConnect.php';

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
    <style>
        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form input, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        form button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Apply for a Job</h2>



<form action="uploadCV.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_id); ?>">
    <input type="file" name="cv" accept=".pdf,.doc,.docx" required>
    <small style="color: red; display: block; margin-top: 5px;">
        Only PDF, DOC, and DOCX files are allowed.
    </small>
    <button type="submit">Submit Application</button>
</form>

</body>
</html>
