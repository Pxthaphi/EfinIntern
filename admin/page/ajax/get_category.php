<?php
include '../../../db/connection.php';

$sql = "SELECT * FROM category";
$result = $conn->query($sql);

$categories = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = [
            'value' => $row['Category_Name'],
            'id' => $row['Category_ID']
        ];
    }
} else {
    echo "0 results";
}

echo json_encode($categories);

$conn->close();
?>
