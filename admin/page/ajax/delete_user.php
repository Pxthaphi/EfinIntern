<?php
include "../../../db/connection.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);


// Function to add ../ to the beginning of each path
function addRelativePathPrefix($path)
{
    if (!empty($path)) {
        // Check if the path contains multiple entries (separated by commas)
        $paths = explode(',', $path);
        foreach ($paths as &$single_path) {
            // Trim whitespace from each path and add ../ prefix
            $single_path = '../' . trim($single_path);
        }
        // Return the paths joined with commas (if multiple)
        return implode(',', $paths);
    }
    return ''; // Return empty string if path is empty
}

// Function to delete files and check success
function deleteFile($file_path, &$deleted_files)
{
    if (!empty($file_path) && file_exists($file_path)) {
        // Log the file path before deletion
        error_log("Attempting to delete file: " . $file_path);
        // Attempt to delete the file and check if it's successful
        if (unlink($file_path)) {
            // Store the file path in the deleted_files array
            $deleted_files[] = $file_path;
            return true;  // File deleted successfully
        } else {
            // Log failure to delete the file
            error_log("Failed to delete file: " . $file_path);
            return false;  // Failed to delete the file
        }
    }
    return true; // Return true if file doesn't exist, no deletion needed
}

// Function to wait until all files are deleted
function waitUntilAllFilesDeleted($file_paths, &$deleted_files)
{
    $all_files_deleted = false;
    while (!$all_files_deleted) {
        $all_files_deleted = true;
        foreach ($file_paths as $file_path) {
            if (file_exists($file_path)) {
                // If file still exists, mark as not deleted yet
                $all_files_deleted = false;
                break;
            }
        }
        // Wait a bit before checking again to avoid infinite loops
        if (!$all_files_deleted) {
            sleep(1); // Sleep for 1 second before rechecking
        }
    }
    return true;
}

// Delete a single project record
if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']); // Safely escape the User_ID string
    $deleted_files = []; // Initialize an array to store deleted file paths

    // Fetch User_Image from user table
    $sql = "SELECT User_Image FROM user WHERE User_ID = '$delete_id'"; // Handle User_ID as string in SQL query
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $Image_Path = $row['User_Image'];

        // Add ../ to the paths before deleting
        $Image_Path = addRelativePathPrefix($Image_Path);

        // Collect all file paths to delete
        $file_paths_to_delete = [$Image_Path];

        // Delete files and wait until all files are deleted
        $all_files_deleted = true;
        foreach ($file_paths_to_delete as $file_path) {
            if (!deleteFile($file_path, $deleted_files)) {
                $all_files_deleted = false;
            }
        }

        // Wait until all files are deleted
        if (!$all_files_deleted) {
            waitUntilAllFilesDeleted($file_paths_to_delete, $deleted_files);
        }

        // If all files were deleted successfully
        if ($all_files_deleted) {
            // Delete data from user table
            $sql = "DELETE FROM user WHERE User_ID = '$delete_id'"; // Handle User_ID as string in SQL query
            $stmt = mysqli_query($conn, $sql);

            // Send response with deleted files info
            if ($stmt) {
                echo json_encode([
                    'success' => true,
                    'message' => 'ลบข้อมูลและไฟล์สำเร็จ',
                    'deleted_files' => $deleted_files
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบไฟล์บางรายการได้']);
        }
    }
}

// Delete multiple project records
if (isset($_POST['deleteMultiple'])) {
    $ids = $_POST['ids']; // Receive array from client
    if (!empty($ids)) {
        // Correct usage of mysqli_real_escape_string
        $id_list = "'" . implode("','", array_map(function ($id) use ($conn) {
            return mysqli_real_escape_string($conn, trim($id)); // Pass $conn as the first argument
        }, $ids)) . "'";
        
        $deleted_files = []; // Initialize an array to store deleted file paths

        // Fetch User_Image from user table
        $sql = "SELECT User_Image FROM user WHERE User_ID IN ($id_list)";
        $result = mysqli_query($conn, $sql);

        $all_files_deleted = true;
        $file_paths_to_delete = [];

        // Collect all file paths to delete
        while ($row = mysqli_fetch_assoc($result)) {
            $Image_Path = $row['User_Image'];

            // Add ../ to the paths before deleting
            $Image_Path = addRelativePathPrefix($Image_Path);

            // Add to file paths to delete
            $file_paths_to_delete[] = $Image_Path;
        }

        // Delete files and wait until all files are deleted
        foreach ($file_paths_to_delete as $file_path) {
            if (!deleteFile($file_path, $deleted_files)) {
                $all_files_deleted = false;
            }
        }

        // Wait until all files are deleted
        if (!$all_files_deleted) {
            waitUntilAllFilesDeleted($file_paths_to_delete, $deleted_files);
        }

        // If all files were deleted successfully
        if ($all_files_deleted) {
            // Delete data from user table
            $sql = "DELETE FROM user WHERE User_ID IN ($id_list)";
            $stmt = mysqli_query($conn, $sql);

            // Send response with deleted files info
            if ($stmt) {
                echo json_encode([
                    'success' => true,
                    'message' => 'ลบข้อมูลและไฟล์สำเร็จ',
                    'deleted_files' => $deleted_files,
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบไฟล์บางรายการได้']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่มีข้อมูลที่ถูกเลือก']);
    }
}
