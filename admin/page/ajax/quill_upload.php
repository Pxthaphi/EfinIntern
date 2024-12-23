<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ตรวจสอบการอัพโหลดไฟล์
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        // ตรวจสอบข้อผิดพลาดจากการอัพโหลด
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(["success" => false, "message" => "File upload error."]);
            exit;
        }

        // กำหนดเส้นทางสำหรับเก็บไฟล์
        $targetDir = __DIR__ . "../../../../assets/images/project/detail/";
        // ถ้าโฟลเดอร์ไม่มีอยู่ให้สร้างใหม่
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0777, true)) {
                echo json_encode(["success" => false, "message" => "Failed to create directory."]);
                exit;
            }
        }

        // ตรวจสอบประเภทไฟล์ด้วย getimagesize()
        $fileInfo = getimagesize($file['tmp_name']);
        if ($fileInfo === false) {
            echo json_encode(["success" => false, "message" => "Uploaded file is not a valid image."]);
            exit;
        }

        // ดึงส่วนสกุลไฟล์
        $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        // ตรวจสอบว่าไฟล์เป็นรูปภาพประเภทที่อนุญาต
        $validExtensions = ["jpg", "jpeg", "png"];
        if (!in_array($fileExtension, $validExtensions)) {
            echo json_encode(["success" => false, "message" => "Invalid file type. Only JPG, JPEG, and PNG are allowed."]);
            exit;
        }

        // สร้างชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน (ลบ .jpg, .png ออก)
        $fileName = uniqid() . "_" . date("Y-m-d_H-i-s"); // ตั้งชื่อไฟล์ตาม uniqid() และเวลา

        $targetFile = $targetDir . $fileName . "." . $fileExtension; // ตั้งชื่อไฟล์เริ่มต้นตามสกุลไฟล์เดิม

        // โหลดไฟล์ภาพตามประเภท
        switch ($fileExtension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'png':
                $image = imagecreatefrompng($file['tmp_name']);
                break;
            default:
                echo json_encode(["success" => false, "message" => "Unsupported file type."]);
                exit;
        }

        // ปรับขนาดภาพ (หากต้องการ)
        list($originalWidth, $originalHeight) = getimagesize($file['tmp_name']);
        $maxWidth = 1200; // ขนาดกว้างสุดที่ต้องการ
        $maxHeight = 1200; // ขนาดสูงสุดที่ต้องการ

        // คำนวณขนาดใหม่เพื่อคงอัตราส่วน
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;

        if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
            $aspectRatio = $originalWidth / $originalHeight;
            if ($originalWidth > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = round($maxWidth / $aspectRatio);
            }
            if ($newHeight > $maxHeight) {
                $newHeight = $maxHeight;
                $newWidth = round($maxHeight * $aspectRatio);
            }
        }

        // สร้างภาพใหม่ตามขนาดที่ได้คำนวณ
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        // ปรับคุณภาพและบีบอัดไฟล์ WebP
        $quality = 80; // เริ่มต้นที่คุณภาพ 80
        $tempFile = $targetDir . $fileName . '.webp';

        // บันทึกไฟล์เป็น WebP
        do {
            imagewebp($resizedImage, $tempFile, $quality);
            $fileSize = filesize($tempFile);
            $quality -= 10; // ลดคุณภาพทีละ 10
        } while ($fileSize > 300000 && $quality > 10); // ถ้าไฟล์เกิน 300KB และ quality ยังมากกว่า 10

        // ถ้าไฟล์มีขนาดไม่เกิน 300KB แล้ว
        if ($fileSize <= 300000) {
            // ลบไฟล์เดิมที่ไม่ใช่ .webp
            $originalFile = $targetDir . $fileName . '.' . $fileExtension;
            if (file_exists($originalFile)) {
                unlink($originalFile); // ลบไฟล์เดิม
            }

            imagedestroy($image);
            imagedestroy($resizedImage);
            $relativePath = "../../../../assets/images/project/detail/" . basename($tempFile);
            echo json_encode(["success" => true, "path" => $relativePath]);
        } else {
            imagedestroy($image);
            imagedestroy($resizedImage);
            echo json_encode(["success" => false, "message" => "Image too large to compress under 300KB."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No file uploaded."]);
    }
}
?>
