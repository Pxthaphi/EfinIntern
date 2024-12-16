<?php
include "../../../db/connection.php";

if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];

    $query = "SELECT Project_Detail FROM project WHERE Project_ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $project_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $project = mysqli_fetch_assoc($result);

    if ($project) {
        echo $project['Project_Detail']; // Return HTML content as plain text
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
