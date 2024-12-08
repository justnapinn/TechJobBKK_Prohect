<?php
session_start();
require 'databaseConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];  // รับ user_id ที่เก็บใน session
    $job_id = isset($_POST['job_id']) ? $_POST['job_id'] : '';

    // ตรวจสอบว่า user_id และ job_id ไม่ว่าง
    if (empty($user_id) || empty($job_id)) {
        die("User ID and Job ID are required.");
    }

    // ตรวจสอบว่า user_id มีอยู่ในฐานข้อมูลหรือไม่ (ใช้ VARCHAR แทน INT)
    $sql_check_user = "SELECT user_id FROM users WHERE user_id = ?";
    $stmt_check = $conn->prepare($sql_check_user);
    $stmt_check->bind_param("s", $user_id);  // ใช้ 's' สำหรับ VARCHAR
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    // หากไม่พบ user_id ในฐานข้อมูล
    if ($result_check->num_rows == 0) {
        die("User ID does not exist in the database.");
    }

    $sql_check_application = "SELECT * FROM applications WHERE user_id = ? AND job_id = ?";
    $stmt_check_app = $conn->prepare($sql_check_application);
    $stmt_check_app->bind_param("ss", $user_id, $job_id);  // ใช้ 's' สำหรับ VARCHAR
    $stmt_check_app->execute();
    $result_check_app = $stmt_check_app->get_result();

    // หากพบการสมัครงานนี้แล้ว
    if ($result_check_app->num_rows > 0) {
        echo "<script>alert('You have already applied for this job.'); window.history.back();</script>";
        exit();  // หยุดการทำงานเพื่อป้องกันการสมัครซ้ำ
    }
    
    // เตรียมการอัพโหลดไฟล์
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // ตรวจสอบประเภทไฟล์ที่อัพโหลด
    $fileType = strtolower(pathinfo($_FILES["cv"]["name"], PATHINFO_EXTENSION));
    $filename = uniqid() . '.' . $fileType;
    $cv_file = $target_dir . $filename;

    if (!in_array($fileType, ['pdf', 'doc', 'docx'])) {
        die("Only PDF, DOC, and DOCX files are allowed.");
    }

    // อัพโหลดไฟล์
    if (move_uploaded_file($_FILES["cv"]["tmp_name"], $cv_file)) {
        // ตั้งค่าเวลาในการสมัคร
        $applied_at = date('Y-m-d H:i:s');
        $app_id = uniqid();
        $status = 'pending';

        // ทำการแทรกข้อมูลลงในตาราง applications
        $sql = "INSERT INTO applications (resume, app_id, user_id, job_id, applied_at, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $cv_file, $app_id, $user_id, $job_id, $applied_at, $status);  // ใช้ 's' สำหรับ VARCHAR

        // ตรวจสอบการแทรกข้อมูล
        if ($stmt->execute()) {
            echo "Your application has been submitted successfully.";
        } else {
            echo "An error occurred: " . $stmt->error;
        }
    } else {
        echo "There was an error uploading your file.";
    }
}
$conn->close();
?>
