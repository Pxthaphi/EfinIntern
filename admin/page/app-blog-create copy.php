<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Preview'])) {

        $cover_image = $_POST['cover_image'] ?? ''; // รับ path ของรูป
        $project_title = $_POST['project_title'];
        $project_detail = $_POST['project_detail'];
        $project_owner = $_POST['project_owner'];
        $project_slideshow = $_POST['slideshow_image'];

        if ($project_title == '' && $project_detail == '' && $project_owner == '') {
            echo "<script>
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'warning',
                            title: 'ระวัง!!',
                            text: 'กรุณากรอกข้อมูลให้ครบทุกช่อง',
                            confirmButtonText: 'ตกลง'
                            }).then(() => {
                            window.location.href = 'app-blog-create.php'; // เปลี่ยนเส้นทางถ้าจำเป็น
                        });
                    }, 100);
                  </script>";
        } else {
            $projectData = [
                'project_cover' => $cover_image,
                'project_title' => $project_title,
                'project_description' => $project_detail,
                'project_owner' => $project_owner,
                'project_slideshow' => $project_slideshow,
                'start_date' => '2024-12-01',
                'end_date' => '2025-12-01',
                'status' => 'กำลังดำเนินการ'
            ];

            $file_name = 'project_' . time() . '.json'; // ใช้ .json เพื่อเก็บข้อมูลในรูปแบบ JSON

            $file_path = 'preview/data/' . $file_name;

            // บันทึกข้อมูลลงในไฟล์ JSON
            file_put_contents($file_path, json_encode($projectData));

            var_dump($projectData);

            // ส่งชื่อไฟล์ไปยัง preview/index.php
            header('Location: preview/index.php?file=' . $file_name);
            exit();
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
    <?php include 'navbar.php' ?>
    <!--  END NAVBAR  -->
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <?php include 'sidebar.php' ?>
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
                        <!-- <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">App</a></li>
                                <li class="breadcrumb-item"><a href="#">Blog</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create</li>
                            </ol>
                        </nav> -->
                    </div>
                    <!-- /BREADCRUMB -->

                    <form method="POST" enctype="multipart/form-data">
                        <div class="row mb-4 layout-spacing layout-top-spacing">
                            <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">

                                <div class="widget-content widget-content-area blog-create-section">
                                    <div class="row mb-4">
                                        <h5 class="title-form mb-3">แบรนเนอร์หน้าปก</h5>

                                        <div class="multiple-file-upload">
                                            <input type="file"
                                                class="filepond file-upload-multiple"
                                                name="file-project"
                                                id="project-images"
                                                data-allow-reorder="true"
                                                data-max-file-size="5MB"
                                                data-max-files="1">
                                            <input type="text" id="cover_image" class="form-control mt-2" readonly placeholder="Path ของไฟล์จะปรากฏที่นี่">

                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label class="title-form">ชื่อโปรเจค</label>
                                            <input type="text" class="form-control title-form" id="project_title" name="project_title" placeholder="ชื่อโปรเจค" required>
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
                                        <h5 class="title-form mb-3">รูปเพิ่มเติม </h5> <span style="color: #FF6767FF;">*จะแสดงที่ตัว Slide Show</span>
                                    </div>
                                    <div class="dropzone" id="mydropzone">
                                        <div class="mb-3">
                                            <i class="display-4 text-muted mdi mdi-cloud-upload-outline"></i>
                                        </div>
                                        <div class="dropzone-previews"></div>
                                    </div>
                                    <input type="hidden" id="slideshow_image" name="slideshow_image">
                                </div>
                            </div>


                            <div class="col-xxl-3 col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-xxl-0 mt-4">
                                <div class="widget-content widget-content-area blog-create-section">
                                    <div class="row">
                                        <div class="col-xxl-12 col-md-12 mb-4">
                                            <label for="category title-form">หมวดหมู่</label>
                                            <input id="category" name="category" placeholder="Choose...">
                                        </div>
                                        <div class="col-xxl-12 col-md-12 mb-4">
                                            <label for="tags title-form">Tags</label>
                                            <input id="tags" class="blog-tags" value="" name="tag">
                                        </div>
                                        <div class="col-xxl-12 col-md-12 mb-4">
                                            <button type="submit" name="Preview" value="Preview" class="btn btn-secondary w-100">Preview</button>
                                        </div>
                                        <div class="col-xxl-12 col-sm-4 col-12 mx-auto">
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

    <script src="../src/plugins/src/tagify/tagify.min.js"></script>

    <script src="../src/assets/js/apps/blog-create.js"></script>

    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="../src/plugins/src/dropzone/dropzone.min.js"></script>
    <script src="../src/plugins/src/sweetalerts2/sweetalert2@11.js"></script>

    <!-- FilePond Setting For Upload Cover -->
    <script>
        FilePond.registerPlugin(FilePondPluginFileValidateType, FilePondPluginFileValidateSize, FilePondPluginImagePreview);

        FilePond.create(document.querySelector('#project-images'), {
            labelIdle: 'ลากไฟล์มาวางที่นี่ หรือ <span class="filepond--label-action">เลือกไฟล์</span>',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '5MB',
            maxFiles: 1,
            allowMultiple: false,
            server: {
                process: 'ajax/cover_upload.php',
                remove: 'ajax/cover_delete.php',
                onload: (response) => {
                    console.log('Response received:', response); // Log the raw response
                    try {
                        const data = JSON.parse(response); // Try to parse the response
                        console.log('Parsed data:', data); // Log the parsed data object
                        if (data.status === 'success') {
                            console.log('File uploaded successfully:', data.file);
                            const path = `uploads/cover/${data.file}`;
                            console.log('File path:', path);
                        } else {
                            console.error('Upload error:', data.message);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        console.error('Raw response:', response); // Log the raw response in case of parsing failure
                    }
                },

                onremove: (response) => {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        console.log('ลบไฟล์สำเร็จ');
                        document.querySelector('#cover_image').value = ''; // Clear value when file is removed
                    } else {
                        console.error('เกิดข้อผิดพลาดในการลบไฟล์');
                    }
                }
            },
            name: 'file-project'
        });
    </script>


    <!-- Quill Setting -->
    <script>
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

        // Fucntion Upload picture from Project Detail to Folder With Ajax
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

                        const response = await fetch('ajax/quillupload.php', {
                            method: 'POST',
                            body: formData,
                        });

                        if (!response.ok) throw new Error('Upload failed');

                        const data = await response.json();
                        if (data.success) {
                            // แก้ไขการสร้าง path ให้ลบเครื่องหมาย / ออกหนึ่งตัว
                            const imagePath = `ajax/${data.path}`.replace('ajax//', 'ajax/'); // ลบ / ที่เกินออก

                            quill.deleteText(range.index, loadingText.length);
                            quill.insertEmbed(range.index, 'image', imagePath);
                        } else {
                            alert('อัปเดตภาพไม่สำเร็จ: ' + data.message);
                        }
                    } catch (error) {
                        alert('มีข้อผิดพลาดในการอัปโหลด: ' + error.message);
                    }
                }
            };
        });

        // Fucntion Delete Image in folder uploads From Project Detail With Ajax
        quill.on('text-change', function(delta, oldContents, source) {
            const removedImages = oldContents.ops.filter(op => op.insert && op.insert.image);
            if (removedImages.length > 0) {
                removedImages.forEach(async (image) => {
                    let imagePath = image.insert.image;

                    // console.log("Final Image Path:", imagePath);

                    if (imagePath && typeof imagePath === 'string') {

                        imagePath = imagePath.replace('ajax/', '');

                        // console.log("Cleaned Image Path:", imagePath);

                        try {
                            const response = await fetch('ajax/quillupload.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `imagePath=${encodeURIComponent(imagePath)}`
                            });

                            const data = await response.json();
                            if (data.success) {
                                console.log('Image deleted successfully');
                            } else {
                                console.log('Failed to delete image');
                            }
                        } catch (error) {
                            console.error('Error deleting image:', error);
                        }
                    } else {
                        console.log("Invalid image path:", imagePath);
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
            maxFiles: 3,
            autoProcessQueue: true,
            parallelUploads: 1,
            acceptedFiles: ".png,.jpg,.jpeg",
            previewsContainer: '.dropzone-previews',
            addRemoveLinks: true,
            init: function() {
                var dzMessage = document.querySelector(".dz-message");
                var myDropzone = this;

                this.on("addedfile", function() {
                    if (dzMessage && this.files.length > 0) {
                        dzMessage.style.display = "none";
                    }
                });

                this.on("removedfile", function() {
                    if (this.files.length === 0 && dzMessage) {
                        dzMessage.style.display = "block";
                    }
                });

                this.on("removedfile", function(file) {
                    var fileName = file.name;
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "ajax/dropzone_delete.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            console.log("File removed successfully: " + fileName);
                        }
                    };
                    xhr.send("filename=" + encodeURIComponent(fileName));
                });

                this.on("error", function(file, errorMessage) {
                    console.error("Upload error:", errorMessage);
                });

                this.on("success", function(file, response) {
                    var filePath = response.replace("File uploaded: ", "").trim();

                    updateSlideshowInput(filePath);
                });

                function updateSlideshowInput(filePath) {
                    var inputField = document.querySelector("input[name='slideshow_image']");
                    var currentValue = inputField.value;

                    if (currentValue) {
                        inputField.value = currentValue + ',' + filePath;
                    } else {
                        inputField.value = filePath;
                    }
                }

                this.on("queuecomplete", function() {
                    if (dzMessage && myDropzone.files.length === 0) {
                        dzMessage.style.display = "block";
                    }
                });
            }
        });
    </script>

    <script>
        /**
         * 
         * Users List
         *  
         **/

        var inputElm = document.querySelector('input[name=project_owner]');

        function tagTemplate(tagData) {
            return `
        <tag title="${tagData.email}"
                contenteditable='false'
                spellcheck='false'
                tabIndex="-1"
                class="tagify__tag ${tagData.class ? tagData.class : ""}"
                ${this.getAttributes(tagData)}>
            <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
            <div>
                <div class='tagify__tag__avatar-wrap'>
                    <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
                </div>
                <span class='tagify__tag-text'>${tagData.name}</span>
            </div>
        </tag>
    `
        }

        function suggestionItemTemplate(tagData) {
            return `
        <div ${this.getAttributes(tagData)}
            class='tagify__dropdown__item ${tagData.class ? tagData.class : ""}'
            tabindex="0"
            role="option">
            ${ tagData.avatar ? `
            <div class='tagify__dropdown__item__avatar-wrap'>
                <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
            </div>` : ''
            }
            <strong>${tagData.name}</strong>
            <span>${tagData.email}</span>
        </div>
    `
        }

        // initialize Tagify on the above input node reference
        var usrList = new Tagify(inputElm, {
            tagTextProp: 'name', // very important since a custom template is used with this property as text
            enforceWhitelist: true,
            skipInvalid: true, // do not remporarily add invalid tags
            dropdown: {
                closeOnSelect: false,
                enabled: 0,
                classname: 'users-list',
                searchKeys: ['name', 'email'] // very important to set by which keys to search for suggesttions when typing
            },
            templates: {
                tag: tagTemplate,
                dropdownItem: suggestionItemTemplate
            },
            whitelist: [{
                    "value": 1,
                    "name": "Justinian Hattersley",
                    "avatar": "https://i.pravatar.cc/80?img=1",
                    "email": "jhattersley0@ucsd.edu"
                },
                {
                    "value": 2,
                    "name": "Antons Esson",
                    "avatar": "https://i.pravatar.cc/80?img=2",
                    "email": "aesson1@ning.com"
                },
                {
                    "value": 3,
                    "name": "Ardeen Batisse",
                    "avatar": "https://i.pravatar.cc/80?img=3",
                    "email": "abatisse2@nih.gov"
                },
                {
                    "value": 4,
                    "name": "Graeme Yellowley",
                    "avatar": "https://i.pravatar.cc/80?img=4",
                    "email": "gyellowley3@behance.net"
                },
                {
                    "value": 5,
                    "name": "Dido Wilford",
                    "avatar": "https://i.pravatar.cc/80?img=5",
                    "email": "dwilford4@jugem.jp"
                },
                {
                    "value": 6,
                    "name": "Celesta Orwin",
                    "avatar": "https://i.pravatar.cc/80?img=6",
                    "email": "corwin5@meetup.com"
                },
                {
                    "value": 7,
                    "name": "Sally Main",
                    "avatar": "https://i.pravatar.cc/80?img=7",
                    "email": "smain6@techcrunch.com"
                },
                {
                    "value": 8,
                    "name": "Grethel Haysman",
                    "avatar": "https://i.pravatar.cc/80?img=8",
                    "email": "ghaysman7@mashable.com"
                },
                {
                    "value": 9,
                    "name": "Marvin Mandrake",
                    "avatar": "https://i.pravatar.cc/80?img=9",
                    "email": "mmandrake8@sourceforge.net"
                },
                {
                    "value": 10,
                    "name": "Corrie Tidey",
                    "avatar": "https://i.pravatar.cc/80?img=10",
                    "email": "ctidey9@youtube.com"
                },
                {
                    "value": 11,
                    "name": "foo",
                    "avatar": "https://i.pravatar.cc/80?img=11",
                    "email": "foo@bar.com"
                },
                {
                    "value": 12,
                    "name": "foo",
                    "avatar": "https://i.pravatar.cc/80?img=12",
                    "email": "foo.aaa@foo.com"
                },
            ]
        })

        usrList.on('dropdown:show dropdown:updated', onDropdownShow)
        usrList.on('dropdown:select', onSelectSuggestion)

        var addAllSuggestionsElm;

        function onDropdownShow(e) {
            var dropdownContentElm = e.detail.tagify.DOM.dropdown.content;

            if (usrList.suggestedListItems.length > 1) {
                addAllSuggestionsElm = getAddAllSuggestionsElm();

                // insert "addAllSuggestionsElm" as the first element in the suggestions list
                dropdownContentElm.insertBefore(addAllSuggestionsElm, dropdownContentElm.firstChild)
            }
        }

        function onSelectSuggestion(e) {
            if (e.detail.elm == addAllSuggestionsElm)
                usrList.dropdown.selectAll();
        }

        // create a "add all" custom suggestion element every time the dropdown changes
        function getAddAllSuggestionsElm() {
            // suggestions items should be based on "dropdownItem" template
            return usrList.parseTemplate('dropdownItem', [{
                class: "addAll",
                name: "Add all",
                email: usrList.whitelist.reduce(function(remainingSuggestions, item) {
                    return usrList.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1
                }, 0) + " Members"
            }])
        }
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

        // รับค่าที่ส่งมาจากฟอร์ม
        $project_title = $_POST['project_title'];
        $project_detail = $_POST['project_detail'];
        $project_owner = $_POST['project_owner'];

        // เตรียมคำสั่ง SQL สำหรับการ insert
        $sql = "INSERT INTO project (project_title, project_detail) VALUES (?, ?)";

        // ใช้ prepared statement เพื่อป้องกัน SQL Injection
        if ($stmt = $conn->prepare($sql)) {
            // ผูกตัวแปรเข้ากับ statement
            $stmt->bind_param("ss", $project_title, $project_detail);

            // ดำเนินการคำสั่ง SQL
            if ($stmt->execute()) {
                // แจ้งเตือนด้วย SweetAlert สำหรับการบันทึกสำเร็จ
                echo "<script>
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกข้อมูลสำเร็จ',
                            text: 'ข้อมูลของคุณถูกบันทึกลงในฐานข้อมูลแล้ว',
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            window.location.href = 'index.php'; // เปลี่ยนเส้นทางถ้าจำเป็น
                        });
                    }, 100);
                  </scrip>";
            } else {
                // แจ้งเตือนด้วย SweetAlert สำหรับข้อผิดพลาด
                echo "<script>
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถบันทึกข้อมูลได้: " . addslashes($stmt->error) . "',
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