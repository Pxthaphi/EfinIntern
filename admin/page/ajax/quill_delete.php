<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // ตรวจสอบว่า imagePath ถูกส่งมาหรือไม่
    if (isset($_POST['imagePath']) && !empty($_POST['imagePath'])) {
        // รับ path และแก้ไขเครื่องหมาย // ใน path
        $imagePath = $_POST['imagePath'];
        $imagePath = preg_replace('/\/+/', '/', $imagePath); // แก้ไขเครื่องหมาย // ให้เหลือ / เดียว

        // ลบ ../ ออก 1 ตัว
        $imagePath = preg_replace('/\.\.\//', '', $imagePath, 1); // ลบ ../ ออก 1 ตัว

        // ตรวจสอบว่า path นั้นถูกต้อง
        if (file_exists($imagePath)) {
            // ลบไฟล์
            if (unlink($imagePath)) {
                echo json_encode(["success" => true, "message" => "Image deleted successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to delete image. Please check file permissions."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "File not found.", "path" => $imagePath]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No image path provided."]);
    }
}
