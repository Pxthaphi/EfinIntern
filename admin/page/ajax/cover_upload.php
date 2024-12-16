<?php
// ตั้งค่า Content-Type ให้เป็น JSON
header('Content-Type: application/json; charset=utf-8');

// โฟลเดอร์ที่เก็บไฟล์ที่อัปโหลด
$targetDir = "../../../assets/images/project/cover/";

// ตรวจสอบว่ามีโฟลเดอร์อยู่หรือไม่ ถ้าไม่มีให้สร้างใหม่
if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0777, true)) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถสร้างโฟลเดอร์ได้']);
        exit;
    }
}

// ตรวจสอบว่ามีไฟล์ที่อัปโหลดมาหรือไม่
if (isset($_FILES['file-project']) && $_FILES['file-project']['error'] == 0) {
    $fileName = basename($_FILES['file-project']['name']); // ชื่อไฟล์
    $targetFile = $targetDir . $fileName; // ที่อยู่เต็มของไฟล์ที่จะบันทึก

    // ประเภทไฟล์ที่อนุญาต
    $allowedTypes = ['image/jpeg', 'image/png'];
    $fileType = $_FILES['file-project']['type'];
    $fileSize = $_FILES['file-project']['size'];

    // ตรวจสอบประเภทไฟล์
    if (in_array($fileType, $allowedTypes)) {
        // ตรวจสอบขนาดไฟล์ (ไม่เกิน 5MB)
        if ($fileSize <= 5 * 1024 * 1024) { // 5MB
            // Load image based on file type
            switch ($fileType) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($_FILES['file-project']['tmp_name']);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($_FILES['file-project']['tmp_name']);
                    break;
                default:
                    echo json_encode(['status' => 'error', 'message' => 'ประเภทไฟล์ไม่ถูกต้อง']);
                    exit;
            }

            // Get original image size
            list($width, $height) = getimagesize($_FILES['file-project']['tmp_name']);

            // Convert to WebP file
            $webpFileName = pathinfo($fileName, PATHINFO_FILENAME) . ".webp";
            $webpFilePath = $targetDir . $webpFileName;

            // Convert image to WebP format with quality
            $quality = 80; // Adjust quality to reduce file size
            imagewebp($image, $webpFilePath, $quality);

            // Check if the WebP file exceeds 300KB and adjust quality
            while (filesize($webpFilePath) > 300 * 1024 && $quality > 10) {
                $quality -= 5;
                imagewebp($image, $webpFilePath, $quality);
            }

            // If still too large, resize image and save again
            if (filesize($webpFilePath) > 300 * 1024) {
                $newWidth = $width * 0.8;
                $newHeight = $height * 0.8;
                $resizedImage = imagescale($image, $newWidth, $newHeight);
                imagewebp($resizedImage, $webpFilePath, $quality);
            }

            // Clean up image resources
            imagedestroy($image);

            // สร้าง relative path สำหรับใช้ในหน้าเว็บ
            $relativePath = "../../../assets/images/project/cover/" . $webpFileName;
            echo json_encode([
                'status' => 'success',
                'file' => $webpFileName,
                'path' => $relativePath, // ส่ง path ของไฟล์กลับ
                'message' => 'ไฟล์อัปโหลดสำเร็จ'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'ขนาดไฟล์ใหญ่เกินไป (จำกัดที่ 5MB)'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'ประเภทไฟล์ไม่ถูกต้อง (รองรับเฉพาะ PNG และ JPEG)'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'ไม่มีไฟล์ที่อัปโหลด หรือเกิดข้อผิดพลาดในการอัปโหลด'
    ]);
}
?>
