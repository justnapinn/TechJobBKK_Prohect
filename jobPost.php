<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job thai</title>
    <style>
        .apply-button {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .apply-button:hover {
            background-color: #0056b3;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        h1 {
            margin-bottom: 30px;
            color: #333;
        }
        .job-container {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .job-section {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            padding: 15px;
        }
        .job-section h3 {
            margin: 0 0 10px;
            font-size: 1.4em;
            color: #007bff;
        }
        .job-section p {
            margin: 0;
            color: #555;
        }
        .apply-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            font-size: 1em;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-top: 20px;
            text-align: center;
        }
        .apply-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<?php
session_start();
require 'databaseConnect.php';
$job_id = isset($_GET['job_id']) ? $_GET['job_id'] : '';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in first.");
}

$user_id = $_SESSION['user_id'];

$sql_company = "SELECT j.job_id, u.first_name
                FROM jobs j
                INNER JOIN users u ON j.user_id = u.user_id
                WHERE j.job_id = ?";
$stmt = $conn->prepare($sql_company);
$stmt->bind_param("s", $job_id);
$stmt->execute();
$result_company = $stmt->get_result();
if ($result_company->num_rows > 0) {
    $row_company = $result_company->fetch_assoc();
    echo "<h1>" . htmlspecialchars($row_company["first_name"]) . "</h1>";
} else {
    echo "<h1>ไม่พบข้อมูลบริษัท</h1>";
}

$sql = "SELECT j.job_id, j.title, j.description, u.first_name, j.welfare, j.contact
        FROM jobs j
        INNER JOIN users u ON j.user_id = u.user_id
        WHERE j.job_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<div class='job-container'>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='job-section'>";
        echo "<h3>Job Title</h3>";
        echo "<p>" . nl2br(htmlspecialchars($row["title"])) . "</p>";
        echo "</div>";

        echo "<div class='job-section'>";
        echo "<h3>Description</h3>";
        echo "<p>" . nl2br(htmlspecialchars($row["description"])) . "</p>";
        echo "</div>";

        echo "<div class='job-section'>";
        echo "<h3>Welfare</h3>";
        echo "<p>" . nl2br(htmlspecialchars($row["welfare"])) . "</p>";
        echo "</div>";

        echo "<div class='job-section'>";
        echo "<h3>Contact</h3>";
        echo "<p>" . nl2br(htmlspecialchars($row["contact"])) . "</p>";
        echo "</div>";
    }
    echo "<a href='apply.php?job_id=" . urlencode($job_id) . "&user_id=" . urlencode($_SESSION['user_id']) . "' class='apply-button'>Apply</a>";
    echo "</div>";
} else {
    echo "<p>Not Found</p>";
}

$conn->close();
?>

</body>
</html>