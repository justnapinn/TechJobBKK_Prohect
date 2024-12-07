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
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "final_de251";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql_company = "SELECT first_name FROM users WHERE user_id = '1'";
$result_company = $conn->query($sql_company);
$row_company = $result_company->fetch_assoc();

// แสดงชื่อบริษัทใน Header
echo "<h1>" . htmlspecialchars($row_company["first_name"]) . "</h1>";

$sql = "SELECT j.job_id, j.title, j.description, u.first_name , j.welfare , j.contact
        FROM jobs j
        INNER JOIN users u ON j.user_id = u.user_id
        WHERE j.job_id = '1'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='job-container'>";
    while($row = $result->fetch_assoc()) {
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
    echo "<a href='apply.php' class='apply-button'>Apply</a>";
    echo "</div>";
} else {
    echo "<p>Not Found</p>";
}

$conn->close();
?>

</body>
</html>