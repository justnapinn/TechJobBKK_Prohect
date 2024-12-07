<?php
session_start();
require 'databaseConnect.php';

// ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // จัดการไฟล์ที่อัปโหลด
    $target_dir = "uploads/"; // โฟลเดอร์เก็บไฟล์
    $cv_file = $target_dir . basename($_FILES["cv"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($cv_file, PATHINFO_EXTENSION));

    // ตรวจสอบชนิดไฟล์
    if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
        echo "Only PDF, DOC, and DOCX files are allowed.";
        $uploadOk = 0;
    }

    // ตรวจสอบการอัปโหลด
    if ($uploadOk && move_uploaded_file($_FILES["cv"]["tmp_name"], $cv_file)) {
        // บันทึกข้อมูลลงฐานข้อมูล
        $applied_at = date('Y-m-d H:i:s');
        $sql = "INSERT INTO applications (resume,app_id,user_id,job_id,applied_at,status) VALUES ('$cv_file','1','1','1','$applied_at','pending')";
        if ($conn->query($sql) === TRUE) {
            echo "Application submitted successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "There was an error uploading your file.";
    }
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
