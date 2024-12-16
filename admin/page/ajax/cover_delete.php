<?php

if (isset($_POST['file'])) {

    $file = $_POST['file'];
    // รับชื่อไฟล์จากโพสต์และเปลี่ยนสกุลเป็น .webp
    $filePath = "../../../assets/images/project/cover/" . basename($file);
    $newFilePath = preg_replace('/\.(jpg|png)$/i', '.webp', $filePath); // เปลี่ยนเป็น .webp

    if (file_exists($newFilePath)) {
        if (unlink($newFilePath)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบไฟล์ได้']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบไฟล์', 'path' => $newFilePath]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไฟล์ไม่ถูกต้อง']);
}
