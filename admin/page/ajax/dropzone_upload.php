<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function compress_image($source, $destination, $quality) {
    $image = imagecreatefromstring(file_get_contents($source));
    if (!$image) {
        return false;
    }

    // แปลงเป็นไฟล์ webp และบีบอัดตามคุณภาพ
    $result = imagewebp($image, $destination, $quality);
    imagedestroy($image);
    return $result;
}

if (!empty($_FILES['file']['name'][0])) {
    $upload_dir = '../../../assets/images/project/slideshow/';

    // ตรวจสอบว่ามีโฟลเดอร์อยู่แล้วหรือไม่ ถ้ายังไม่มีให้สร้าง
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $uploaded_files = [];  // เพื่อเก็บชื่อไฟล์ที่อัปโหลด

    foreach ($_FILES['file']['name'][0] as $key => $name) {
        if (!is_string($name)) {
            echo "Invalid file name format.";
            continue;
        }

        $tmp_name = $_FILES['file']['tmp_name'][0][$key];
        $file_extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $new_name = pathinfo($name, PATHINFO_FILENAME) . '.webp';  // เปลี่ยนชื่อไฟล์เป็น .webp

        // ตรวจสอบว่าไฟล์เป็นรูปภาพที่รองรับ
        if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
            $upload_file = $upload_dir . $new_name;

            // เริ่มต้นคุณภาพสูงสุดที่ 80 (สามารถปรับได้)
            $quality = 80;

            // ตรวจสอบขนาดไฟล์หลังจากบีบอัด
            $success = false;
            do {
                // เรียกใช้ฟังก์ชันบีบอัดภาพ
                $success = compress_image($tmp_name, $upload_file, $quality);
                if (!$success) {
                    echo "Failed to compress image: $name\n";
                    break;
                }

                clearstatcache();  // ล้าง cache ขนาดไฟล์
                $file_size = filesize($upload_file); // ตรวจสอบขนาดไฟล์
                $quality -= 5; // ลดคุณภาพเพื่อให้ขนาดไฟล์ลดลง

            } while ($file_size > 300 * 1024 && $quality > 10); // หยุดเมื่อขนาดไฟล์ไม่เกิน 300KB หรือคุณภาพต่ำกว่า 10

            // ถ้าขนาดไฟล์ไม่เกิน 300KB จะได้ไฟล์แล้ว
            if ($file_size <= 300 * 1024) {
                echo "File uploaded and converted to WebP: $upload_file\n";
                $uploaded_files = $new_name;  // เก็บชื่อไฟล์
            } else {
                echo "Failed to compress image below 300KB for: $name\n";
            }
        } else {
            echo "Unsupported file format: $name\n";
        }
    }

    // ส่งชื่อไฟล์กลับไปยัง JavaScript เพื่อให้สามารถใช้ในการลบไฟล์ได้
    if (!empty($uploaded_files)) {
        // echo implode(", ", $uploaded_files);
    } else {
        echo "No files uploaded.";
    }
} else {
    echo "No files uploaded.";
}
?>
