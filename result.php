<?php
session_start();
require_once 'databaseConnect.php'; // Assume this file handles database connection

// Sanitize and process search inputs
$location = $_GET['location'] ?? 'กรุงเทพฯ';
$keyword = $_GET['keyword'] ?? '';
$job_type = $_GET['job_type'] ?? '';

// Construct dynamic SQL query
$query = "SELECT jobs.*, users.first_name, users.last_name, users.logo 
          FROM jobs 
          JOIN users ON jobs.user_id = users.user_id 
          WHERE 1=1";

$params = [];

if (!empty($location)) {
    $query .= " AND users.province = ?";
    $params[] = $location;
}

if (!empty($keyword)) {
    $query .= " AND (jobs.title LIKE ? OR jobs.description LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}

if (!empty($job_type)) {
    $query .= " AND jobs.job_type = ?";
    $params[] = $job_type;
}

// Prepare and execute the statement
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
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

        <?php if ($result->num_rows > 0): ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($job = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center mb-4">
                            <?php if (!empty($job['logo'])): ?>
                                <img src="<?php echo htmlspecialchars($job['logo']); ?>" alt="Company Logo"
                                     class="w-16 h-16 mr-4 rounded-full">
                            <?php endif; ?>
                            <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($job['title']); ?></h2>
                        </div>
                        <p class="text-gray-600 mb-2">
                            <?php echo htmlspecialchars($job['first_name'] . ' ' . $job['last_name']); ?>
                        </p>
                        <div class="mb-4">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                <?php echo htmlspecialchars($job['job_type']); ?>
                            </span>
                        </div>
                        <p class="text-gray-700 mb-4">
                            <?php echo substr(htmlspecialchars($job['description']), 0, 150) . '...'; ?>
                        </p>
                        <a href="jobPost.php?job_id=<?php echo $job['job_id']; ?>"
                           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            ดูรายละเอียด
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p>ไม่พบผลการค้นหา</p>
            </div>
        <?php endif; ?>
    </div>
    </body>
    </html>

<?php
$stmt->close();
$conn->close();
?>