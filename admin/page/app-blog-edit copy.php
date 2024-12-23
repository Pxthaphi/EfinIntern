<?php
include "../../db/connection.php";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// ดึงข้อมูลจาก URL (ถ้ามี)
$project_id = $_GET['Project_ID'] ?? 0; // รับ ID โครงการจาก URL

var_dump($project_id);


// ตรวจสอบว่าได้รับค่า project_id มาจาก URL หรือไม่
if ($project_id != 0) {
    // ตรวจสอบว่ามีการดึงข้อมูลไปแล้วหรือยังใน Session
    if ($project_id) {
        // คำสั่ง SQL สำหรับดึงข้อมูลโปรเจคที่มี Project_ID ตรงกับที่ได้รับจาก URL
        $sql = "
            SELECT 
                p.Project_ID,
                p.Project_Title,
                p.Project_Detail,
                GROUP_CONCAT(DISTINCT pu.User_ID) AS User_IDs,
                GROUP_CONCAT(DISTINCT pi.Cover_Path) AS Cover_Images,
                GROUP_CONCAT(DISTINCT pi.Slideshow_Path) AS Slideshow_Images,
                GROUP_CONCAT(DISTINCT pc.Category_ID) AS Category_IDs,
                GROUP_CONCAT(DISTINCT pc.Subcategory_ID) AS Subcategory_IDs
            FROM 
                project p
            JOIN 
                projectusers pu ON pu.Project_ID = p.Project_ID
            JOIN 
                projectimages pi ON pi.Project_ID = p.Project_ID
            JOIN 
                projectcategory pc ON pc.Project_ID = p.Project_ID
            WHERE 
                p.Project_ID = $project_id
            GROUP BY 
                p.Project_ID, 
                p.Project_Title, 
                p.Project_Detail
        ";

        // ดำเนินการคำสั่ง SQL
        $result = $conn->query($sql);

        var_dump($result);

        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if ($result->num_rows > 0) {
            // ดึงข้อมูลแต่ละแถว
            while ($project = $result->fetch_assoc()) {
                $projectData = [
                    'Project_ID' => $project['Project_ID'],
                    'Project_Title' => $project['Project_Title'],
                    'Project_Detail' => $project['Project_Detail'],
                    'User_IDs' => explode(',', $project['User_IDs']),
                    'Cover_Images' => explode(',', $project['Cover_Images']),
                    'Slideshow_Images' => explode(',', $project['Slideshow_Images']),
                    'Category_IDs' => explode(',', $project['Category_IDs']),
                    'Subcategory_IDs' => explode(',', $project['Subcategory_IDs']),
                ];
            }

            // บันทึกข้อมูลใน Session
            $_SESSION['last_project_id'] = $project_id;
        } else {
            echo "No project found with ID: $project_id";
        }
    } else {
        // ดึงข้อมูลจาก Session
        $projectData = $_SESSION['project_data'];
    }

    // ใช้งานข้อมูลใน $projectData
    $projectID = $projectData['Project_ID'];
    $projectTitle = $projectData['Project_Title'];
    $projectDetail = $projectData['Project_Detail'];
    $userIDs = $projectData['User_IDs'];
    $coverImages = $projectData['Cover_Images'];
    $slideshowImages = $projectData['Slideshow_Images'];
    $categoryIDs = $projectData['Category_IDs'];
    $subcategoryIDs = $projectData['Subcategory_IDs'];

    var_dump($projectData);
} else {
    echo "No Project ID provided.";
}

// Define session expiration time (1 minutes = 60 seconds)
$session_timeout = 5; // 5 นาที


// Retrieve data from POST or SESSION, with fallback to empty string
$project_title = $_POST['project_title'] ?? $_SESSION['project_title'] ?? '';
$project_detail = $_POST['project_detail'] ?? $_SESSION['project_detail'] ?? '';
$project_owner = $_POST['project_owner'] ?? $_SESSION['project_owner'] ?? '';
$cover_image = $_POST['cover_image'] ?? $_SESSION['cover_image'] ?? '';
$project_slideshow = $_POST['slideshow_image'] ?? $_SESSION['slideshow_image'] ?? '';
$category = $_POST['category'] ?? $_SESSION['category'] ?? '';
$category_id = $_POST['category_id'] ?? $_SESSION['category_id'] ?? '';
$subcategory = $_POST['subcategory'] ?? $_SESSION['subcategory'] ?? '';
$subcategory_id = $_POST['subcategory_id'] ?? $_SESSION['subcategory_id'] ?? '';


// Check if the session data has been set and if it has expired
if (isset($_SESSION['project_time'])) {
    $session_age = time() - $_SESSION['project_time'];

    $cover_image_new = preg_replace('/^(\.\.\/){1}/', '', $cover_image);
    $project_slideshow_new = preg_replace('/^(\.\.\/){1}/', '', $project_slideshow);

    // If session data is older than 5 minutes, unset the session variables
    if ($session_age > $session_timeout) {
        // ลบไฟล์ cover_image หากมีอยู่ในโฟลเดอร์
        if (isset($_SESSION['cover_image']) && file_exists($_SESSION['cover_image'])) {
            unlink($cover_image_new);
        }

        // ลบไฟล์ slideshow_image ทีละไฟล์ หากมีหลายไฟล์
        if (isset($_SESSION['slideshow_image']) && is_array($_SESSION['slideshow_image'])) {
            foreach ($_SESSION['slideshow_image'] as $imagePath) {
                $imagePath = preg_replace('/^(\.\.\/){1}/', '', $imagePath);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }

        // ล้างค่าเซสชัน
        unset($_SESSION['cover_image']);
        unset($_SESSION['project_title']);
        unset($_SESSION['project_detail']);
        unset($_SESSION['project_owner']);
        unset($_SESSION['slideshow_image']);
        unset($_SESSION['category']);
        unset($_SESSION['category_id']);
        unset($_SESSION['subcategory']);
        unset($_SESSION['subcategory_id']);
        unset($_SESSION['project_time']);
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Preview'])) {

        // Retrieve data from POST or SESSION, with fallback to empty string
        $project_id = $_GET['Project_ID'];
        $project_title = $_POST['project_title'] ?? $_SESSION['project_title'] ?? '';
        $project_detail = $_POST['project_detail'] ?? $_SESSION['project_detail'] ?? '';
        $project_owner = $_POST['project_owner'] ?? $_SESSION['project_owner'] ?? '';
        $cover_image = (isset($_POST['cover_image']) ? '../' . $_POST['cover_image'] : (isset($_SESSION['cover_image']) ? '../' . $_SESSION['cover_image'] : ''));
        $project_slideshow = $_POST['slideshow_image'] ?? $_SESSION['slideshow_image'] ?? '';

        // แยก path แต่ละตัวออกเป็น array
        $slideshow_paths = explode(',', $project_slideshow);

        // เพิ่ม ../ เข้าไปข้างหน้าของแต่ละ path
        $slideshow_paths = array_map(function ($path) {
            return '../' . $path;
        }, $slideshow_paths);

        // รวม array กลับเป็น string
        $project_slideshow = implode(',', $slideshow_paths);


        $category = $_POST['category'] ?? $_SESSION['category'] ?? '';
        $category_id = $_POST['category_id'] ?? $_SESSION['category_id'] ?? '';
        $subcategory = $_POST['subcategory'] ?? $_SESSION['subcategory'] ?? '';
        $subcategory_id = $_POST['subcategory_id'] ?? $_SESSION['subcategory_id'] ?? '';


        // Store the current time in session to track session expiration
        $_SESSION['project_time'] = time();

        // Check if required fields are empty
        if ($cover_image == '' && $project_slideshow == '') {
            echo "<script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'warning',
                    title: 'ระวัง!!',
                    text: 'กรุณาอัปโหลดรูป!!',
                    confirmButtonText: 'ตกลง'
                    });
            }, 100);
          </script>";
        } else if ($project_title == '' && $project_detail == '' && $project_owner == '' && $category == '' && $subcategory == '') {
            echo "<script>
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'warning',
                            title: 'ระวัง!!',
                            text: 'กรุณากรอกข้อมูลให้ครบทุกช่อง',
                            confirmButtonText: 'ตกลง'
                            });
                    }, 100);
                  </script>";
        } else {
            // Prepare the data for saving
            $projectData = [
                'project_id' => $project_id,
                'project_cover' => $cover_image,
                'project_title' => $project_title,
                'project_description' => $project_detail,
                'project_owner' => $project_owner,
                'project_slideshow' => $project_slideshow,
                'Project_category' => $category_id,
                'Project_subcategory' => $subcategory_id,
                'category' => $category,
                'subcategory' => $subcategory,
                'start_date' => '2024-12-01',
                'end_date' => '2025-12-01',
                'status' => 'กำลังดำเนินการ'
            ];

            $file_name = 'project_' . time() . '.json'; // Use .json to store data in JSON format

            $file_path = 'preview/data/' . $file_name;

            // Save data to a JSON file
            file_put_contents($file_path, json_encode($projectData));

            // Open preview in a new tab with the file name
            header('Location: preview/index.php?file=' . $file_name . '&type=edit');
        }
    }
}

