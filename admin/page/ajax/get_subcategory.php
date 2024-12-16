<?php
include '../../../db/connection.php';

$sql = "SELECT * FROM subcategory";
$result = $conn->query($sql);

$categories = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = [
            'value' => $row['Subcategory_Name'],
            'id' => $row['Subcategory_ID'],
            'category_id' => $row['Category_ID']
        ];
    }
} else {
    echo "0 results";
}

echo json_encode($categories);

$conn->close();
?>
