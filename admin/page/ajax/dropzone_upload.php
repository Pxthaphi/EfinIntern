<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
            $image = @imagecreatefromstring(file_get_contents($tmp_name));
            if ($image) {
                // ตรวจสอบขนาดของไฟล์ภาพ
                $file_size = filesize($tmp_name); // ขนาดไฟล์ในไบต์
                $max_size = 300 * 1024; // 300 KB in bytes

                // ถ้าขนาดไฟล์ใหญ่กว่า 300KB ให้ทำการลดคุณภาพ
                if ($file_size > $max_size) {
                    $quality = 75;  // Set desired quality (0-100 scale)
                } else {
                    $quality = 90;  // Higher quality if file size is within limit
                }

                // เปลี่ยนเป็นรูป WebP โดยใช้คุณภาพที่ตั้งค่า
                $upload_file = $upload_dir . $new_name;
                $result = imagewebp($image, $upload_file, $quality); // Save as WebP with quality setting

                imagedestroy($image);

                if ($result) {
                    echo "File uploaded and converted to WebP: $upload_file\n";
                    $uploaded_files[] = $new_name;  // Store the file name
                } else {
                    echo "Failed to convert image to WebP: $name\n";
                }
            } else {
                echo "Invalid image file: $name\n";
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
