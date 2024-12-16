<?php
include '../../../db/connection.php';

header('Content-Type: application/json');

$sql = "SELECT 
            u.User_ID, 
            u.User_Prefix, 
            u.User_FirstName, 
            u.User_Lastname, 
            u.User_Image, 
            u.User_PositionID,
            p.Position_Name
        FROM user u
        JOIN intern i ON i.User_ID = u.User_ID 
        JOIN position p ON p.Position_ID = u.User_PositionID";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "SQL Error: " . $conn->error]);
    $conn->close();
    exit;
}

if ($result->num_rows > 0) {
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
} else {
    echo json_encode([]);
}

$conn->close();
?>
