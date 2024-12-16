<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $file_to_delete = $_SESSION['file_to_delete'] ?? '';
    if ($file_to_delete == 'project_Final.json') {
        echo "ไม่ต้องลบ";
    } else if ($file_to_delete && file_exists('data/' . $file_to_delete)) {
        unlink('data/' . $file_to_delete);
        echo "ไฟล์ถูกลบเรียบร้อยแล้ว.";
    } else {
        echo "ไม่พบไฟล์ที่จะลบ.";
    }
}
?>
