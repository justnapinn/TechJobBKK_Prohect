<?php
ob_start();
session_start();
require_once 'databaseConnect.php';
include('navbar.php');
include('checkLogin.php');

// Get search parameters from the form
$keyword = $_GET['keyword'] ?? '';
$job_type = $_GET['job-type'] ?? [];
$job_location = $_GET['location'] ?? '';

$job_types = is_array($job_type) ? array_filter($job_type, fn($type) => in_array($type, ['hybrid', 'work-from-home', 'onsite'])) : [];

// Build the SQL query based on the search parameters
$sql = "SELECT j.*, u.first_name, u.logo , u.province
        FROM jobs j
        INNER JOIN users u ON j.user_id = u.user_id";

$where_clause = [];

// Add keyword search
if (!empty($keyword)) {
    $where_clause[] = "(j.title LIKE '%$keyword%' OR u.username LIKE '%$keyword%')";
}

// Add job type filter
if (!empty($job_types)) {
    $job_type_conditions = array_map(fn($type) => "j.job_type = '" . addslashes($type) . "'", $job_types);
    $where_clause[] = '(' . implode(' OR ', $job_type_conditions) . ')';
}


// Add location filter
if (!empty($job_location) && $job_location !== 'all') { // 'all' is for no filtering
    $where_clause[] = "u.province = '$job_location'";
}

// Combine the where clauses using AND
if (!empty($where_clause)) {
    $sql .= " WHERE " . implode(' AND ', $where_clause);
}

// Execute the query
$result = $conn->query($sql);

ob_end_flush();
?>

    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <title>Job Search Results - TechJobBkk</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>

    <body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">ผลการค้นหางาน</h1>

        <?php if ($result->num_rows > 0) { ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center mb-4">
                            <?php if (!empty($row['logo']) && file_exists($row['logo'])): ?>
                                <img src="<?php echo htmlspecialchars($row['logo']); ?>" alt="Company Logo"
                                     class="w-16 h-16 mr-4 rounded-full">
                            <?php else: ?>
                                <div class="w-16 h-16 mr-4 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500 text-sm">No Profile</span>
                                </div>
                            <?php endif; ?>
                            <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($row['title']); ?></h2>
                        </div>
                        <p class="text-gray-600 mb-2">
                            <?php echo htmlspecialchars($row['first_name']); ?>
                        </p>
                        <div class="mb-4">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                <?php echo htmlspecialchars($row['job_type']); ?>
                            </span>
                        </div>
                        <p class="text-gray-700 mb-4">
                            <?php echo substr(htmlspecialchars($row['description']), 0, 150) . '...'; ?>
                        </p>
                        <a href="jobPost.php?job_id=<?php echo $row['job_id']; ?>"
                           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            ดูรายละเอียด
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <?php echo "<p>ไม่พบผลการค้นหา</p>"; ?>
            </div>
        <?php } ?>
    </div>
    </body>

    </html>

<?php
$conn->close();
?>