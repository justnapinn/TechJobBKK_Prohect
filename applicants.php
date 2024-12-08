<?php
session_start();
require_once 'databaseConnect.php';
include('navbar.php');

// Check if the user is logged in and has 'company' user_type
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    header("Location: login.php");
    exit();
}

// Get the current logged-in company's user ID
$company_id = $_SESSION['user_id'];

// Fetch applications for jobs created by the current company
$sql = "
    SELECT 
        a.app_id,
        u.first_name,
        u.last_name,
        TIMESTAMPDIFF(YEAR, u.birthday, CURDATE()) AS age,
        u.user_email,
        u.user_phone,
        a.resume,
        a.applied_at,
        a.status
    FROM 
        applications a
    JOIN 
        jobs j ON a.job_id = j.job_id
    JOIN 
        users u ON a.user_id = u.user_id
    WHERE 
        j.user_id = ?
    ORDER BY 
        a.applied_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $company_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS file -->
</head>
<body>
<h1>Applicants</h1>
<table border="1">
    <thead>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Age</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Resume</th>
        <th>Applied At</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['first_name']) ?></td>
            <td><?= htmlspecialchars($row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['age']) ?></td>
            <td><?= htmlspecialchars($row['user_email']) ?></td>
            <td><?= htmlspecialchars($row['user_phone']) ?></td>
            <td><a href="<?= htmlspecialchars($row['resume']) ?>" target="_blank">View Resume</a></td>
            <td><?= htmlspecialchars($row['applied_at']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
