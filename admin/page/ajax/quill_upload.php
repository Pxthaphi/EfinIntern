<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Admin File From Folder uploads/quill
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        // ตรวจสอบข้อผิดพลาดจากการอัพโหลด
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(["success" => false, "message" => "File upload error."]);
            exit;
        }

        $targetDir = __DIR__ . "../../../../assets/images/project/detail/";
       // ถ้าโฟลเดอร์ไม่มีอยู่ให้สร้างใหม่
       if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0777, true)) {
                echo json_encode(["success" => false, "message" => "Failed to create directory."]);
                exit;
            }
        }

        // สร้างชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน
        $fileName = uniqid() . "-" . basename($file["name"]);
        $targetFile = $targetDir . $fileName;

        // ตรวจสอบประเภทไฟล์
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $validExtensions = ["jpg", "jpeg", "png"];
        if (!in_array($fileType, $validExtensions)) {
            echo json_encode(["success" => false, "message" => "Invalid file type."]);
            exit;
        }

        // โหลดไฟล์ภาพตามประเภท
        switch ($fileType) {
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
