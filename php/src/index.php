<?php
session_start();
require_once 'databaseConnect.php';
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
    <section class="search-section bg-blue-500 text-black py-8">
        <div class="container mx-auto max-w-lg bg-white p-6 rounded-lg shadow-md">
            <form class="space-y-4" action="result.php" method="GET">
                <div>
                    <label for="location" class="block font-bold mb-2">สถานที่ปฏิบัติงาน</label>
                    <select id="location" name="location" class="w-full border rounded-md p-2">
                        <option value="all">ทั้งหมด</option>
                        <option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>
                        <option value="สมุทรปราการ">สมุทรปราการ</option>
                        <option value="นนทบุรี">นนทบุรี</option>
                        <option value="ปทุมธานี">ปทุมธานี</option>
                        <option value="พระนครศรีอยุธยา">พระนครศรีอยุธยา</option>
                        <option value="อ่างทอง">อ่างทอง</option>
                        <option value="ลพบุรี">ลพบุรี</option>
                        <option value="สิงห์บุรี">สิงห์บุรี</option>
                        <option value="ฉะเชิงเทรา">ฉะเชิงเทรา</option>
                        <option value="ชลบุรี">ชลบุรี</option>
                        <option value="ระยอง">ระยอง</option>
                        <option value="ตราด">ตราด</option>
                        <option value="จันทบุรี">จันทบุรี</option>
                        <option value="สระแก้ว">สระแก้ว</option>
                        <option value="นครราชสีมา">นครราชสีมา</option>
                        <option value="บุรีรัมย์">บุรีรัมย์</option>
                        <option value="สุรินทร์">สุรินทร์</option>
                        <option value="ศรีสะเกษ">ศรีสะเกษ</option>
                        <option value="อุบลราชธานี">อุบลราชธานี</option>
                        <option value="อำนาจเจริญ">อำนาจเจริญ</option>
                        <option value="ขอนแก่น">ขอนแก่น</option>
                        <option value="อุดรธานี">อุดรธานี</option>
                        <option value="หนองบัวลำภู">หนองบัวลำภู</option>
                        <option value="เลย">เลย</option>
                        <option value="ชัยภูมิ">ชัยภูมิ</option>
                        <option value="นครพนม">นครพนม</option>
                        <option value="มุกดาหาร">มุกดาหาร</option>
                        <option value="สกลนคร">สกลนคร</option>
                        <option value="ร้อยเอ็ด">ร้อยเอ็ด</option>
                        <option value="มหาสารคาม">มหาสารคาม</option>
                        <option value="กาฬสินธุ์">กาฬสินธุ์</option>
                        <option value="หนองคาย">หนองคาย</option>
                        <option value="เชียงใหม่">เชียงใหม่</option>
                        <option value="เชียงราย">เชียงราย</option>
                        <option value="ลำปาง">ลำปาง</option>
                        <option value="ลำพูน">ลำพูน</option>
                        <option value="พะเยา">พะเยา</option>
                        <option value="น่าน">น่าน</option>
                        <option value="แพร่">แพร่</option>
                        <option value="อุตรดิตถ์">อุตรดิตถ์</option>
                        <option value="ตาก">ตาก</option>
                        <option value="สุโขทัย">สุโขทัย</option>
                        <option value="พิษณุโลก">พิษณุโลก</option>
                        <option value="เพชรบูรณ์">เพชรบูรณ์</option>
                        <option value="กำแพงเพชร">กำแพงเพชร</option>
                        <option value="นครสวรรค์">นครสวรรค์</option>
                        <option value="อุทัยธานี">อุทัยธานี</option>
                        <option value="ชัยนาท">ชัยนาท</option>
                        <option value="สุพรรณบุรี">สุพรรณบุรี</option>
                        <option value="ราชบุรี">ราชบุรี</option>
                        <option value="กาญจนบุรี">กาญจนบุรี</option>
                        <option value="เพชรบุรี">เพชรบุรี</option>
                        <option value="ประจวบคีรีขันธ์">ประจวบคีรีขันธ์</option>
                        <option value="นครปฐม">นครปฐม</option>
                        <option value="สมุทรสาคร">สมุทรสาคร</option>
                        <option value="สมุทรสงคราม">สมุทรสงคราม</option>
                        <option value="ชุมพร">ชุมพร</option>
                        <option value="ระนอง">ระนอง</option>
                        <option value="สุราษฎร์ธานี">สุราษฎร์ธานี</option>
                        <option value="พังงา">พังงา</option>
                        <option value="ภูเก็ต">ภูเก็ต</option>
                        <option value="กระบี่">กระบี่</option>
                        <option value="ตรัง">ตรัง</option>
                        <option value="พัทลุง">พัทลุง</option>
                        <option value="สงขลา">สงขลา</option>
                        <option value="สตูล">สตูล</option>
                        <option value="ปัตตานี">ปัตตานี</option>
                        <option value="นราธิวาส">นราธิวาส</option>
                        <option value="ยะลา">ยะลา</option>
                    </select>
                </div>



                <div>
                    <label for="keyword" class="block font-bold mb-2">คำที่ต้องการค้นหา</label>
                    <input type="text" id="keyword" placeholder="ระบุตำแหน่งงาน หรือชื่อบริษัท" name="keyword"
                        class="w-full border rounded-md p-2">
                </div>

                <div>
                    <label class="block font-bold mb-2">รูปแบบการทำงาน</label>
                    <div class="flex gap-4">
                        <input type="checkbox" id="job-type-hybrid" name="job-type[]" value="hybrid">
                        <label for="job-type-hybrid"> hybrid </label><br>

                        <input type="checkbox" id="job-type-work-from-home" name="job-type[]" value="work-from-home">
                        <label for="job-type-work-from-home"> Work-Form-Home </label><br>

                        <input type="checkbox" id="job-type-onsite" name="job-type[]" value="onsite">
                        <label for="job-type-onsite"> Onsite 100% </label><br>
                        
                    </div>
                </div>

                <button type="submit"
                    class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">ค้นหา</button>
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