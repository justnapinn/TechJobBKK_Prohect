<?php

session_start();
require 'databaseConnect.php';


// รับค่าจากฟอร์ม
$location = $_GET['location'] ?? 'all';
$job_type = $_GET['job_type'] ?? 'all';
$keyword = $_GET['keyword'] ?? '';

// สร้าง SQL Query
$sql = "SELECT * FROM companies WHERE 1=1";
$sqljoin_user = "SELECT * FROM    users RIGHT JOIN     jobs ON  users.user_id = jobs.user_id";

if ($location !== 'all') {
    $sql .= " AND location = '$location'";
}

if ($job_type !== 'all') {
    $sql .= " AND job_type = '$job_type'";
}

if (!empty($keyword)) {
    $sql .= " AND (company_name LIKE '%$keyword%' OR job_title LIKE '%$keyword%')";
}

// ดึงข้อมูลจากฐานข้อมูล
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลการค้นหา - TechJobBKK</title>
    <link rel="stylesheet" href="styleHomepage.css">
</head>
<body>
    <section class="results-section">
        <div class="container">
            <h1>ผลการค้นหา</h1>
            <?php if ($result->num_rows > 0): ?>
                <ul class="company-list">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <li class="company-item">
                            <h2><?php echo $row['company_name']; ?></h2>
                            <p>ตำแหน่งงาน: <?php echo $row['job_title']; ?></p>
                            <p>สถานที่: <?php echo $row['location']; ?></p>
                            <p>ประเภทงาน: <?php echo $row['job_type']; ?></p>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>ไม่พบข้อมูลที่ตรงกับการค้นหา</p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>

<?php
$conn->close();
?>
