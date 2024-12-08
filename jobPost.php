<?php
session_start();
require 'databaseConnect.php';
include('navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Thai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            if (status === 'success') {
                document.getElementById('successModal').classList.remove('hidden');
            } else if (status === 'error') {
                document.getElementById('errorModal').classList.remove('hidden');
            } else if (status === 'alreadyAppliedModal') {
                document.getElementById('alreadyAppliedModal').classList.remove('hidden');
                setTimeout(function() {
                    document.getElementById('alreadyAppliedModal').classList.add('hidden');
                }, 3000); // ปิดป๊อปอัพหลังจาก 3 วินาที
            }
        }
    </script>
</head>
<body class="bg-gray-100">

<?php generateNavbar(); ?>

<!-- ป๊อปอัพการสมัครงานซ้ำ -->
<div id="alreadyAppliedModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-xl w-96">
        <h2 class="text-2xl text-yellow-600 font-semibold mb-4">Already Applied</h2>
        <p class="text-gray-700">You have already applied for this job.</p>
        <button onclick="document.getElementById('alreadyAppliedModal').classList.add('hidden')" class="bg-blue-600 text-white py-2 px-6 mt-4 rounded-lg hover:bg-blue-700 transition duration-300">
            Close
        </button>
    </div>
</div>

<!-- ป๊อปอัพเมื่อการสมัครสำเร็จ -->
<div id="successModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-xl w-96">
        <h2 class="text-2xl text-green-600 font-semibold mb-4">Success</h2>
        <p>Your application has been submitted successfully.</p>
        <button onclick="document.getElementById('successModal').classList.add('hidden')" class="bg-blue-600 text-white py-2 px-6 mt-4 rounded-lg hover:bg-blue-700 transition duration-300">
            Close
        </button>
    </div>
</div>

<!-- ป๊อปอัพเมื่อการสมัครไม่สำเร็จ -->
<div id="errorModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-xl w-96">
        <h2 class="text-2xl text-red-600 font-semibold mb-4">Error</h2>
        <p>There was an error submitting your application. Please try again.</p>
        <button onclick="document.getElementById('errorModal').classList.add('hidden')" class="bg-red-600 text-white py-2 px-6 mt-4 rounded-lg hover:bg-red-700 transition duration-300">
            Close
        </button>
    </div>
</div>

<?php
$job_id = isset($_GET['job_id']) ? $_GET['job_id'] : '';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in first.");
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลบริษัทจากฐานข้อมูล
$sql_company = "SELECT j.job_id, u.first_name
                FROM jobs j
                INNER JOIN users u ON j.user_id = u.user_id
                WHERE j.job_id = ?";
$stmt = $conn->prepare($sql_company);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result_company = $stmt->get_result();

if ($result_company->num_rows > 0) {
    $row_company = $result_company->fetch_assoc();
    echo "<h1 class='text-3xl font-bold text-center text-gray-800 mb-8'>" . htmlspecialchars($row_company["first_name"]) . "</h1>";
} else {
    echo "<h1 class='text-3xl font-bold text-center text-gray-800 mb-8'>ไม่พบข้อมูลบริษัท</h1>";
}

// ค้นหาข้อมูลงานจาก job_id
if (!empty($job_id)) {
    $sql = "SELECT * FROM jobs WHERE job_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<div class='max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg'>";

        // แสดงข้อมูลของงาน
        echo "<div class='bg-gray-50 p-4 rounded-lg mb-4'>";
        echo "<h3 class='text-xl font-semibold text-blue-600 mb-2'>Job Title</h3>";
        echo "<p class='text-gray-700'>" . nl2br(htmlspecialchars($row["title"])) . "</p>";
        echo "</div>";

        echo "<div class='bg-gray-50 p-4 rounded-lg mb-4'>";
        echo "<h3 class='text-xl font-semibold text-blue-600 mb-2'>Description</h3>";
        echo "<p class='text-gray-700'>" . nl2br(htmlspecialchars($row["description"])) . "</p>";
        echo "</div>";

        echo "<div class='bg-gray-50 p-4 rounded-lg mb-4'>";
        echo "<h3 class='text-xl font-semibold text-blue-600 mb-2'>Welfare</h3>";
        echo "<p class='text-gray-700'>" . nl2br(htmlspecialchars($row["welfare"])) . "</p>";
        echo "</div>";

        echo "<div class='bg-gray-50 p-4 rounded-lg mb-4'>";
        echo "<h3 class='text-xl font-semibold text-blue-600 mb-2'>Contact</h3>";
        echo "<p class='text-gray-700'>" . nl2br(htmlspecialchars($row["contact"])) . "</p>";
        echo "</div>";

        echo "</div>";
    } else {
        echo "<p class='text-center text-xl text-gray-700'>ไม่พบข้อมูลงานนี้</p>";
    }

    $stmt->close();
} else {
    echo "<p class='text-center text-xl text-gray-700'>ไม่พบ job_id ที่ระบุ</p>";
}
?>

<!-- ปุ่ม Apply -->
<div class="flex justify-center mt-6">
    <a href="apply.php?job_id=<?php echo urlencode($job_id); ?>&user_id=<?php echo urlencode($_SESSION['user_id']); ?>" class="inline-block bg-blue-600 text-white py-3 px-8 rounded-lg text-center hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
        Apply
    </a>
</div>

<?php $conn->close(); ?>

</body>
</html>
