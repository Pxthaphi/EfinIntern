<?php
include "../../../db/connection.php";

// Function to add ../ to the beginning of each path
function addRelativePathPrefix($path) {
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
function deleteFile($file_path, &$deleted_files) {
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
function waitUntilAllFilesDeleted($file_paths, &$deleted_files) {
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
    $delete_id = intval($_GET['delete']);
    $deleted_files = []; // Initialize an array to store deleted file paths

    // Fetch Cover_Path and Slideshow_Path from projectimages table
    $sql = "SELECT Cover_Path, Slideshow_Path FROM projectimages WHERE Project_ID = $delete_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $cover_path = $row['Cover_Path'];
        $slideshow_paths = $row['Slideshow_Path'];

        // Add ../ to the paths before deleting
        $cover_path = addRelativePathPrefix($cover_path);
        $slideshow_paths = addRelativePathPrefix($slideshow_paths);

        // Collect all file paths to delete
        $file_paths_to_delete = [$cover_path];
        if (!empty($slideshow_paths)) {
            $paths = explode(',', $slideshow_paths);
            foreach ($paths as $path) {
                $file_paths_to_delete[] = trim($path); // Remove any whitespace
            }
        }

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
            // Delete data from projectimages table
            $sql = "DELETE FROM projectimages WHERE Project_ID = $delete_id";
            mysqli_query($conn, $sql);

            // Delete data from project table
            $sql = "DELETE FROM project WHERE Project_ID = $delete_id";
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
        $id_list = implode(',', array_map('intval', $ids)); // Convert array to string and escape
        $deleted_files = []; // Initialize an array to store deleted file paths

        // Fetch Cover_Path and Slideshow_Path from projectimages table
        $sql = "SELECT Project_ID, Cover_Path, Slideshow_Path FROM projectimages WHERE Project_ID IN ($id_list)";
        $result = mysqli_query($conn, $sql);

        $all_files_deleted = true;
        $file_paths_to_delete = [];

        // Collect all file paths to delete
        while ($row = mysqli_fetch_assoc($result)) {
            $cover_path = $row['Cover_Path'];
            $slideshow_paths = $row['Slideshow_Path'];

            // Add ../ to the paths before deleting
            $cover_path = addRelativePathPrefix($cover_path);
            $slideshow_paths = addRelativePathPrefix($slideshow_paths);

            // Add to file paths to delete
            $file_paths_to_delete[] = $cover_path;
            if (!empty($slideshow_paths)) {
                $paths = explode(',', $slideshow_paths);
                foreach ($paths as $path) {
                    $file_paths_to_delete[] = trim($path); // Remove any whitespace
                }
            }
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
            // Delete data from projectimages table
            $sql = "DELETE FROM projectimages WHERE Project_ID IN ($id_list)";
            mysqli_query($conn, $sql);

            // Delete data from project table
            $sql = "DELETE FROM project WHERE Project_ID IN ($id_list)";
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
?>
