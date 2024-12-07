<?php
session_start();
include('navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Job BKK Homepage</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans text-gray-700 bg-gray-100">
<nav class="bg-blue-500 text-white py-4">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="#" class="text-2xl font-bold">TechJobBkk</a>
        <ul class="flex gap-4">
            <li><a href="#">หางาน</a></li>
            <li><a href="register.html">สมัครสมาชิก</a></li>
            <li><a href="login.html">เข้าสู่ระบบ</a></li>
        </ul>
    </div>
</nav>

<section class="search-section bg-blue-500 text-black py-8">
    <div class="container mx-auto max-w-lg bg-white p-6 rounded-lg shadow-md">
        <form class="space-y-4" action="result.php" method="GET">
            <div>
                <label for="location" class="block font-bold mb-2">สถานที่ปฏิบัติงาน</label>
                <select id="location" class="w-full border rounded-md p-2">
                    <option value="all">ทั้งหมด</option>
                    <option value="bangkok">กรุงเทพฯ</option>
                </select>
            </div>

            <div>
                <label for="keyword" class="block font-bold mb-2">คำที่ต้องการค้นหา</label>
                <input type="text" id="keyword" placeholder="ระบุตำแหน่งงาน หรือชื่อบริษัท"
                       class="w-full border rounded-md p-2">
            </div>

            <div>
                <label class="block font-bold mb-2">รูปแบบการทำงาน</label>
                <div class="flex gap-4">
                    <label><input type="radio" name="work-type" value="hybrid"> Hybrid Work</label>
                    <label><input type="radio" name="work-type" value="remote"> Work from Home</label>
                    <label><input type="radio" name="work-type" value="onsite"> Onsite 100%</label>
                </div>
            </div>

            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">ค้นหา</button>
        </form>
    </div>
</section>

<section class="section-PopularCompany bg-gray-100 py-8 text-center">
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-6">Welcome to TechJobBkk <br> Popular Tech Companies</h1>
        <div class="flex flex-wrap justify-center gap-4">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Singha_Beer_Logo.png" alt="SINGHA"
                 class="w-32 h-auto">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcREdMVffUvtW3_TC0oGmYPst-LCk6_GT6gQUA&s"
                 alt="SCBX" class="w-32 h-auto">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQDKGBd50-GpKtgrpl1ZI8p0mkry3MkFU1GFQ&s"
                 alt="AGODA" class="w-32 h-auto">
        </div>
    </div>
</section>
</body>
</html>