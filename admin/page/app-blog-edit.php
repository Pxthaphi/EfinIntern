<?php
include "../../db/connection.php";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Define session expiration time (5 minutes = 300 seconds)
$session_timeout = 300;

// Retrieve data from POST or SESSION, with fallback to empty string
$project_title = $_POST['project_title'] ?? $_SESSION['project_title'] ?? '';
$project_detail = $_POST['project_detail'] ?? $_SESSION['project_detail'] ?? '';
$project_owner = $_POST['project_owner'] ?? $_SESSION['project_owner'] ?? '';
$cover_image = $_POST['cover_image'] ?? $_SESSION['cover_image'] ?? '';
$project_slideshow = $_POST['slideshow_image'] ?? $_SESSION['slideshow_image'] ?? '';
$category = $_POST['category'];
$subcategory = $_POST['subcategory'];

// Check if the session data has been set and if it has expired
if (isset($_SESSION['project_time'])) {
    $session_age = time() - $_SESSION['project_time'];

    // If session data is older than 5 minutes, unset the session variables
    if ($session_age > $session_timeout) {
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
        $category = $_POST['category'];
        $subcategory = $_POST['subcategory'];


        // Store the current time in session to track session expiration
        $_SESSION['project_time'] = time();

        // Check if required fields are empty
        if ($project_title == '' && $project_detail == '' && $project_owner == '') {
            echo "<script>
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'warning',
                            title: 'ระวัง!!',
                            text: 'กรุณากรอกข้อมูลให้ครบทุกช่อง',
                            confirmButtonText: 'ตกลง'
                            }).then(() => {
                            window.location.href = 'app-blog-create.php'; // Redirect if needed
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
            // echo "<script>window.open('preview/index.php?file=$file_name&type=edit', '_blank');</script>";
            header('Location: preview/index.php?file=' . $file_name . '&type=edit');
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
                                            <input type="text" id="cover_image" name="cover_image" class="form-control mt-2" readonly placeholder="Path ของไฟล์จะปรากฏที่นี่">

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
                                            <input name='project_owner' value='' placeholder="กรุณาเลือกเจ้าของโปรเจค">
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
                                    <input type="hidden" id="slideshow_image" name="slideshow_image">
                                </div>
                            </div>


                            <div class="col-xxl-3 col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-xxl-0 mt-4">
                                <div class="widget-content widget-content-area blog-create-section">
                                    <div class="row">
                                        <div class="col-xxl-12 col-md-12 mb-4">
                                            <label for="category">หมวดหมู่</label>
                                            <input id="category" name="category" placeholder="เลือกหมวดหมู่...">
                                            <input type="text" name="category_id" id="category_id"> <!-- input ซ่อนสำหรับเก็บ Category_ID -->
                                        </div>

                                        <div class="col-xxl-12 col-md-12 mb-4">
                                            <label for="tags title-form">หมวดหมู่ย่อย</label>
                                            <input id="subcategory" class="blog-tags" value="" name="subcategory" placeholder="เลือกหมวดหมู่ย่อย...">
                                            <input type="text" name="subcategory_id" id="subcategory_id"> <!-- input ซ่อนสำหรับเก็บ Category_ID -->
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
    <script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>


    <script src="../src/plugins/src/tagify/tagify.min.js"></script>

    <!-- <script src="../src/assets/js/apps/blog-create.js"></script> -->

    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="../src/plugins/src/dropzone/dropzone.min.js"></script>
    <script src="../src/plugins/src/sweetalerts2/sweetalert2@11.js"></script>

    <script>
        FilePond.registerPlugin(FilePondPluginFileValidateType, FilePondPluginFileValidateSize, FilePondPluginImagePreview, FilePondPluginFilePoster);

        // สมมุติว่าเราได้ path ของไฟล์ที่ต้องการจากฐานข้อมูล
        const filePathFromDB = '../../assets/images/project/cover/ปก  1920 x 720.webp'; // แทนที่ด้วย path ที่ได้จากฐานข้อมูล

        FilePond.create(document.querySelector('#project-images'), {
            labelIdle: 'ลากไฟล์มาวางที่นี่ หรือ <span class="filepond--label-action">เลือกไฟล์</span>',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '5MB',
            maxFiles: 1,
            allowMultiple: false,
            server: {
                process: 'ajax/cover_upload.php',
            },
            name: 'file-project',
            files: [{
                source: filePathFromDB, // ใช้ path ของไฟล์ .webp ที่ได้จากฐานข้อมูล
                options: {
                    type: 'local',
                    metadata: {
                        poster: filePathFromDB // ใช้ FilePondPluginFilePoster เพื่อแสดงรูป
                    }
                }
            }],
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


    <!-- Quill Setting -->
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
            url: "ajax/dropzone_upload.php", // URL สำหรับการอัปโหลด
            paramName: "file[]", // ชื่อพารามิเตอร์ที่ใช้ส่งไฟล์
            uploadMultiple: true, // อัปโหลดไฟล์หลายไฟล์
            maxFilesize: 99, // ขนาดไฟล์สูงสุดที่อนุญาต
            maxFiles: 10, // จำนวนไฟล์สูงสุดที่อนุญาต
            autoProcessQueue: true, // อัปโหลดไฟล์โดยอัตโนมัติ
            parallelUploads: 1, // อัปโหลดทีละไฟล์
            acceptedFiles: ".png,.jpg,.jpeg", // ประเภทไฟล์ที่อนุญาต
            addRemoveLinks: true, // เพิ่มลิงก์สำหรับการลบไฟล์
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

                    var fileName = file.name;
                    var filePath = file.serverFilePath; // รับชื่อไฟล์ที่แปลงแล้วจากเซิร์ฟเวอร์

                    // ส่งคำขอลบไฟล์จากเซิร์ฟเวอร์
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "ajax/dropzone_delete.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            console.log("File removed successfully: " + fileName);
                        }
                    };
                    xhr.send("filename=" + encodeURIComponent(filePath));
                });

                // เมื่อเกิดข้อผิดพลาดในการอัปโหลด
                this.on("error", function(file, errorMessage) {
                    console.error("Upload error:", errorMessage);
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
                tagTextProp: 'User_FirstName', // ใช้ฟิลด์ที่ต้องการแสดงผล
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
        /**
         * =======================
         *        Category 
         * =======================
         */

        fetch('ajax/get_category.php')
            .then(response => response.json())
            .then(data => {
                var categories = data.map(item => ({
                    value: item.value,
                    id: item.id
                }));

                console.log(categories);


                var input = document.querySelector('input[name=category]');

                // ใช้ Tagify เพื่อแสดงหมวดหมู่
                var tagify = new Tagify(input, {
                    whitelist: categories, // ใส่ whitelist ที่เป็นอาร์เรย์ของหมวดหมู่
                    userInput: false,
                    maxTags: 1, // จำกัดให้เลือกได้แค่ 1 หมวดหมู่
                    // ฟังก์ชันเมื่อเลือกหมวดหมู่
                    onChange: function(e) {
                        if (e.detail.value) {
                            // ส่งค่า Category_ID ไปยัง input ที่ซ่อน
                            document.querySelector('input[name=category_id]').value = e.detail.tag.id;
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching categories:', error);
            });

        /**
         * =====================
         *      Subcaregory 
         * =====================
         */
        fetch('ajax/get_subcategory.php')
            .then(response => response.json())
            .then(data => {
                var subcategory = data.map(item => ({
                    value: item.value,
                    id: item.id,
                    category: item.category_id
                }));

                console.log(subcategory);


                var input = document.querySelector('.blog-tags');
                // ใช้ Tagify เพื่อแสดงหมวดหมู่
                var tagify = new Tagify(input, {
                    whitelist: subcategory, // ใส่ whitelist ที่เป็นอาร์เรย์ของหมวดหมู่
                    userInput: false,
                    // ฟังก์ชันเมื่อเลือกหมวดหมู่
                    onChange: function(e) {
                        if (e.detail.value) {
                            // ส่งค่า Category_ID ไปยัง input ที่ซ่อน
                            document.querySelector('input[name=subcategory_id]').value = e.detail.tag.id;
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching categories:', error);
            });
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

        // รับค่าที่ส่งมาจากฟอร์ม
        $project_title = $_POST['project_title'];
        $project_detail = $_POST['project_detail'];
        $project_owner = $_POST['project_owner'];
        $cover_image_path = $_POST['cover_image'];

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

        var_dump($cover_image_path);

        // แปลงค่า JSON เป็น array
        $owner_data = json_decode($project_owner, true);

        // ดึงค่า User_ID ออกมา
        if (is_array($owner_data) && isset($owner_data[0]['User_ID'])) {
            $project_owner = $owner_data[0]['User_ID'];
            // echo "User_ID: " . $user_id;
        } else {
            echo "ไม่พบข้อมูล User_ID";
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

        // เตรียมคำสั่ง SQL สำหรับการ insert ข้อมูลโปรเจ็ค
        $sql = "INSERT INTO project (Project_Title, Project_Detail, Project_UserID) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $project_title, $project_detail, $project_owner);

            if ($stmt->execute()) {
                $project_id = $stmt->insert_id;  // รับ Project_ID ที่เพิ่งเพิ่มเข้าไป

                // ถ้ามีการอัปโหลดไฟล์สำหรับ cover_image
                if (!empty($cover_image_path) && !empty($slideshow_image_path)) {
                    $sql_image = "INSERT INTO projectimages (Project_ID, Cover_Path, Slideshow_Path) VALUES (?, ?, ?)";
                    $stmt_image = $conn->prepare($sql_image);
                    $stmt_image->bind_param("iss", $project_id, $cover_image_path, $slideshow_image_path);
                    $stmt_image->execute();
                    $stmt_image->close();
                }

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