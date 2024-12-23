<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // ตรวจสอบว่า filename ถูกส่งมาหรือไม่
    if (isset($_POST['filename']) && !empty($_POST['filename'])) {
        $filePath = $_POST['filename'];
        $filePath = preg_replace('/\/+/', '/', $filePath);  // แก้ไขเครื่องหมาย // ให้เหลือ / เดียว
        $filePath = preg_replace('/\.\.\//', '', $filePath, 1);  // ลบ ../ ออก 1 ตัว

        // ตรวจสอบว่า path นั้นถูกต้อง
        if (file_exists($filePath)) {
            // ลบไฟล์
            if (unlink($filePath)) {
                echo json_encode(["success" => true, "message" => "File deleted successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to delete file. Please check file permissions."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "File not found.", "path" => $filePath]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No filename provided."]);
    }
}
?>
