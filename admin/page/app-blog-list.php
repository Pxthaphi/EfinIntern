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
    <title>Blog List | CORK - Multipurpose Bootstrap Dashboard Template </title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico" />
    <link href="../layouts/modern-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="../layouts/modern-light-menu/loader.js"></script>
    <script src="../src/icontify/iconify-icon.min.js"></script>
    <!-- <link rel="stylesheet" href="../../assets/fonts/fonts/addfont.css"> -->
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link rel="stylesheet" type="text/css" href="../src/plugins/src/table/datatable/datatables.css">

    <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/table/datatable/dt-global_style.css">
    <!--  END CUSTOM STYLE FILE  -->

    <style>
        #blog-list img {
            border-radius: 5px;
        }

        .profile-img-list {
            position: relative;
            border: 2px solid white;
            /* เส้นขอบขาว */
        }

        .profile-img-list:first-child {
            z-index: 1;
        }

        .profile-img-list+.profile-img-list {
            z-index: 2;
            margin-left: -10px;
            /* ระยะทับซ้อน */
        }
    </style>

</head>

<body class="layout-boxed" data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="140">

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
    <?php include "partials/navbar.php" ?>
    <!--  END NAVBAR  -->


    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <?php include "partials/sidebar.php" ?>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">

            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                    <!-- BREADCRUMB -->
                    <div class="page-meta">
                        <div class="d-flex justify-content-between">
                            <!-- <button class="btn btn-light">
                                <p class="title-form">ย้อนกลับ</p>
                            </button> -->
                            <h2 class="title-form">โปรเจคทั้งหมด</h2>
                            <a href="app-blog-create.php" class="btn btn-success d-flex align-items-center justify-content-center">
                                <iconify-icon icon="basil:add-outline" width="24" height="24" class="me-1"></iconify-icon>
                                <span class="btn-text-inner form-title">สร้างโปรเจค</span>
                            </a>

                        </div>
                    </div>
                    <!-- /BREADCRUMB -->

                    <div class="row layout-top-spacing">

                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                                <?php
                                include '../../db/connection.php';

                                $sql = "SELECT project.*, 
                                            GROUP_CONCAT(projectimages.ProjectImages_ID) AS ProjectImages_IDs, 
                                            GROUP_CONCAT(projectimages.Cover_Path) AS Cover_Paths, 
                                            GROUP_CONCAT(projectimages.Slideshow_Path) AS Slideshow_Paths,
                                            GROUP_CONCAT(user.User_Image) AS User_Images, 
                                            GROUP_CONCAT(user.User_ID) AS User_IDs, 
                                            GROUP_CONCAT(intern.Intern_StartDate) AS Intern_StartDates
                                        FROM `project` 
                                        JOIN projectimages ON project.Project_ID = projectimages.Project_ID
                                        JOIN projectusers ON project.Project_ID = projectusers.Project_ID 
                                        JOIN user ON projectusers.User_ID = user.User_ID 
                                        LEFT JOIN intern ON intern.User_ID = user.User_ID
                                        WHERE user.User_Type = 'intern'
                                        GROUP BY project.Project_ID
                                        ";

                                $result = $conn->query($sql);
                                ?>

                                <table id="blog-list" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="checkbox-column no-content"></th>
                                            <th class="">ชื่อผลงาน</th>
                                            <th class="">ประเภท</th>
                                            <th class="">ปีที่ฝึกงาน</th>
                                            <th class="">ผู้จัดทำ</th>
                                            <th class="">เพิ่มโดย</th>
                                            <th class="no-content text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                // Split the concatenated strings into arrays
                                                $userImages = explode(',', $row['User_Images']);
                                                $userIDs = explode(',', $row['User_IDs']);
                                                $coverPaths = explode(',', $row['Cover_Paths']);
                                                $slideshowPaths = explode(',', $row['Slideshow_Paths']);
                                                $internStartDates = explode(',', $row['Intern_StartDates']);
                                                $years = array(); // Array to store the years

                                                foreach ($internStartDates as $startDate) {
                                                    if (!empty($startDate)) {
                                                        $year = date('Y', strtotime($startDate));
                                                        if (!in_array($year, $years)) {
                                                            $years[] = $year + 543;
                                                        }
                                                    }
                                                }

                                                $displayYear = !empty($years) ? $years[0] : '';
                                        ?>
                                                <tr data-id="<?= $row['Project_ID'] ?>">
                                                    <td>
                                                        <div class="form-check form-check-success d-block new-control">
                                                            <input class="form-check-input child-chk" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="app-blog-detail.php?Project_ID=<?= $row['Project_ID'] ?>" class="d-flex justify-content-left align-items-center">
                                                            <div class="avatar me-3">
                                                                <?php
                                                                if (!empty($coverPaths[0])) {
                                                                    echo '<img src="' . $coverPaths[0] . '" alt="Project Cover" width="64" height="64">';
                                                                } else {
                                                                    echo '<img src="../src/assets/img/default-cover.jpg" alt="Project Cover" width="64" height="64">';
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <span class="text-truncate fw-bold"><?= $row['Project_Title'] ?></span>
                                                            </div>
                                                        </a>
                                                    </td>
                                                    <td><span class="badge badge-success"><?= $row['Category_Name'] ?></span></td>
                                                    <td><?= $displayYear ?></td>
                                                    <td>
                                                        <div class="d-flex justify-content-start align-items-center">
                                                            <?php
                                                            foreach ($userImages as $image) {
                                                                if (!empty($image)) {
                                                                    echo '<img src="' . $image . '" class="rounded-circle profile-img-list" alt="avatar" width="40" height="40">';
                                                                } else {
                                                                    echo '<img src="../src/assets/img/profile-9.jpeg" class="rounded-circle profile-img-list" alt="avatar" width="40" height="40">';
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge badge-success"><?= $row['Project_CreateBy'] ?></span></td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <a href="app-blog-edit.php?Project_ID=<?= $row['Project_ID'] ?>" class="bs-tooltip me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="แก้ไข" data-original-title="Edit">
                                                                <iconify-icon icon="fluent:edit-20-regular" width="24" height="24" class="me-1"></iconify-icon>
                                                            </a>
                                                            <a href="#" data-id="<?= $row['Project_ID']; ?>" class="bs-tooltip delete-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="ลบ" data-original-title="Delete">
                                                                <iconify-icon icon="fluent:delete-16-regular" width="24" height="24" class="me-1"></iconify-icon>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>

                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>No records found</td></tr>";
                                        }
                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>

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
    <script src="../src/plugins/src/global/vendors.min.js"></script>
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../src/plugins/src/waves/waves.min.js"></script>
    <script src="../layouts/modern-light-menu/app.js"></script>
    <script src="../src/assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="../src/plugins/src/table/datatable/datatables.js"></script>
    <script src="../src/plugins/src/sweetalerts2/sweetalert2@11.js"></script>

    <script>
        $(document).ready(function() {
            blogList = $('#blog-list').DataTable({
                headerCallback: function(e, a, t, n, s) {
                    e.getElementsByTagName("th")[0].innerHTML = `
                    <div class="form-check form-check-success d-block new-control">
                        <input class="form-check-input chk-parent" type="checkbox" id="form-check-default">
                    </div>`;
                },
                columnDefs: [{
                    targets: 0,
                    width: "30px",
                    className: "",
                    orderable: !1,
                    render: function(e, a, t, n) {
                        return `
                        <div class="form-check form-check-success d-block new-control">
                            <input class="form-check-input child-chk" type="checkbox" id="form-check-default">
                        </div>`;
                    }
                }],
                "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'<'btn-delete-container'>f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
                "oLanguage": {
                    "oPaginate": {
                        "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    "sInfo": "หน้าที่ _PAGE_ / _PAGES_",
                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder": "ค้นหา....",
                    "sLengthMenu": "จำนวนที่แสดง :  _MENU_",
                },
                "stripeClasses": [],
                "lengthMenu": [5, 10, 20, 50],
                "pageLength": 10
            });

            // เพิ่มปุ่มลบใน container
            $("div.btn-delete-container").html(`
            <button id="delete-selected-btn" class="btn btn-danger mx-2">
                ลบข้อมูล
            </button>
        `);

            multiCheck(blogList);

            // ซ่อนปุ่มลบเมื่อเริ่มต้น
            $('#delete-selected-btn').hide(); // ซ่อนปุ่มลบ

            // ตรวจสอบการเลือก checkbox ที่ header (Select/Deselect All)
            $('#blog-list').on('change', '.chk-parent', function() {
                var isChecked = $(this).prop('checked');
                $('.child-chk').prop('checked', isChecked);

                // แสดง/ซ่อนปุ่มลบตามจำนวน checkbox ที่เลือก
                if ($('.child-chk:checked').length > 0) {
                    $('#delete-selected-btn').show();
                } else {
                    $('#delete-selected-btn').hide();
                }
            });

            // ตรวจสอบการเลือก checkbox ในแถว
            $('#blog-list').on('change', '.child-chk', function() {
                // เช็คว่า checkbox ใดถูกเลือก
                if ($('.child-chk:checked').length > 0) {
                    $('#delete-selected-btn').show();
                } else {
                    $('#delete-selected-btn').hide();
                }
            });

            // เมื่อคลิกปุ่มลบหลายรายการ
            $('#delete-selected-btn').on('click', function() {
                var selectedIds = [];
                $('.child-chk:checked').each(function() {
                    selectedIds.push($(this).closest('tr').data('id')); // ดึง id จากแต่ละแถวที่เลือก
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        title: 'กรุณาเลือกข้อมูล',
                        text: 'คุณยังไม่ได้เลือกข้อมูลที่จะลบ',
                        icon: 'warning',
                        showConfirmButton: false, // Hide the confirm button
                        timer: 2000
                    });
                    return;
                }

                console.log("Select ID For Delete : ", selectedIds);

                deleteMultiple(selectedIds); // ฟังก์ชันสำหรับการลบหลายๆ รายการ
            });

            // ฟังก์ชันลบหลายๆ รายการ
            function deleteMultiple(ids) {
                Swal.fire({
                    title: 'แน่ใจใช่ไหม ?',
                    text: "หากลบ จะไม่สามารถกู้ข้อมูลกลับมาได้อีก!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4CBB17',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ตกลง',
                    cancelButtonText: 'ยกเลิก',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            $.ajax({
                                    url: 'ajax/delete_project.php',
                                    type: 'POST',
                                    data: {
                                        deleteMultiple: true,
                                        ids: ids
                                    },
                                })
                                .done(function(response) {
                                    const data = JSON.parse(response); // Ensure the response is parsed as JSON
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'ลบสำเร็จ',
                                            text: 'ลบข้อมูลโปรเจคสำเร็จ',
                                            showConfirmButton: true,
                                        }).then(() => {
                                            document.location.href = 'app-blog-list.php'; // ไปที่หน้าโปรเจคหลังจากลบสำเร็จ
                                        });
                                    } else {
                                        Swal.fire('ผิดพลาด', data.message, 'error');
                                    }
                                })
                                .fail(function() {
                                    Swal.fire('ผิดพลาด', 'โปรดลองอีกครั้ง', 'error');
                                });
                        });
                    },
                });
            }
        });
    </script>

    <!-- END PAGE LEVEL SCRIPTS -->
