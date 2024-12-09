<?php
session_start();
require_once 'databaseConnect.php';
include('navbar.php');

// Check if user is logged in as an applicant
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'applicant') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// SQL to fetch jobs applied by the current user
$sql = "
    SELECT 
        j.job_id,
        j.title,
        j.job_type,
        j.description,
        j.welfare,
        j.contact,
        u.first_name AS company_first_name,
        u.last_name AS company_last_name,
        a.status,
        a.applied_at
    FROM 
        applications a
    JOIN 
        jobs j ON a.job_id = j.job_id
    JOIN 
        users u ON j.user_id = u.user_id
    WHERE 
        a.user_id = ?
    ORDER BY 
        a.applied_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Jobs</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 min-h-screen font-sans">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">My Applied Jobs</h1>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <?php if ($result->num_rows > 0): ?>
                <table class="min-w-full bg-white">
                    <thead>
                    <tr class="bg-gray-200 text-gray-600 text-sm uppercase font-semibold">
                        <th class="py-3 px-6 text-left">Job Title</th>
                        <th class="py-3 px-6 text-left">Company</th>
                        <th class="py-3 px-6 text-left">Job Type</th>
                        <th class="py-3 px-6 text-left">Description</th>
                        <th class="py-3 px-6 text-left">Welfare</th>
                        <th class="py-3 px-6 text-left">Contact</th>
                        <th class="py-3 px-6 text-left">Applied At</th>
                        <th class="py-3 px-6 text-left">Status</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-6"><?= htmlspecialchars($row['title']) ?></td>
                            <td class="py-3 px-6"><?= htmlspecialchars($row['company_first_name'] . ' ' . $row['company_last_name']) ?></td>
                            <td class="py-3 px-6"><?= htmlspecialchars($row['job_type']) ?></td>
                            <td class="py-3 px-6"><?= htmlspecialchars($row['description']) ?></td>
                            <td class="py-3 px-6"><?= htmlspecialchars($row['welfare']) ?></td>
                            <td class="py-3 px-6"><?= htmlspecialchars($row['contact']) ?></td>
                            <td class="py-3 px-6"><?= htmlspecialchars($row['applied_at']) ?></td>
                            <td class="py-3 px-6">
                            <span class="
                                <?php
                            switch($row['status']) {
                                case 'pending':
                                    echo 'text-yellow-600';
                                    break;
                                case 'reviewed':
                                    echo 'text-blue-600';
                                    break;
                                case 'accepted':
                                    echo 'text-green-600';
                                    break;
                                case 'rejected':
                                    echo 'text-red-600';
                                    break;
                            }
                            ?>
                            ">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center text-gray-600 py-6">
                    <p>You have not applied to any jobs yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </body>
    </html>

<?php
$stmt->close();
$conn->close();
?>