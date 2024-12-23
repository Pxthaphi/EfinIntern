<?php
include '../../../db/connection.php';

// รับค่า Category_ID จาก URL (ถ้ามี)
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

if ($category_id > 0) {
    // กรอง Subcategory ตาม Category_ID
    $stmt = $conn->prepare("SELECT * FROM subcategory WHERE Category_ID = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $categories = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = [
                'value' => $row['Subcategory_Name'],
                'id' => $row['Subcategory_ID'],
                'category_id' => $row['Category_ID']
            ];
        }
    }

    echo json_encode($categories);
} else {
    // ส่งกลับ array ว่าง ถ้าไม่มี Category_ID
    echo json_encode([]);
}

$conn->close();
?>