</body>

</html>

<script>
    $(document).ready(function() {
        // เมื่อคลิกลิงก์ลบในแต่ละแถว (ถังขยะ)
        $('.delete-btn').on('click', function(e) {
            e.preventDefault(); // ป้องกันไม่ให้ลิงก์ทำงาน

            var projectId = $(this).data('id'); // ดึง ID ของโปรเจคที่ต้องการลบ

            Swal.fire({
                title: 'แน่ใจใช่ไหม ?',
                text: "หากลบ จะไม่สามารถกู้ข้อมูลกลับมาได้อีก!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4CBB17',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก',
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                                url: 'ajax/delete_project.php',
                                type: 'GET', // ใช้ GET เพราะเป็นการลบโปรเจค 1 รายการ
                                data: {
                                    delete: projectId
                                },
                            })
                            .done(function(response) {
                                const data = JSON.parse(response); // Ensure the response is parsed as JSON
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'ลบสำเร็จ',
                                        text: 'ลบข้อมูลโปรเจคสำเร็จ',
                                        showConfirmButton: true,
                                    }).then(() => {
                                        document.location.href = 'app-blog-list.php'; // ไปที่หน้าโปรเจคหลังจากลบสำเร็จ
                                    });
                                } else {
                                    Swal.fire('ผิดพลาด', data.message, 'error');
                                }
                            })
                            .fail(function() {
                                Swal.fire('ผิดพลาด', 'โปรดลองอีกครั้ง', 'error');
                            });
                    });
                },
            });
        });
    });
</script>