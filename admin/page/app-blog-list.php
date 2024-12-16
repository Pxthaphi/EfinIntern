<?php
include "../../db/connection.php";

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $sql = "DELETE FROM project WHERE Project_ID = $delete_id";
    $stmt = mysqli_query($conn, $sql);
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

                                $sql = "SELECT project.*, user.User_Image , intern.*, projectimages.*
                                        FROM `project`
                                        JOIN user ON user.User_ID = project.Project_UserID
                                        JOIN intern ON user.User_ID = intern.User_ID
                                        JOIN projectimages ON project.Project_ID = projectimages.Project_ID";
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
                                            <th class="no-content text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) { ?>
                                                    <td><?= $row['Project_ID'] ?></td>
                                                    <td>
                                                        <a href="app-blog-detail.php" class="d-flex justify-content-left align-items-center">
                                                            <div class="avatar me-3">
                                                                <img src="<?= $row['Cover_Path'] ?>" alt="Avatar" width="64" height="64">
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <span class="text-truncate fw-bold"><?= $row['Project_Title'] ?></span>
                                                            </div>
                                                        </a>
                                                    </td>
                                                    <td><span class="badge badge-success"><?= $row['Category_Name'] ?></span></td>
                                                    <td><?= $row['Intern_StartDate'] ?></td>
                                                    <td>
                                                        <div class="d-flex justify-content-start align-items-center">
                                                            <?php
                                                            if (!empty($row['User_Image'])) {
                                                                $userImagePath = $row['User_Image'];
                                                                echo '<img src="' . $userImagePath . '" class="rounded-circle profile-img-list" alt="avatar" width="40" height="40">';
                                                            } else {
                                                                echo '<img src="../src/assets/img/profile-9.jpeg" class="rounded-circle profile-img-list" alt="avatar" width="40" height="40">';
                                                            }
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <a href="app-blog-edit.php?Project_ID=<?= $row['Project_ID'] ?>" class="bs-tooltip me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="แก้ไข" data-original-title="Edit">
                                                                <iconify-icon icon="fluent:edit-20-regular" width="24" height="24" class="me-1"></iconify-icon>
                                                            </a>
                                                            <a data-id="<?= $row["Project_ID"]; ?>" href="?delete=<?= $row["Project_ID"]; ?>" class="bs-tooltip delete-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="ลบ" data-original-title="Delete">
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
        blogList = $('#blog-list').DataTable({
            headerCallback: function(e, a, t, n, s) {
                e.getElementsByTagName("th")[0].innerHTML = `
                <div class="form-check form-check-success d-block new-control">
                    <input class="form-check-input chk-parent" type="checkbox" id="form-check-default">
                </div>`
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
                    </div>`
                }
            }],
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
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
        multiCheck(blogList);
    </script>
    <!-- END PAGE LEVEL SCRIPTS -->
</body>

</html>

<script>
    $(".delete-btn").click(function (e) {
        var project_id = $(this).data('id');
        e.preventDefault();
        deleteConfirm(project_id);
    })

    function deleteConfirm(project_id) {
        Swal.fire({
            title: 'แน่ใจใช่มั้ย ?',
            text: "หากลบ จะไม่สามารถกู้ข้อมูลกลับมาได้อีก!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4CBB17',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve) {
                    $.ajax({
                        url: 'app-blog-list.php',
                        type: 'GET',
                        data: 'delete=' + project_id,
                    })
                        .done(function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ',
                                text: 'สำเร็จ',
                            }).then(() => {
                                document.location.href = 'app-blog-list.php';
                            })
                        })
                        .fail(function () {
                            Swal.fire('ผิดพลาด', 'โปรดลองอีกครั้ง', 'error')
                            window.location.reload();
                        });
                });
            },
        });
    }
</script>