// --------------------- เปรียบเทียบค่าของตัวแปร -------------------------- //


// ----------------------- Cover ---------------------------- //

// ตรวจสอบค่าของ Project_Cover จาก Session , Post , Database
$CoverimageData = '';
if (!empty($cover_image)) {
    $CoverimageData = $cover_image; // from Session & POST
} elseif (!empty($coverImages)) {
    $CoverimageData = $coverImages; // from Database
}

// แปลงอาร์เรย์เป็นสตริงที่คั่นด้วย comma (หรือใช้คั่นด้วยอื่น ๆ ที่ต้องการ)
if (is_array($CoverimageData)) {
    $CoverimageData = implode(',', $CoverimageData);
}

// echo $CoverimageData;

// --------------------- End Cover -------------------------- //

// ----------------------- Title ---------------------------- //

$projecttitleData = '';
if (!empty($project_title)) {
    $projecttitleData = $project_title; // from Session & POST
} elseif (!empty($projectTitle)) {
    $projecttitleData = $projectTitle; // from Database
}

// --------------------- End Title ---------------------------- //

// ----------------------- Detail ---------------------------- //

$projectdetailData = '';
if (!empty($project_detail)) {
    $projectdetailData = $project_detail; // from Session & POST
} elseif (!empty($slideshowImages)) {
    $projectdetailData = $projectDetail; // from Database
}

// echo $projectdetailData;

// --------------------- End Detail -------------------------- //


// ----------------------- Owner ---------------------------- //

$projectownerData = '';
if (!empty($project_owner)) {
    $projectownerData = $project_owner; // from Session & POST
} elseif (!empty($userIDs)) {
    // สมมติว่า $userIDs เป็นอาร์เรย์ที่มีค่าเช่น ['Intern_Efin001', 'Intern_Efin002']
    $user_details = []; // สร้างอาร์เรย์เพื่อเก็บข้อมูลผู้ใช้

    // วนลูปดึงข้อมูลจากตาราง user โดยใช้ $userIDs
    $Owner = $userIDs;
    foreach ($Owner as $userID) {
        // สร้างคำสั่ง SQL เพื่อดึงข้อมูลของผู้ใช้จากฐานข้อมูลตาม User_ID
        $query = "SELECT user.User_ID, user.User_FirstName, user.User_Lastname, user.User_Image, position.Position_Name FROM user JOIN position ON user.User_PositionID = position.Position_ID WHERE User_ID = ?";

        // เตรียมคำสั่ง SQL
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $userID); // ผูกตัวแปร $userID กับคำสั่ง SQL
            $stmt->execute(); // รันคำสั่ง SQL

            // เก็บผลลัพธ์
            $result = $stmt->get_result();

            // ถ้ามีข้อมูลผู้ใช้ในฐานข้อมูล
            if ($users = $result->fetch_assoc()) {
                // เก็บข้อมูลในอาร์เรย์ $user_details
                $user_details[] = [
                    'User_ID' => $users['User_ID'],
                    'User_FirstName' => $users['User_FirstName'],
                    'User_Lastname' => $users['User_Lastname'],
                    'User_Image' => $users['User_Image'],
                    'Position_Name' => $users['Position_Name']
                ];
            }

            // ปิดคำสั่ง SQL
            $stmt->close();
        }
    }

    $projectownerData = $user_details; // from Database
}

// --------------------- End Owner -------------------------- //

// ----------------- Category & Subcategory -------------------- //

$categoryData = ''; // กำหนดตัวแปรเริ่มต้น
$categoryData_ID = ''; // Initialize subcategory ID

if ($_SESSION['last_project_id'] == $project_id) {
}

if (!empty($category_id)) {
    $categoryData = $category_id; // from Session & POST

    print_r($categoryData);
} elseif (!empty($categoryIDs)) {
    // ตรวจสอบว่า $categoryIDs เป็นอาร์เรย์หรือไม่
    if (is_array($categoryIDs)) {
        var_dump("cateID : ", $categoryIDs);
        // ป้องกัน SQL Injection โดยการแปลงค่าในอาร์เรย์ให้เป็นจำนวนเต็ม
        $categoryIDsArray = array_map('intval', $categoryIDs); // แปลงแต่ละค่าของอาร์เรย์ให้เป็นตัวเลข
        $categoryIDsList = implode(',', $categoryIDsArray); // แปลงอาร์เรย์เป็นสตริงที่คั่นด้วย comma

        // สร้าง query เพื่อดึงข้อมูล category ที่มี id ตรงกับ categoryIDs และจำกัดแค่ 1 แถว
        $sql = "SELECT Category_ID FROM category WHERE Category_ID IN ($categoryIDsList) LIMIT 1";
        $result = $conn->query($sql);

        // ตรวจสอบผลลัพธ์จากการ query
        if ($result->num_rows > 0) {
            // ถ้ามีข้อมูล ดึงแค่ค่า ID เดียว
            $row = $result->fetch_assoc();
            $singleCategoryID = $row["Category_ID"];

            // แสดงผลเฉพาะ ID เดียว
            $categoryData = $singleCategoryID; // from Session & POST

        } else {
            echo "ไม่มีข้อมูล category ที่ตรงกับ ID ที่เลือก";
        }
    } else {
        echo "ข้อมูล categoryIDs ไม่ถูกต้อง";
    }
}




