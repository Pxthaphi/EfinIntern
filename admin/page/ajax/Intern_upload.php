<?php
// ตั้งค่า Content-Type ให้เป็น JSON
header('Content-Type: application/json; charset=utf-8');

include '../../../db/connection.php';

// โฟลเดอร์ที่เก็บไฟล์ที่อัปโหลด
$targetDir = "../../../assets/images/profile/";

// ตรวจสอบว่ามีโฟลเดอร์อยู่หรือไม่ ถ้าไม่มีให้สร้างใหม่
if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0777, true)) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถสร้างโฟลเดอร์ได้']);
        exit;
    }
}

// ตรวจสอบว่ามีไฟล์ที่อัปโหลดมาหรือไม่
if (isset($_FILES['User_Profile']) && $_FILES['User_Profile']['error'] == 0) {
    $fileName = basename($_FILES['User_Profile']['name']); // ชื่อไฟล์เดิม
    $fileType = $_FILES['User_Profile']['type']; // ประเภทไฟล์
    $fileSize = $_FILES['User_Profile']['size']; // ขนาดไฟล์
    $fileTmp = $_FILES['User_Profile']['tmp_name']; // ไฟล์ชั่วคราว

    // ตรวจสอบประเภทไฟล์
    if (in_array($fileType, ['image/jpeg', 'image/png'])) {
        // ดึงข้อมูล User_ID ล่าสุดจากฐานข้อมูล
        $sql = "SELECT User_ID FROM user WHERE User_ID LIKE 'Intern_Efin%' ORDER BY User_ID DESC LIMIT 1";
        $result = $conn->query($sql);

        $userID = 'Intern_Efin001'; // ค่าตั้งต้น หากยังไม่มีข้อมูลในฐานข้อมูล

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastUserID = $row['User_ID'];

            // แยกส่วนตัวเลขออกจาก User_ID
            preg_match('/(\D+)(\d+)/', $lastUserID, $matches);

            if (count($matches) === 3) {
                $prefix = $matches[1]; // ตัวอักษรนำหน้า เช่น "Off_Efin"
                $number = (int)$matches[2]; // ตัวเลข เช่น 001

                // เพิ่มตัวเลขขึ้น 1 และเติม 0 นำหน้าตามความยาวเดิม
                $newNumber = str_pad($number + 1, strlen($matches[2]), '0', STR_PAD_LEFT);
                $userID = $prefix . $newNumber;
            }
        }

        // เปลี่ยนชื่อไฟล์เป็น User_ID และแปลงเป็น .webp
        $newFileName = $userID . '.webp';
        $targetFile = $targetDir . $newFileName;
        
        // ตรวจสอบขนาดไฟล์ (ไม่เกิน 5MB)
        if ($fileSize <= 5 * 1024 * 1024) {
            // แปลงไฟล์เป็น WebP
            if ($fileType == 'image/png') {
                $image = imagecreatefrompng($fileTmp);
            } elseif ($fileType == 'image/jpeg') {
                $image = imagecreatefromjpeg($fileTmp);
            }

            // บันทึกไฟล์เป็น .webp
            if ($image && imagewebp($image, $targetFile)) {
                imagedestroy($image); // ทำลายรูปหลังจากแปลงเสร็จ
                echo json_encode([
                    'status' => 'success',
                    'file' => $newFileName,
                    'path' => $targetFile, // ส่ง path ของไฟล์กลับ
                    'message' => 'ไฟล์อัปโหลดและแปลงเป็น WebP สำเร็จ',
                    'result' => $result
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการแปลงไฟล์']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ขนาดไฟล์ใหญ่เกินไป (จำกัดที่ 5MB)']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ประเภทไฟล์ไม่ถูกต้อง (รองรับเฉพาะ PNG และ JPEG)']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ไม่มีไฟล์ที่อัปโหลด หรือเกิดข้อผิดพลาดในการอัปโหลด']);
}

$conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
