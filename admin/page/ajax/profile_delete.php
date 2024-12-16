<?php

if (isset($_POST['file'])) {

    $file = $_POST['file'];
    $filePath = "../../../assets/images/profile/" . basename($file);

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบไฟล์ได้']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบไฟล์']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไฟล์ไม่ถูกต้อง']);
}
?>