$subcategoryData = '';
$subcategoryData_ID = ''; // Initialize subcategory ID

if (!empty($subcategory)) {
    $subcategoryData = $subcategory; // From Session & POST
} elseif (!empty($subcategoryIDs)) {
    var_dump($subcategoryIDs);
    // Check if $subcategoryIDs is an array
    if (is_array($subcategoryIDs)) {
        // Prevent SQL Injection by converting the values in the array to integers
        $subcategoryIDsArray = array_map('intval', $subcategoryIDs); // Convert each value to an integer
        $subcategoryIDsList = implode(',', $subcategoryIDsArray); // Convert the array into a comma-separated string

        // Query to fetch subcategory details where Subcategory_ID matches
        $sql = "SELECT Subcategory_ID, Subcategory_Name FROM subcategory WHERE Subcategory_ID IN ($subcategoryIDsList)";
        $result = $conn->query($sql);

        // Check the result of the query
        if ($result->num_rows > 0) {
            $subcategories = [];
            $subcategoryIDsList = [];  // To hold the IDs

            // Loop through the query result
            while ($row = $result->fetch_assoc()) {
                // Add subcategory details to the array
                $subcategories[] = [
                    "value" => $row["Subcategory_Name"],  // Use Subcategory_Name from the database
                ];

                // Store Subcategory_IDs in an array for $subcategoryData_ID
                $subcategoryIDsList[] = $row["Subcategory_ID"];
            }

            // Encode the subcategories into JSON format
            // $subcategoryData = json_encode($subcategories);  // Do not use JSON_PRETTY_PRINT

            // Join the subcategory IDs into a comma-separated string
            $subcategoryData_ID = implode(',', $subcategoryIDsList);  // This is your comma-separated subcategory IDs
            $subcategoryData = $subcategoryData_ID; // From Session & POST

        } else {
            echo "ไม่มีข้อมูล subcategory ที่ตรงกับ ID ที่เลือก";
        }
    } else {
        echo "ข้อมูล subcategoryIDs ไม่ถูกต้อง";
    }
}


// --------------- End Category & Subcategory -------------------- //


// --------------------- Slide Show -------------------------- //

// ตรวจสอบค่าของ Slideshow จาก Session , Post , Database
$slideshowData = '';
if (!empty($project_slideshow)) {
    $slideshowData = $project_slideshow; // from Session & POST
} elseif (!empty($slideshowImages)) {
    $slideshowData = $slideshowImages; // from Database
}

// แปลงอาร์เรย์เป็นสตริงที่คั่นด้วย comma (หรือใช้คั่นด้วยอื่น ๆ ที่ต้องการ)
if (is_array($slideshowData)) {
    $slideshowData = implode(',', $slideshowData);
}

// // แสดงผลลัพธ์
// echo $slideshowData;

// ------------------- End Slide Show----------------------- //

// --------------------- สิ้นสุดเปรียบเทียบค่าของตัวแปร ------------------------ //

var_dump($_POST);
var_dump($_SESSION);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $menu = "Project";
    $submenu = "Create";
    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Blog Create | CORK - Multipurpose Bootstrap Dashboard Template </title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico" />
    <link href="../layouts/modern-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="../layouts/modern-light-menu/loader.js"></script>
    <link rel="stylesheet" href="../../assets/fonts/fonts/addfont.css">

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <script src="../src/icontify/iconify-icon.min.js"></script>



    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link rel="stylesheet" href="../src/plugins/src/filepond/filepond.min.css">
    <link rel="stylesheet" href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/src/tagify/tagify.css">

    <link rel="stylesheet" type="text/css" href="../src/assets/css/light/forms/switches.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/editors/quill/quill.snow.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/tagify/custom-tagify.css">
    <link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="../src/assets/css/dark/forms/switches.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/editors/quill/quill.snow.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/tagify/custom-tagify.css">
    <link href="../src/plugins/css/dark/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
    <link
        href="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css"
        rel="stylesheet" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link rel="stylesheet" href="../src/assets/css/light/apps/blog-create.css">
    <link rel="stylesheet" href="../src/assets/css/dark/apps/blog-create.css">
    <!--  END CUSTOM STYLE FILE  -->

    <link href="../src/plugins/src/dropzone/dropzone.min.css" rel="stylesheet" />

</head>

