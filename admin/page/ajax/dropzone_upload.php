<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// var_dump($_FILES);


if (!empty($_FILES['file']['name'][0][0])) {
    $upload_dir = '../../../assets/images/project/slideshow/';

    // ตรวจสอบโฟลเดอร์
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $uploaded_files = [];

    foreach ($_FILES['file']['name'][0] as $key => $name) {
        if (!is_string($name)) {
            echo "Invalid file name format.";
            continue;
        }

        $tmp_name = $_FILES['file']['tmp_name'][0][$key];
        $file_extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $new_name = 'Slide_' . uniqid() . '.webp';

        if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
            $image = @imagecreatefromstring(file_get_contents($tmp_name));
            if ($image) {
                $file_size = filesize($tmp_name);
                $max_size = 300 * 1024;

                $quality = ($file_size > $max_size) ? 75 : 90;

                $upload_file = $upload_dir . $new_name;
                $result = imagewebp($image, $upload_file, $quality);
                imagedestroy($image);

                if ($result) {
                    echo "File uploaded and converted to WebP: $upload_file\n";
                    $uploaded_files[] = $new_name;
                } else {
                    echo "Failed to convert image to WebP: $name\n";
                }
            } else {
                echo "Invalid image file: $name\n";
            }
        } else {
            echo "Unsupported file format: $name\n";
        }
    }

    if (!empty($uploaded_files)) {
        // echo implode(", ", $uploaded_files);
    } else {
        echo "No files uploaded.";
    }
} else {
    echo "No files uploaded.";
}
