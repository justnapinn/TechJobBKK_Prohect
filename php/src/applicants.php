<?php
session_start();
require_once 'databaseConnect.php';
include('navbar.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    header("Location: login.php");
    exit();
}

$company_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $app_id = $_POST['app_id'];
    $new_status = $_POST['status'];

    $update_sql = "UPDATE applications SET status = ? WHERE app_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ss', $new_status, $app_id);

    if ($update_stmt->execute()) {
        $message = "Application status updated successfully!";
    } else {
        $message = "Error updating status: " . $conn->error;
    }
    $update_stmt->close();
}

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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Applicants</h1>

    <?php if (isset($message)): ?>
        <div class="bg-blue-100 text-blue-800 border border-blue-200 rounded-md p-4 mb-6">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full bg-white">
            <thead>
            <tr class="bg-gray-200 text-gray-600 text-sm uppercase font-semibold">
                <th class="py-3 px-6 text-left">First Name</th>
                <th class="py-3 px-6 text-left">Last Name</th>
                <th class="py-3 px-6 text-left">Age</th>
                <th class="py-3 px-6 text-left">Email</th>
                <th class="py-3 px-6 text-left">Phone</th>
                <th class="py-3 px-6 text-left">Resume</th>
                <th class="py-3 px-6 text-left">Applied At</th>
                <th class="py-3 px-6 text-left">Status</th>
                <th class="py-3 px-6 text-center">Action</th>
            </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-6"><?= htmlspecialchars($row['first_name']) ?></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($row['last_name']) ?></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($row['age']) ?></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($row['user_email']) ?></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($row['user_phone']) ?></td>
                    <td class="py-3 px-6"><a href="<?= htmlspecialchars($row['resume']) ?>"
                                             class="text-blue-600 underline" target="_blank">View Resume</a></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($row['applied_at']) ?></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($row['status']) ?></td>
                    <td class="py-3 px-6 text-center">
                        <form method="POST" action="" class="inline">
                            <input type="hidden" name="app_id" value="<?= htmlspecialchars($row['app_id']) ?>">
                            <select name="status" class="border rounded-md px-2 py-1">
                                <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending
                                </option>
                                <option value="reviewed" <?= $row['status'] === 'reviewed' ? 'selected' : '' ?>>
                                    Reviewed
                                </option>
                                <option value="rejected" <?= $row['status'] === 'rejected' ? 'selected' : '' ?>>
                                    Rejected
                                </option>
                                <option value="accepted" <?= $row['status'] === 'accepted' ? 'selected' : '' ?>>
                                    Accepted
                                </option>
                            </select>
                            <button type="submit" name="update_status"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
