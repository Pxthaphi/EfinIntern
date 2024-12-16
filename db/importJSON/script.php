<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../connection.php";

// Read and decode JSON data
$json_data = file_get_contents("universities-pretty.json");
$universities = json_decode($json_data, true);

if ($universities === null) {
    die("Error decoding JSON: " . json_last_error_msg());
}

echo "<script>console.table(" . json_encode($universities) . ");</script>";

// Prepare the SQL statement for insertion
$stmt_insert = $conn->prepare("INSERT INTO university (University_Name, University_thCode, University_enCode) VALUES (?, ?, ?)");
if (!$stmt_insert) {
    die("Error preparing statement: " . $conn->error);
}

// Prepare the SQL statement for duplicate check
$stmt_check = $conn->prepare("SELECT COUNT(*) FROM university WHERE University_Name = ?");
if (!$stmt_check) {
    die("Error preparing statement for duplicate check: " . $conn->error);
}

// Bind parameters and insert each university if not a duplicate
foreach ($universities as $university) {
    $university_name = $university['university'];
    $th_code = $university['thCode'];
    $en_code = $university['enCode'];

    // Check for duplicates by executing the count query
    $stmt_check->bind_param("s", $university_name);
    $stmt_check->execute();
    $stmt_check->store_result(); // Store the result to reset the connection
    $stmt_check->bind_result($count);
    $stmt_check->fetch();

    if ($count > 0) {
        echo "Duplicate found, skipping: $university_name<br>";
    } else {
        // Insert if not a duplicate
        $stmt_insert->bind_param("sss", $university_name, $th_code, $en_code);
        if (!$stmt_insert->execute()) {
            echo "Error inserting record: " . $stmt_insert->error . "<br>";
        } else {
            echo "Inserted: $university_name<br>";
        }
    }

    // Reset the result of the check statement for the next iteration
    $stmt_check->free_result();
}

// Close statements and connection
$stmt_check->close();
$stmt_insert->close();
$conn->close();
?>