<body class="layout-boxed">

    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">

            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                    <!-- BREADCRUMB -->
                    <div class="page-meta">
                        <div class="d-flex justify-content-start mb-2">
                            <!-- ปุ่มย้อนกลับ (ไม่มีสี) -->
                            <a href="app-blog-list.php" class="btn btn-outline-success btn-sm d-flex align-items-center px-2 py-1">
                                <iconify-icon icon="iconamoon:arrow-left-2-light" width="20" height="20" class=""></iconify-icon>
                                <span class="">ย้อนกลับ</span>
                            </a>
                        </div>

                        <div class="d-flex justify-content-start">
                            <h2 class="title-form">แก้ไขข้อมูลโปรเจค</h2>
                        </div>

                    </div>


                    <!-- /BREADCRUMB -->

                    <form method="POST" enctype="multipart/form-data">
                        <div class="row mb-4 layout-spacing layout-top-spacing">
                            <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">

                                <div class="widget-content widget-content-area blog-create-section mb-4">
                                    <div class="row mb-4">
                                        <div class="d-flex justify-content-start gap-1">
                                            <h5 class="title-form mb-3">แบรนเนอร์หน้าปก </h5> <span style="color: #FF8484FF;">*ขนาดรูป 1920 x 1080 (px)</span>
                                        </div>

                                        <input type="hidden" class="form-control mt-2" name="Project_ID" id="Project_ID" value="<?php echo htmlspecialchars($project_id); ?>">


                                        <div class="multiple-file-upload">
                                            <input type="file"
                                                class="filepond file-upload-multiple"
                                                name="file-project"
                                                id="project-images"
                                                data-allow-reorder="true"
                                                data-max-file-size="5MB"
                                                data-max-files="1">
                                            <input type="hidden" id="cover_image" name="cover_image" class="form-control mt-2" readonly placeholder="Path ของไฟล์จะปรากฏที่นี่" value="<?= $CoverimageData ?>">

                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area blog-create-section">
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label class="title-form">ชื่อโปรเจค</label>
                                            <input
                                                type="text"
                                                class="form-control title-form"
                                                id="project_title"
                                                name="project_title"
                                                placeholder="ชื่อโปรเจค"
                                                value="<?= htmlspecialchars($projecttitleData ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                required>

                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label class="title-form">รายละเอียดโปรเจค</label>
                                            <div id="editor-container"></div>

                                            <!-- Hidden Input Field -->
                                            <input type="hidden" name="project_detail" id="hiddenDescription" required>

                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label class="title-form">เจ้าของโปรเจค</label>
                                            <input
                                                name="project_owner"
                                                placeholder="กรุณาเลือกเจ้าของโปรเจค"
                                                value='<?php echo isset($user_details) ? htmlspecialchars(json_encode($user_details), ENT_QUOTES, 'UTF-8') : '[]'; ?>'>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget-content widget-content-area blog-create-section mt-4">
                                    <div class="d-flex justify-content-start gap-1">
                                        <h5 class="title-form mb-3">รูปเพิ่มเติม </h5> <span style="color: #FF8484FF;">*จะแสดงที่ตัว Slide Show (สูงสุด 10 รูป)</span>
                                    </div>
                                    <div class="dropzone dz-clickable" id="mydropzone">
                                        <div class="dz-default dz-message" data-dz-message="">
                                            <iconify-icon icon="icons8:upload-2" width="60" height="60" class=""></iconify-icon>
                                            <h5>กดคลิกเพื่อเลือกไฟล์ หรือ ลากไฟล์รูปมาที่นี่</h5>
                                            <span style="color: #FF8484FF;">รองรับเฉพาะไฟล์ PNG , JPG เท่านั้น และไม่เกิน 5 MB ต่อไฟล์</span>
                                        </div>
                                    </div>
                                    <input type="hidden" id="slideshow_image" name="slideshow_image" class="form-control mt-2" readonly>
                                </div>
                            </div>


                            <div class="col-xxl-3 col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-xxl-0 mt-4">
                                <div class="widget-content widget-content-area blog-create-section">
                                    <div class="row">
                                        <div class="col-xxl-12 col-md-12 mb-4">
                                            <label for="category">หมวดหมู่</label>
                                            <input id="category" name="category" placeholder="เลือกหมวดหมู่...">
                                            <input type="text" class="form-control mt-2" name="category_id" id="category_id" value="<?php echo htmlspecialchars($categoryData); ?>">
                                        </div>

                                        <div class="col-xxl-12 col-md-12 mb-4">
                                            <label for="tags title-form">หมวดหมู่ย่อย</label>
                                            <input id="subcategory" class="blog-tags" name="subcategory" placeholder="เลือกหมวดหมู่ย่อย...">
                                            <input type="text" class="form-control mt-2" name="subcategory_id" id="subcategory_id" value="<?php echo htmlspecialchars($subcategoryData); ?>">
                                        </div>
                                        <div class="col-xxl-12 col-md-12 mb-4">
                                            <button type="submit" name="Preview" value="Preview" class="btn btn-secondary w-100">Preview</button>
                                        </div>
                                        <div class="col-xxl-12 col-md-12 mb-4">
                                            <button type="submit" class="btn btn-success w-100" name="Insert_Project">บันทึกข้อมูล</button>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </form>

                </div>

            </div>

            <!--  BEGIN FOOTER  -->
            <?php include "partials/footer.php" ?>
            <!--  END FOOTER  -->

        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../src/plugins/src/waves/waves.min.js"></script>
    <script src="../layouts/modern-light-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="../src/plugins/src/editors/quill/quill.js"></script>
    <script src="../src/plugins/src/filepond/filepond.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginFileValidateType.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageCrop.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageResize.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageTransform.min.js"></script>
    <script src="../src/plugins/src/filepond/filepondPluginFileValidateSize.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageValidateSize.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>


    <script src="../src/plugins/src/tagify/tagify.min.js"></script>

    <!-- <script src="../src/assets/js/apps/blog-create.js"></script> -->

    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="../src/plugins/src/dropzone/dropzone.min.js"></script>
    <script src="../src/plugins/src/sweetalerts2/sweetalert2@11.js"></script>

    <!-- FilePond For Upload Cover -->

    <script>
        FilePond.registerPlugin(FilePondPluginFileValidateType, FilePondPluginFileValidateSize, FilePondPluginImageValidateSize, FilePondPluginImagePreview, FilePondPluginFilePoster);

        const filePathFromDB = '<?= $CoverimageData ?>'; // ตัวอย่าง: ../../../assets/images/project/cover/filename.webp

        // ตัด ../ ตัวแรกออก
        const cleanedPath = filePathFromDB.replace(/^(\.\.\/){0}/, '');

        console.log(cleanedPath);

        FilePond.create(document.querySelector('#project-images'), {
            labelIdle: 'ลากไฟล์มาวางที่นี่ หรือ <span class="filepond--label-action">เลือกไฟล์</span>',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '5MB',
            maxFiles: 1,
            allowMultiple: false,
            // imageValidateSizeMinWidth: 500,
            // imageValidateSizeMinHeight: 700,
            server: {
                process: 'ajax/cover_upload.php',
            },
            name: 'file-project',
            files: cleanedPath ? [{
                source: cleanedPath,
                options: {
                    type: 'local',
                    metadata: {
                        poster: cleanedPath // ใช้ FilePondPluginFilePoster เพื่อแสดงรูป
                    }
                }
            }] : [], // หากไม่มี path จะไม่ตั้งค่า files
            onprocessfile: (error, file) => {
                if (!error) {
                    console.log('File processed successfully:', file);

                    // สร้าง path ใหม่ที่มีสกุล .webp แทน .png หรือ .jpg
                    const path = `../../../assets/images/project/cover/${file.filename.replace(/\.(jpg|jpeg|png)$/i, '.webp')}`;

                    // กำหนดค่าให้กับ input
                    document.querySelector('#cover_image').value = path;
                } else {
                    console.error('Error processing file:', error);
                }
            },

            onremovefile: (error, file) => {
                if (!error) {
                    const filePath = `../../../assets/images/project/cover/${file.filename}`;

                    fetch('ajax/cover_delete.php', {
                            method: 'POST',
                            body: new URLSearchParams({
                                'file': file.filename
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                console.log('File deleted successfully:', file);
                                document.querySelector('#cover_image').value = '';
                            } else {
                                console.error('Failed to delete file:', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error with AJAX request:', error);
                        });
                } else {
                    console.error('Error processing file:', error);
                }
            },
        });
    </script>


    <!-- Quill Setting For Project Detail -->
    <script>
        const projectDetailFromSession = <?php echo json_encode($projectdetailData ?? ''); ?>;

        const quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        header: "1"
                    }, {
                        header: "2"
                    }],
                    [{
                        size: []
                    }],
                    ["bold", "italic", "underline", "strike", "blockquote"],
                    [{
                            align: ""
                        },
                        {
                            align: "center"
                        },
                        {
                            align: "right"
                        },
                        {
                            align: "justify"
                        },
                    ],
                    [{
                            list: "ordered"
                        },
                        {
                            list: "bullet"
                        },
                        {
                            indent: "-1"
                        },
                        {
                            indent: "+1"
                        },
                    ],
                    ["image", "video", "link"],
                ],
            }
        });

        // Set initial value in the Quill editor from session data if available
        if (projectDetailFromSession) {
            quill.root.innerHTML = projectDetailFromSession;
        }


        quill.on('text-change', function() {
            const hiddenDescription = document.querySelector('#hiddenDescription');
            hiddenDescription.value = quill.root.innerHTML;
        });

        // ส่งข้อมูลในฟอร์ม
        document.querySelector('form').addEventListener('submit', function(e) {
            const hiddenDescription = document.querySelector('#hiddenDescription');
            hiddenDescription.value = quill.root.innerHTML;
            console.log("Content submitted:", hiddenDescription.value);
        });

        // Function Upload picture from Project Detail to Folder With Ajax
        quill.getModule('toolbar').addHandler('image', () => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                if (file) {
                    const formData = new FormData();
                    formData.append('file', file);

                    try {
                        const loadingText = 'Uploading image...';
                        const range = quill.getSelection();
                        quill.insertText(range.index, loadingText);

                        const response = await fetch('ajax/quill_upload.php', {
                            method: 'POST',
                            body: formData,
                        });

                        if (!response.ok) throw new Error('Upload failed');

                        const data = await response.json();
                        if (data.success) {
                            // ใช้ path ตรงกับ PHP
                            console.log(data.path);
                            const imagePath = data.path.replace('../../assets', '/assets/');

                            quill.deleteText(range.index, loadingText.length);
                            quill.insertEmbed(range.index, 'image', imagePath);
                        } else {
                            alert('อัปโหลดภาพไม่สำเร็จ: ' + data.message);
                        }
                    } catch (error) {
                        alert('มีข้อผิดพลาดในการอัปโหลด: ' + error.message);
                    }
                }
            };
        });


        // ฟังก์ชั่นอัปโหลดภาพจาก Clipboard
        quill.root.addEventListener('paste', function(e) {
            const clipboardItems = e.clipboardData.items;

            for (let i = 0; i < clipboardItems.length; i++) {
                const item = clipboardItems[i];

                // ตรวจสอบว่าเป็นรูปภาพ
                if (item.type.indexOf('image') !== -1) {
                    const file = item.getAsFile();
                    uploadImageFromClipboard(file);
                    e.preventDefault(); // ป้องกันการวางเนื้อหาใน quill
                }
            }
        });

        // ฟังก์ชั่นสำหรับอัปโหลดภาพที่คัดลอกมา
        async function uploadImageFromClipboard(file) {
            if (file) {
                const formData = new FormData();
                formData.append('file', file);

                try {
                    const loadingText = 'Uploading image...';
                    const range = quill.getSelection();
                    quill.insertText(range.index, loadingText);

                    const response = await fetch('ajax/quill_upload.php', {
                        method: 'POST',
                        body: formData,
                    });

                    if (!response.ok) throw new Error('Upload failed');

                    const data = await response.json();
                    if (data.success) {
                        // แก้ไขการสร้าง path ให้ลบเครื่องหมาย / ออกหนึ่งตัว
                        const imagePath = data.path.replace('../../assets', '/assets/');

                        quill.deleteText(range.index, loadingText.length);
                        quill.insertEmbed(range.index, 'image', imagePath);
                    } else {
                        alert('อัปเดตภาพไม่สำเร็จ: ' + data.message);
                    }
                } catch (error) {
                    alert('มีข้อผิดพลาดในการอัปโหลด: ' + error.message);
                }
            }
        }
        // ลบรูปออกจากโฟลเดอร์เมื่อมีการลบรูปใน Quill Editor
        quill.on('text-change', function(delta, oldContents, source) {
            const removedImages = [];
            delta.ops.forEach((op, index) => {
                if (op.delete && oldContents.ops[index]?.insert?.image) {
                    removedImages.push(oldContents.ops[index].insert.image);
                }
            });

            if (removedImages.length > 0) {
                removedImages.forEach(async (imagePath) => {
                    if (imagePath && typeof imagePath === 'string') {
                        // เพิ่ม ../../ ข้างหน้า path
                        let correctedPath = '../../' + imagePath.replace(/\/+/g, '/'); // เพิ่ม ../../ และลบเครื่องหมาย / เกิน

                        try {
                            const response = await fetch('ajax/quill_delete.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `imagePath=${encodeURIComponent(correctedPath)}`,
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    console.log('Image deleted successfully:', correctedPath);
                                } else {
                                    console.error('Failed to delete image:', correctedPath, data.message);
                                }
                            } else {
                                console.error('HTTP error deleting image:', response.status, correctedPath);
                            }
                        } catch (error) {
                            console.error('Error deleting image:', error, correctedPath);
                        }
                    } else {
                        console.log('Invalid image path:', imagePath);
                    }
                });
            }
        });
    </script>

    <!-- Dropzone For Upload Image to use carousel -->
    <script>
        var myDropzone = new Dropzone("div#mydropzone", {
            url: "ajax/dropzone_upload.php",
            paramName: "file[]",
            uploadMultiple: true,
            maxFilesize: 99,
            maxFiles: 10,
            autoProcessQueue: true,
            parallelUploads: 1,
            acceptedFiles: ".png,.jpg,.jpeg",
            addRemoveLinks: true,
            init: function() {
                var dzMessage = document.querySelector(".dz-message");
                var myDropzone = this;

                // เมื่อมีไฟล์ถูกเพิ่มเข้ามา
                this.on("addedfile", function() {
                    if (dzMessage && this.files.length > 0) {
                        dzMessage.style.display = "none";
                    }
                });

                // เมื่อไฟล์ถูกลบ
                this.on("removedfile", function(file) {
                    if (this.files.length === 0 && dzMessage) {
                        dzMessage.style.display = "block";
                    }

                    var filePath = file.serverFilePath; // รับชื่อไฟล์ที่แปลงแล้วจากเซิร์ฟเวอร์

                    // ส่งคำขอลบไฟล์จากเซิร์ฟเวอร์
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "ajax/dropzone_delete.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            console.log("File removed successfully: " + filePath);
                        }
                    };
                    xhr.send("filename=" + encodeURIComponent(filePath));
                });

                // เมื่ออัปโหลดไฟล์เสร็จ
                this.on("success", function(file, response) {
                    var filePath = response.replace("File uploaded and converted to WebP: ", "").trim();
                    file.serverFilePath = filePath; // เก็บชื่อไฟล์ที่แปลงเป็น .webp

                    // อัปเดตฟอร์มที่ใช้เก็บข้อมูลชื่อไฟล์
                    updateSlideshowInput(filePath);
                });

                // อัปเดตข้อมูลในฟอร์ม
                function updateSlideshowInput(filePath) {
                    var inputField = document.querySelector("input[name='slideshow_image']");
                    var currentValue = inputField.value;

                    if (currentValue) {
                        inputField.value = currentValue + ',' + filePath;
                        console.log(inputField.value);
                    } else {
                        inputField.value = filePath;
                        console.log(inputField.value);
                    }
                }

                // ตรวจสอบว่ามีข้อมูล project_slideshow หรือไม่
                if ('<?php echo $slideshowData; ?>' !== '') {
                    var existingFiles = '<?php echo $slideshowData; ?>'.split(',');

                    // นำ path ไฟล์มาทำความสะอาด และสร้าง mockFile
                    existingFiles.forEach(function(filePath) {
                        var cleanedFilePath = filePath.replace(/^(\.\.\/){0}/, ''); // ตัวเลขคือการลบจำนวน ../ ออกจาก path

                        // สร้าง mockFile
                        var mockFile = {
                            name: cleanedFilePath,
                            serverFilePath: cleanedFilePath,
                            url: cleanedFilePath
                        };

                        // แสดงใน Dropzone
                        myDropzone.displayExistingFile(mockFile, cleanedFilePath);
                        myDropzone.emit("complete", mockFile);
                    });

                    // แสดงค่าใน input[name='slideshow_image']
                    document.querySelector("input[name='slideshow_image']").value = existingFiles.join(',');
                }


                // เมื่อคิวอัปโหลดเสร็จสิ้น
                this.on("queuecomplete", function() {
                    if (dzMessage && myDropzone.files.length === 0) {
                        dzMessage.style.display = "block";
                    }
                });
            }
        });
    </script>


    <script>
        var inputElm = document.querySelector('input[name=project_owner]');
        var preSelectedOwner = inputElm.value; // Get the JSON string from the input field
        var preSelectedOwnerArray = []; // Array to hold User_IDs from session

        // Parse the JSON string to array
        try {
            preSelectedOwnerArray = JSON.parse(preSelectedOwner); // Convert the JSON string to an array
            console.log("Pre-selected owner (parsed):", preSelectedOwnerArray);
        } catch (e) {
            console.error("Error parsing preSelectedOwner JSON:", e);
        }

        function tagTemplate(tagData) {
            console.log(tagData);
            return `
        <tag title="${tagData.User_ID}"
            contenteditable="false"
            spellcheck="false"
            tabIndex="-1"
            class="tagify__tag ${tagData.class ? tagData.class : ""}">
            <x title="" class="tagify__tag__removeBtn" role="button" aria-label="remove tag"></x>
            <div>
                <div class="tagify__tag__avatar-wrap">
                    <img onerror="this.style.visibility='hidden'" src="${tagData.User_Image}">
                </div>
                <span class="tagify__tag-text">${tagData.User_FirstName} ${tagData.User_Lastname}</span>
            </div>
        </tag>
    `;
        }


        // Fetch user data from the server
        async function fetchUsers() {
            try {
                const response = await fetch('ajax/fetch_owner.php');
                if (!response.ok) throw new Error('Network error');
                const userList = await response.json();

                return userList.map(user => ({
                    value: user.User_ID,
                    User_ID: user.User_ID,
                    User_FirstName: user.User_FirstName,
                    User_Lastname: user.User_Lastname,
                    User_Image: user.User_Image,
                    Position_Name: user.Position_Name
                }));
            } catch (error) {
                console.error('Error fetching user list:', error);
                return [];
            }
        }

        // Main function to initialize and populate tags
        fetchUsers().then((userList) => {
            if (!Array.isArray(userList)) {
                console.error('User list should be an array');
                return;
            }

            var usrList = new Tagify(inputElm, {
                tagTextProp: 'User_FirstName',
                enforceWhitelist: true,
                skipInvalid: true,
                dropdown: {
                    closeOnSelect: false,
                    enabled: 0,
                    classname: 'users-list',
                    searchKeys: ['User_FirstName', 'User_Lastname', 'Position_Name']
                },
                templates: {
                    tag: tagTemplate,
                    dropdownItem: suggestionItemTemplate
                },
                whitelist: userList
            });

            // ตรวจสอบค่าของ preSelectedOwnerArray
            console.log("preSelectedOwnerArray:", preSelectedOwnerArray);

            // Process the session data and add the selected tags
            if (Array.isArray(preSelectedOwnerArray) && preSelectedOwnerArray.length > 0) {
                const preSelectedTags = preSelectedOwnerArray.map(sessionData => {
                    // ตรวจสอบว่ามีข้อมูลผู้ใช้ใน userList ที่ตรงกับ sessionData.User_ID หรือไม่
                    const user = userList.find(user => user.User_ID === sessionData.User_ID);
                    if (user) {
                        console.log("Found user in userList:", user); // แสดงข้อมูลผู้ใช้ที่ตรงกัน
                        return {
                            value: user.User_ID,
                            User_ID: user.User_ID,
                            User_FirstName: user.User_FirstName,
                            User_Lastname: user.User_Lastname,
                            User_Image: user.User_Image,
                            Position_Name: user.Position_Name
                        };
                    } else {
                        console.warn(`User not found for ID: ${sessionData.User_ID}`); // หากไม่พบผู้ใช้ใน userList
                        return null;
                    }
                }).filter(Boolean);

                console.log("User select from session: ", preSelectedTags);

                if (preSelectedTags.length > 0) {
                    usrList.addTags(preSelectedTags);
                }
            } else {
                console.error("preSelectedOwnerArray is empty or invalid:", preSelectedOwnerArray);
            }


            // Event listeners for dropdown interactions
            usrList.on('dropdown:show dropdown:updated', onDropdownShow);
            usrList.on('dropdown:select', onSelectSuggestion);

            var addAllSuggestionsElm;

            function onDropdownShow(e) {
                var dropdownContentElm = e.detail.tagify.DOM.dropdown.content;

                if (usrList.suggestedListItems.length > 1) {
                    addAllSuggestionsElm = getAddAllSuggestionsElm();

                    dropdownContentElm.insertBefore(addAllSuggestionsElm, dropdownContentElm.firstChild);
                }
            }

            function onSelectSuggestion(e) {
                if (e.detail.elm == addAllSuggestionsElm) {
                    usrList.dropdown.selectAll();
                }
            }

            function getAddAllSuggestionsElm() {
                return usrList.parseTemplate('dropdownItem', [{
                    class: "addAll",
                    User_FirstName: "เลือกทั้งหมด",
                    User_Lastname: "",
                    Position_Name: usrList.whitelist.reduce(function(remainingSuggestions, item) {
                        return usrList.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1;
                    }, 0) + " รายการ"
                }]);
            }
        });

        function suggestionItemTemplate(tagData) {
            return `
        <div ${this.getAttributes(tagData)}
            class='tagify__dropdown__item ${tagData.class ? tagData.class : ""}'
            tabindex="0"
            role="option">
            ${tagData.User_Image ? `
                <div class='tagify__dropdown__item__avatar-wrap'>
                    <img onerror="this.style.visibility='hidden'" src="${tagData.User_Image}">
                </div>` : ''}
            <strong>${tagData.User_FirstName} ${tagData.User_Lastname}</strong>
            <span>${tagData.Position_Name}</span>
        </div>
    `;
        }
    </script>




    <script>
        // ============================
        //       Fetch Categories
        // ============================

        fetch('ajax/get_category.php')
            .then(response => response.json())
            .then(categories => {
                const categoryInput = document.querySelector('input[name=category]');
                const categoryIdInput = document.querySelector('input[name=category_id]'); // Hidden input

                // สร้าง Tagify สำหรับ Category
                const tagifyCategory = new Tagify(categoryInput, {
                    whitelist: categories.map(item => ({
                        value: item.value,
                        id: item.id
                    })),
                    userInput: false,
                    maxTags: 1,
                    placeholder: "เลือกหมวดหมู่..."
                });

                // ตั้งค่าหมวดหมู่เริ่มต้น (ถ้ามี)
                const initialCategoryId = categoryIdInput.value;
                if (initialCategoryId) {
                    const initialCategory = categories.find(cat => cat.id === initialCategoryId);
                    if (initialCategory) {
                        tagifyCategory.addTags([{
                            value: initialCategory.value,
                            id: initialCategory.id
                        }]);
                        updateSubcategories(initialCategoryId);
                    }
                }

                // เมื่อเปลี่ยนค่า Category
                tagifyCategory.on('change', (e) => {
                    const selectedCategory = JSON.parse(e.detail.value)[0];
                    const selectedCategoryId = selectedCategory ? selectedCategory.id : null;

                    categoryIdInput.value = selectedCategoryId || ''; // อัปเดตค่าใน hidden input
                    updateSubcategories(selectedCategoryId); // อัปเดต Subcategories

                    // แสดงค่า category ที่เลือก
                    console.log('Selected Category:', selectedCategory);
                });

                // ลบค่า Category
                tagifyCategory.on('remove', () => {
                    categoryIdInput.value = ''; // เคลียร์ค่าใน hidden input
                    clearSubcategories(); // เคลียร์ Subcategories
                    console.log('Category removed');
                });
            })
            .catch(error => console.error('Error fetching categories:', error));


        // ============================
        //      Fetch Subcategories
        // ============================

        let tagifySubcategory;
        const subcategoryInput = document.querySelector('.blog-tags');
        const subcategoryIdInput = document.querySelector('input[name=subcategory_id]'); // Hidden input

        function updateSubcategories(categoryId) {
            if (!categoryId) {
                clearSubcategories();
                return;
            }

            fetch(`ajax/get_subcategory.php?category_id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched Data:', data);

                    // Process the fetched data to create the subcategories array
                    const subcategories = data.map(item => ({
                        value: item.value,
                        id: String(item.id) // Ensure the ID is a string for consistency
                    }));

                    console.log('Processed Subcategories:', subcategories); // Log the processed subcategories array

                    // สร้างหรืออัปเดต Tagify สำหรับ Subcategory
                    if (!tagifySubcategory) {
                        tagifySubcategory = new Tagify(subcategoryInput, {
                            whitelist: subcategories.map(item => ({
                                value: item.value,
                                id: item.id
                            })),
                            userInput: false,
                            placeholder: "เลือกหมวดหมู่ย่อย...",
                            enforceWhitelist: true,
                        });

                        // เพิ่มแท็กอัตโนมัติจากค่าใน hidden input
                        const initialSubCategoryIds = subcategoryIdInput.value.split(',').map(id => id.trim());

                        // เพิ่มแท็กหากมีค่าใน hidden input
                        if (initialSubCategoryIds.length > 0) {
                            initialSubCategoryIds.forEach(id => {
                                const initialSubCategory = subcategories.find(cat => cat.id === id);

                                if (initialSubCategory) {
                                    // เพิ่มแท็กลงใน Tagify
                                    tagifySubcategory.addTags([{
                                        value: initialSubCategory.value,
                                        id: initialSubCategory.id
                                    }]);
                                } else {
                                    // แจ้งเตือนหากไม่พบหมวดหมู่ย่อยในรายการที่ดึงมา
                                    console.warn(`Subcategory with ID ${id} not found in fetched data.`);
                                }
                            });
                        } else {
                            console.log('No initial subcategory IDs to add.');
                        }

                        // ฟังก์ชันจัดการการเปลี่ยนแปลงแท็ก
                        tagifySubcategory.on('change', (e) => {
                            const selectedTags = JSON.parse(e.detail.value) || [];
                            console.log("Selected Tags:", selectedTags);

                            const selectedIds = selectedTags.map(tag => tag.id); // ดึง id จากแต่ละ tag
                            console.log("Selected IDs:", selectedIds);

                            if (selectedIds.length > 0) {
                                subcategoryIdInput.value = selectedIds.join(',');
                                console.log('Selected Subcategory IDs:', selectedIds);
                            } else {
                                console.log('No subcategory IDs found');
                                subcategoryIdInput.value = ''; // เคลียร์ค่าใน subcategoryIdInput ถ้าไม่มี tag
                            }
                        });

                        // ฟังก์ชันจัดการการลบแท็ก
                        tagifySubcategory.on('remove', (e) => {
                            const removedTag = e.detail.data;
                            console.log('Removed Tag:', removedTag);

                            const removedTagId = removedTag.id;
                            const currentIds = subcategoryIdInput.value.split(',').filter(id => id !== removedTagId.toString());
                            subcategoryIdInput.value = currentIds.join(',');
                            console.log('Updated Subcategory IDs after removal:', currentIds);
                        });

                    } else {
                        // อัปเดตค่า whitelist และแสดง dropdown ใหม่
                        tagifySubcategory.settings.whitelist = subcategories.map(item => ({
                            value: item.value,
                            id: item.id
                        }));
                        tagifySubcategory.removeAllTags();
                        tagifySubcategory.dropdown.hide();
                        tagifySubcategory.dropdown.show();
                    }

                })
                .catch(error => console.error('Error fetching subcategories:', error));

        }


        function clearSubcategories() {
            if (tagifySubcategory) {
                tagifySubcategory.settings.whitelist = [];
                tagifySubcategory.removeAllTags();
            }
            subcategoryIdInput.value = ''; // เคลียร์ค่าใน hidden input
        }
    </script>




    <script>
        // Preserve form data if the page is reloaded
        window.onload = function() {
            if (window.location.search.indexOf("file") === -1) {
                // Check if no preview file parameter exists, then restore form data
                let projectData = <?php echo json_encode($projectData ?? []); ?>;
                if (projectData) {
                    for (let key in projectData) {
                        let element = document.querySelector(`[name='${key}']`);
                        if (element) {
                            element.value = projectData[key];
                        }
                    }
                }
            }
        };
    </script>

    <script>
        window.addEventListener('unload', () => {
            navigator.sendBeacon('ajax/delete_session.php');
        });
    </script>


    <!-- END PAGE LEVEL SCRIPTS -->
</body>

</html>

<?php

include "../../db/connection.php";
session_start();

// ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Update_Project'])) {

        // var_dump($_POST);

        // รับค่าที่ส่งมาจากฟอร์ม
        $project_id = $_POST['Project_ID'];
        $project_title = $_POST['project_title'];
        $project_detail = $_POST['project_detail'];
        $project_owner = $_POST['project_owner'];
        $cover_image_path = $_POST['cover_image'];
        $CreateBy_UserID = $_SESSION['User_ID'];
        $category_id = $_POST['category_id'];
        $subcategory = $_POST['subcategory_id']; // อาจจะเป็น array หรือไม่เป็น array

        // Remove the first occurrence of '../'
        $cover_image_path = preg_replace('/^(\.\.\/)/', '', $cover_image_path, 1);

        $slideshow_image_path = $_POST['slideshow_image'];

        // Split the paths by commas
        $image_paths = explode(',', $slideshow_image_path);

        // Loop through each path and remove the first '../' occurrence
        foreach ($image_paths as &$path) {
            $path = preg_replace('/^(\.\.\/)/', '', $path);
        }

        // Join the paths back into a single string
        $slideshow_image_path = implode(',', $image_paths);

        // แปลง JSON เป็น array
        $owner_data = json_decode($project_owner, true);

        if (is_array($owner_data)) {
            $user_ids = []; // สร้าง array เก็บ User_ID ทั้งหมด

            // วนลูปดึงค่า User_ID
            foreach ($owner_data as $owner) {
                if (isset($owner['User_ID'])) {
                    $user_ids[] = $owner['User_ID']; // เก็บ User_ID ไว้ใน array
                }
            }
        } else {
            echo "ไม่สามารถแปลง JSON หรือไม่พบข้อมูล";
            exit; // หยุดการทำงานหากไม่สามารถแปลง JSON
        }

        // ตรวจสอบค่าที่ได้รับจากฟอร์ม
        if (empty($project_title) || empty($project_detail) || empty($project_owner) || empty($category_id) || empty($subcategory)) {
            echo "<script>
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อมูลไม่ครบถ้วน',
                        text: 'กรุณากรอกข้อมูลทั้งหมด',
                        confirmButtonText: 'ตกลง'
                    });
                }, 100);
              </script>";
            exit; // หยุดการทำงานหากข้อมูลไม่ครบ
        }

        if (empty($cover_image_path) || empty($slideshow_image_path)) {
            echo "<script>
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'อัปโหลดรูปไม่ครบ!!',
                        text: 'กรุณาอัปโหลดรูปให้ครบทั้งหมด',
                        confirmButtonText: 'ตกลง'
                    });
                }, 100);
              </script>";
            exit; // หยุดการทำงานหากข้อมูลไม่ครบ
        }

        // เตรียมคำสั่ง SQL สำหรับการ UPDATE ข้อมูลโปรเจ็ค
        $sql = "UPDATE project SET Project_Title = ?, Project_Detail = ?, Project_CreateBy = ? WHERE Project_ID = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssi", $project_title, $project_detail, $CreateBy_UserID, $project_id);

            if ($stmt->execute()) {

                // อัปเดตข้อมูลรูปภาพ
                $sql_image = "UPDATE projectimages SET Cover_Path = ?, Slideshow_Path = ? WHERE Project_ID = ?";
                $stmt_image = $conn->prepare($sql_image);
                $stmt_image->bind_param("ssi", $cover_image_path, $slideshow_image_path, $project_id);
                $stmt_image->execute();
                $stmt_image->close();

                // ลบข้อมูล User_ID เดิมใน projectusers แล้ว INSERT ใหม่
                $sql_delete_user = "DELETE FROM projectusers WHERE Project_ID = ?";
                $stmt_delete_user = $conn->prepare($sql_delete_user);
                $stmt_delete_user->bind_param("i", $project_id);
                $stmt_delete_user->execute();
                $stmt_delete_user->close();

                // วนลูป INSERT ข้อมูล User_ID ใหม่ลงใน projectusers
                $sql_user = "INSERT INTO projectusers (Project_ID, User_ID) VALUES (?, ?)";
                $stmt_user = $conn->prepare($sql_user);

                foreach ($user_ids as $user_id) {
                    $stmt_user->bind_param("is", $project_id, $user_id);
                    $stmt_user->execute();
                }
                $stmt_user->close();

                // อัปเดตข้อมูล Category_ID และ Subcategory_ID ลงใน projectcategory
                $sql_delete_category = "DELETE FROM projectcategory WHERE Project_ID = ?";
                $stmt_delete_category = $conn->prepare($sql_delete_category);
                $stmt_delete_category->bind_param("i", $project_id);
                $stmt_delete_category->execute();
                $stmt_delete_category->close();

                $sql_category = "INSERT INTO projectcategory (Project_ID, Category_ID, Subcategory_ID) VALUES (?, ?, ?)";
                $stmt_category = $conn->prepare($sql_category);

                // แยกค่า $subcategory_id จาก string ที่คั่นด้วยเครื่องหมาย ","
                $subcategory_ids = explode(',', $subcategory); // จะได้ array เช่น [1, 2]

                // วนลูปแทรกข้อมูล Subcategory_ID ที่แยกออกมา
                foreach ($subcategory_ids as $subcategory_id) {
                    $stmt_category->bind_param("iii", $project_id, $category_id, $subcategory_id);
                    $stmt_category->execute();
                }

                $stmt_category->close();

                // เคลียร์ session หลังจากบันทึกข้อมูลเสร็จ
                unset($_SESSION['cover_image']);
                unset($_SESSION['project_title']);
                unset($_SESSION['project_detail']);
                unset($_SESSION['project_owner']);
                unset($_SESSION['slideshow_image']);
                unset($_SESSION['category']);
                unset($_SESSION['category_id']);
                unset($_SESSION['subcategory']);
                unset($_SESSION['subcategory_id']);

                // แจ้งเตือนด้วย SweetAlert สำหรับการอัปเดตสำเร็จ
                echo "<script>
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'อัปเดตข้อมูลสำเร็จ',
                            text: 'ข้อมูลของคุณถูกอัปเดตแล้ว',
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            window.location.href = 'app-blog-list.php'; // เปลี่ยนเส้นทางถ้าจำเป็น
                        });
                    }, 100);
                  </script>";
            } else {
                // แจ้งเตือนข้อผิดพลาด
                echo "<script>
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถอัปเดตข้อมูลโปรเจ็คได้: " . addslashes($stmt->error) . "',
                            confirmButtonText: 'ตกลง'
                        });
                    }, 100);
                  </script>";
            }

            // ปิด statement
            $stmt->close();
        } else {
            // แจ้งเตือนเมื่อเกิดข้อผิดพลาดในการเตรียม statement
            echo "<script>
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถเตรียมคำสั่ง SQL ได้: " . addslashes($conn->error) . "',
                        confirmButtonText: 'ตกลง'
                    });
                }, 100);
              </script>";
        }

        // ปิดการเชื่อมต่อฐานข้อมูล
        $conn->close();
    }
}
?>