<?php
include "../../db/connection.php";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Define session expiration time (1 minutes = 60 seconds)
$session_timeout = 60;

// Retrieve data from POST or SESSION, with fallback to empty string
$project_title = $_POST['project_title'] ?? $_SESSION['project_title'] ?? '';
$project_detail = $_POST['project_detail'] ?? $_SESSION['project_detail'] ?? '';
$project_owner = $_POST['project_owner'] ?? $_SESSION['project_owner'] ?? '';
$cover_image = $_POST['cover_image'] ?? $_SESSION['cover_image'] ?? '';
$project_slideshow = $_POST['slideshow_image'] ?? $_SESSION['slideshow_image'] ?? '';
$category = $_POST['category'] ?? ''; // Fallback to empty string if not set
$subcategory = $_POST['subcategory'] ?? ''; // Fallback to empty string if not set

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
        unset($_SESSION['project_time']);
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Preview'])) {

        // Retrieve data from POST or SESSION, with fallback to empty string
        $project_title = $_POST['project_title'] ?? $_SESSION['project_title'] ?? '';
        $project_detail = $_POST['project_detail'] ?? $_SESSION['project_detail'] ?? '';
        $project_owner = $_POST['project_owner'] ?? $_SESSION['project_owner'] ?? '';
        $cover_image = $_POST['cover_image'] ?? $_SESSION['cover_image'] ?? '';
        $project_slideshow = $_POST['slideshow_image'] ?? $_SESSION['slideshow_image'] ?? '';
        $category = $_POST['category_id'];
        $subcategory = $_POST['subcategory_id'];


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
                'project_cover' => $cover_image,
                'project_title' => $project_title,
                'project_description' => $project_detail,
                'project_owner' => $project_owner,
                'project_slideshow' => $project_slideshow,
                'Project_category' => $category,
                'Project_subcategory' => $subcategory,
                'start_date' => '2024-12-01',
                'end_date' => '2025-12-01',
                'status' => 'กำลังดำเนินการ'
            ];

            $file_name = 'project_' . time() . '.json'; // Use .json to store data in JSON format

            $file_path = 'preview/data/' . $file_name;

            // Save data to a JSON file
            file_put_contents($file_path, json_encode($projectData));

            // Open preview in a new tab with the file name
            // echo "<script>window.open('preview/index.php?file=$file_name&type=add', '_blank');</script>";
            header('Location: preview/index.php?file=' . $file_name . '&type=add');
        }
    }
}
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
    <?php include 'partials/navbar.php' ?>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <?php include 'partials/sidebar.php' ?>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">

            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                    <!-- BREADCRUMB -->
                    <div class="page-meta">
                        <div class="flex justify-content-start">
                            <!-- <button class="btn btn-light">
                                <p class="title-form">ย้อนกลับ</p>
                            </button> -->
                            <h2 class="title-form">เพิ่มข้อมูลโปรเจคใหม่</h2>
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

                                        <div class="multiple-file-upload">
                                            <input type="file"
                                                class="filepond file-upload-multiple"
                                                name="file-project"
                                                id="project-images"
                                                data-allow-reorder="true"
                                                data-max-file-size="5MB"
                                                data-max-files="1">
                                            <input type="hidden" id="cover_image" name="cover_image" class="form-control mt-2" readonly placeholder="Path ของไฟล์จะปรากฏที่นี่" value="<?= $cover_image ?>">

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
                                                value="<?= htmlspecialchars($project_title ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                                            <input name="project_owner" placeholder="กรุณาเลือกเจ้าของโปรเจค" value='<?php echo isset($_SESSION['project_owner']) ? json_encode($_SESSION['project_owner']) : ''; ?>'>>
                                            <?php
                                            var_dump($_SESSION['project_owner']);
                                            ?>
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
                                            <input id="category" name="category" placeholder="เลือกหมวดหมู่..." value="<?php $category ?>">
                                            <input type="text" class="form-control mt-2" name="category_id" id="category_id" value="">
                                        </div>

                                        <div class="col-xxl-12 col-md-12 mb-4">
                                            <label for="tags title-form">หมวดหมู่ย่อย</label>
                                            <input id="subcategory" class="blog-tags" value="" name="subcategory" placeholder="เลือกหมวดหมู่ย่อย..." value="<?php $subcategory ?>">
                                            <input type="text" class="form-control mt-2" name="subcategory_id" id="subcategory_id">
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

        const filePathFromDB = '<?= $cover_image ?>'; // ตัวอย่าง: ../../../assets/images/project/cover/filename.webp

        // ตัด ../ ตัวแรกออก
        const cleanedPath = filePathFromDB.replace(/^(\.\.\/){1}/, '');

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
        const projectDetailFromSession = <?php echo json_encode($_SESSION['project_detail'] ?? ''); ?>;

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
                if ('<?php echo $project_slideshow; ?>' !== '') {
                    var existingFiles = '<?php echo $project_slideshow; ?>'.split(',');

                    // นำ path ไฟล์มาทำความสะอาด และสร้าง mockFile
                    existingFiles.forEach(function(filePath) {
                        var cleanedFilePath = filePath.replace(/^(\.\.\/){1}/, '');

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


    <!-- Fetch Owner Project -->
    <script>
        var inputElm = document.querySelector('input[name=project_owner]');
        var preSelectedOwner = inputElm.value; // Get the value (if any)
        console.log(preSelectedOwner); // ตรวจสอบค่าของ preSelectedOwner

        // แปลง preSelectedOwner จาก JSON string ให้เป็น object
        var preSelectedOwnerObj = preSelectedOwner ? JSON.parse(preSelectedOwner) : null;
        console.log(preSelectedOwnerObj); // ตรวจสอบค่าหลังจากแปลงเป็น object

        function tagTemplate(tagData) {
            return `
    <tag title="${tagData.User_ID}"
            contenteditable='false'
            spellcheck='false'
            tabIndex="-1"
            class="tagify__tag ${tagData.class ? tagData.class : ""}"
            ${this.getAttributes(tagData)}>
        <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
        <div>
            <div class='tagify__tag__avatar-wrap'>
                <img onerror="this.style.visibility='hidden'" src="${tagData.User_Image}">
            </div>
            <span class='tagify__tag-text'>${tagData.User_FirstName} ${tagData.User_Lastname}</span>
        </div>
    </tag>
`;
        }

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

        // ดึงข้อมูลจาก API
        async function fetchUsers() {
            try {
                let response = await fetch('ajax/fetch_owner.php');
                if (!response.ok) throw new Error('Network response was not ok');
                let data = await response.json();

                return data.map(user => ({
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

        // Initialize Tagify
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
                whitelist: userList // ใช้ข้อมูลจาก API
            });

            console.log(preSelectedOwnerObj);
            console.log(userList)


            // Pre-select the owner if a value is provided in the input field
            preSelectedOwnerObj.forEach(owner => {
                const preSelectedUser = userList.find(user => user.User_ID == owner.User_ID);
                if (preSelectedUser) {
                    usrList.addTags([preSelectedUser]); // เพิ่ม tag
                }
            });

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
    </script>


    <script>
        // เก็บข้อมูล Subcategory
        let subcategory = [];

        // ============================
        //        Fetch Categories
        // ============================

        fetch('ajax/get_category.php')
            .then(response => response.json())
            .then(data => {
                const categories = data.map(item => ({
                    value: item.value,
                    id: item.id
                }));

                console.log('Categories:', categories); // ตรวจสอบข้อมูลที่ได้จาก API

                const categoryInput = document.querySelector('input[name=category]');

                // สร้าง Tagify สำหรับ Category
                const tagifyCategory = new Tagify(categoryInput, {
                    whitelist: categories,
                    userInput: false,
                    maxTags: 1,
                    placeholder: "เลือกหมวดหมู่..."
                });

                tagifyCategory.on('change', (e) => {
                    console.log('Tagify Change Event:', e.detail);

                    const selectedCategory = JSON.parse(e.detail.value)[0];

                    const selectedCategoryId = selectedCategory ? selectedCategory.id : null;
                    console.log('Selected Category ID:', selectedCategoryId);

                    document.querySelector('input[name=category_id]').value = selectedCategoryId;

                    // อัปเดต Subcategories
                    updateSubcategories(selectedCategoryId);
                });

                // เมื่อมีการลบ tag
                tagifyCategory.on('remove', (e) => {
                    console.log('Tag removed:', e.detail);

                    // เคลียร์ค่าใน input ของ category_id
                    document.querySelector('input[name=category_id]').value = '';
                    console.log('Category ID input cleared');
                });

            })
            .catch(error => {
                console.error('Error fetching categories:', error);
                alert('ไม่สามารถโหลดข้อมูลหมวดหมู่ได้');
            });

        // ============================
        //       Fetch Subcategories
        // ============================

        fetch('ajax/get_subcategory.php')
            .then(response => response.json())
            .then(data => {
                // เก็บ Subcategory ในตัวแปร
                subcategory = data.map(item => ({
                    value: item.value,
                    id: item.id,
                    category: item.category_id
                }));

                const subcategoryInput = document.querySelector('.blog-tags');

                // ใช้ Tagify สำหรับ Subcategory (เริ่มต้น whitelist ว่าง)
                window.tagifySubcategory = new Tagify(subcategoryInput, {
                    whitelist: subcategory.map(item => item.value), // Set the whitelist here after fetching data
                    userInput: false,
                    placeholder: "เลือกหมวดหมู่ย่อย..."
                });

                // เมื่อเปลี่ยนค่า Subcategory
                tagifySubcategory.on('change', (e) => {
                    console.log('Subcategory Change Event:', e.detail); // ตรวจสอบข้อมูล Event

                    if (e.detail.value) {
                        // แปลงค่า e.detail.value จาก string เป็น array
                        const selectedTags = JSON.parse(e.detail.value); // แปลง string เป็น array

                        // console.log('Parsed Selected Tags:', selectedTags);

                        // แยก ID ของ subcategories ที่เลือก
                        const selectedSubcategoryIds = selectedTags.map(tag => {
                            const subcategoryItem = subcategory.find(item => item.value === tag.value);
                            return subcategoryItem ? subcategoryItem.id : null; // หาค่า id ของ subcategory ที่ตรงกับ tag
                        }).filter(id => id !== null); // กรองค่า null ออก

                        // console.log('Selected Subcategory IDs:', selectedSubcategoryIds);

                        // ส่งค่า ID ไปที่ input ที่ซ่อน
                        const subcategoryInputElement = document.querySelector('input[name=subcategory_id]');
                        subcategoryInputElement.value = selectedSubcategoryIds.join(','); // รวม ID ด้วยเครื่องหมาย ","
                        console.log('Updated Subcategory ID input value:', subcategoryInputElement.value);
                    } else {
                        console.log('No subcategory tag selected');
                        const subcategoryInputElement = document.querySelector('input[name=subcategory_id]');
                        subcategoryInputElement.value = ''; // หากไม่มีการเลือก subcategory ให้เคลียร์ค่า
                    }
                });


            })
            .catch(error => {
                console.error('Error fetching subcategories:', error);
                alert('ไม่สามารถโหลดข้อมูลหมวดหมู่ย่อยได้');
            });

        // ============================
        //    Update Subcategories
        // ============================

        function updateSubcategories(selectedCategoryId) {
            if (!selectedCategoryId) {
                tagifySubcategory.settings.whitelist = [];
                tagifySubcategory.removeAllTags();
                tagifySubcategory._updateSettings();
                return;
            }

            const filteredSubcategories = subcategory.filter(item => item.category === selectedCategoryId);

            tagifySubcategory.settings.whitelist = filteredSubcategories.map(item => item.value);
            tagifySubcategory.removeAllTags();
            tagifySubcategory._updateSettings();
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

    <!-- END PAGE LEVEL SCRIPTS -->
</body>

</html>

<?php

include "../../db/connection.php";
session_start();


// ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Insert_Project'])) {

        
        // var_dump($_POST);
        var_dump($_SESSION);
        
        // รับค่าที่ส่งมาจากฟอร์ม
        $project_title = $_POST['project_title'];
        $project_detail = $_POST['project_detail'];
        $project_owner = $_POST['project_owner'];
        $cover_image_path = $_POST['cover_image'];
        $CreateBy_UserID = $_SESSION['User_ID'];

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

        // var_dump($cover_image_path);

        // แปลง JSON เป็น array
        $owner_data = json_decode($project_owner, true);

        if (is_array($owner_data)) {
            echo "User_ID ทั้งหมด:\n";
            $user_ids = []; // สร้าง array เก็บ User_ID ทั้งหมด

            // วนลูปดึงค่า User_ID
            foreach ($owner_data as $owner) {
                if (isset($owner['User_ID'])) {
                    $user_ids[] = $owner['User_ID']; // เก็บ User_ID ไว้ใน array
                }
            }

            // แสดงผลโดยคั่นด้วยเครื่องหมาย ,
            echo implode(", ", $user_ids);
        } else {
            echo "ไม่สามารถแปลง JSON หรือไม่พบข้อมูล";
        }

        // ตรวจสอบค่าที่ได้รับจากฟอร์ม
        if (empty($project_title) || empty($project_detail) || empty($project_owner)) {
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

        // เตรียมคำสั่ง SQL สำหรับการ INSERT ข้อมูลโปรเจ็ค
        $sql = "INSERT INTO project (Project_Title, Project_Detail, Project_CreateBy) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $project_title, $project_detail, $CreateBy_UserID);

            if ($stmt->execute()) {
                $project_id = $stmt->insert_id; // รับ Project_ID ที่เพิ่งเพิ่มเข้าไป

                // INSERT ข้อมูลรูปภาพ
                $sql_image = "INSERT INTO projectimages (Project_ID, Cover_Path, Slideshow_Path) VALUES (?, ?, ?)";
                $stmt_image = $conn->prepare($sql_image);
                $stmt_image->bind_param("iss", $project_id, $cover_image_path, $slideshow_image_path);
                $stmt_image->execute();
                $stmt_image->close();

                // วนลูป INSERT ข้อมูล User_ID ลงใน projectusers
                $sql_user = "INSERT INTO projectusers (Project_ID, User_ID) VALUES (?, ?)";
                $stmt_user = $conn->prepare($sql_user);

                foreach ($user_ids as $user_id) {
                    $stmt_user->bind_param("is", $project_id, $user_id);
                    $stmt_user->execute();
                }
                $stmt_user->close();

                // เคลียร์ session หลังจากบันทึกข้อมูลเสร็จ
                session_start();
                unset($_SESSION['cover_image']);
                unset($_SESSION['project_title']);
                unset($_SESSION['project_detail']);
                unset($_SESSION['project_owner']);
                unset($_SESSION['slideshow_image']);

                // แจ้งเตือนด้วย SweetAlert สำหรับการบันทึกสำเร็จ
                echo "<script>
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกข้อมูลสำเร็จ',
                            text: 'ข้อมูลของคุณถูกบันทึกลงในฐานข้อมูลแล้ว',
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
                            text: 'ไม่สามารถบันทึกข้อมูลโปรเจ็คได้: " . addslashes($stmt->error) . "',
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