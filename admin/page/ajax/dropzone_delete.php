<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['filename'])) {
    // Directly get the file name from POST
    $file_path = $_POST['filename'];

    // Check if the file exists
    if (file_exists($file_path)) {
        // Attempt to delete the file
        if (unlink($file_path)) {
            echo "File deleted successfully: $file_path";
        } else {
            echo "Failed to delete the file: $file_path";
        }
    } else {
        echo "File does not exist: $file_path";
    }
} else {
    echo "No filename specified.";
}
?>
