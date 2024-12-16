<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $menu = "User";
    $submenu = "edit_user";
    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Account Settings | CORK - Multipurpose Bootstrap Dashboard Template </title>
    <?php include "partials/header.php" ?>
</head>

<body class=" layout-boxed">

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
    <?php
    include "partials/navbar.php"
    ?>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="cs-overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <?php
        include "partials/sidebar.php"
        ?>
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
                            <h2 class="title-form">แก้ข้อมูลผู้ใช้งาน</h2>
                        </div>
                    </div>
                    <!-- /BREADCRUMB -->

                    <div class="account-settings-container layout-top-spacing">

                        <div class="account-content">
                            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                <form class="section general-info" method="POST" enctype="multipart/form-data">
                                    <?php
                                    include '../../db/connection.php';

                                    // รับ User_ID จาก URL
                                    $User_ID = isset($_GET['User_ID']) ? $_GET['User_ID'] : null;

                                    if ($User_ID) {
                                        // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
                                        $query = "SELECT * FROM user WHERE User_ID = ?";
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param("s", $User_ID);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            // เก็บข้อมูลผู้ใช้ในตัวแปร
                                            $userData = $result->fetch_assoc();
                                        } else {
                                            echo "ไม่พบข้อมูลผู้ใช้";
                                        }
                                    }
                                    ?>

                                    <div class="info">
                                        <div class="col-lg-11 mx-auto mt-5 mb-5">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-12 col-md-4">
                                                    <div class="profile-image  mt-4 pe-md-4">
                                                        <div class="img-uploader-content">
                                                            <input type="file"
                                                                class="filepond"
                                                                name="User_Profile"
                                                                id="User_Profile"
                                                                data-allow-reorder="true"
                                                                data-max-file-size="2MB"
                                                                data-max-files="1">
                                                            <input type="text" id="profile_image" name="profile_image" class="form-control mt-2" readonly placeholder="Path ของไฟล์จะปรากฏที่นี่" value="<?php echo isset($userData['User_Image']) ? $userData['User_Image'] : ''; ?>">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0">
                                                    <div class="form">
                                                        <div class="row g-3">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="User_Prefix">คำนำหน้าชื่อ</label>
                                                                    <select class="form-select mb-3" id="User_Prefix" name="User_Prefix">
                                                                        <option>คำนำหน้าชื่อ</option>
                                                                        <option value="นาย" <?php echo ($userData['User_Prefix'] == 'นาย') ? 'selected' : ''; ?>>นาย</option>
                                                                        <option value="นางสาว" <?php echo ($userData['User_Prefix'] == 'นางสาว') ? 'selected' : ''; ?>>นางสาว</option>
                                                                        <option value="นาง" <?php echo ($userData['User_Prefix'] == 'นาง') ? 'selected' : ''; ?>>นาง</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label for="fullName">ชื่อ</label>
                                                                    <input type="text" class="form-control mb-3" id="User_Firstname" name="User_Firstname" placeholder="กรุณาป้อนชื่อ" value="<?php echo isset($userData['User_Firstname']) ? $userData['User_Firstname'] : ''; ?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label for="profession">นามสกุล</label>
                                                                    <input type="text" class="form-control mb-3" id="User_Lastname" name="User_Lastname" placeholder="กรุณาป้อนนามสกุล" value="<?php echo isset($userData['User_Lastname']) ? $userData['User_Lastname'] : ''; ?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="User_Gender">เพศ</label>
                                                                    <select class="form-select mb-3" id="User_Gender" name="User_Gender">
                                                                        <option>เลือกเพศ</option>
                                                                        <option value="ชาย" <?php echo ($userData['User_Gender'] == 'ชาย') ? 'selected' : ''; ?>>ชาย</option>
                                                                        <option value="หญิง" <?php echo ($userData['User_Gender'] == 'หญิง') ? 'selected' : ''; ?>>หญิง</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-10">
                                                                <div class="form-group">
                                                                    <label for="address">วัน/เดือน/ปี เกิด</label>
                                                                    <input id="rangeCalendarFlatpickr" class="form-control flatpickr flatpickr-input active" name="User_Birthday" type="text" placeholder="เลือกวัน/เดือน/ปีเกิด....">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="User_DepartmentID">แผนก</label>
                                                                    <select class="form-select mb-3" id="User_DepartmentID" name="User_DepartmentID">
                                                                        <option value="">กรุณาเลือกแผนก</option>
                                                                        <?php
                                                                        // ดึงข้อมูลแผนกจากตาราง department
                                                                        $query = "SELECT Department_ID, Department_Name FROM department";
                                                                        $result = $conn->query($query);

                                                                        if ($result->num_rows > 0) {
                                                                            while ($row = $result->fetch_assoc()) {
                                                                                $selected = ($userData['User_DepartmentID'] == $row['Department_ID']) ? "selected" : "";
                                                                                echo "<option value='{$row['Department_ID']}' $selected>{$row['Department_Name']}</option>";
                                                                            }
                                                                        } else {
                                                                            echo "<option value=''>ไม่มีข้อมูล</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="User_PositionID">ตำแหน่ง</label>
                                                                    <select class="form-select mb-3" id="User_PositionID" name="User_PositionID">
                                                                        <option value="">กรุณาเลือกตำแหน่ง</option>
                                                                        <?php
                                                                        // ดึงข้อมูลตำแหน่งจากตาราง position
                                                                        $query = "SELECT Position_ID, Position_Name FROM position";
                                                                        $result = $conn->query($query);

                                                                        if ($result->num_rows > 0) {
                                                                            while ($row = $result->fetch_assoc()) {
                                                                                $selected = ($userData['User_PositionID'] == $row['Position_ID']) ? "selected" : "";
                                                                                echo "<option value='{$row['Position_ID']}' $selected>{$row['Position_Name']}</option>";
                                                                            }
                                                                        } else {
                                                                            echo "<option value=''>ไม่มีข้อมูล</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="email">Email</label>
                                                                    <input type="email" class="form-control mb-3" id="email" name="User_Email" placeholder="กรุณาป้อนอีเมล" value="<?php echo isset($userData['User_Email']) ? $userData['User_Email'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="phone">เบอร์โทรศัพท์</label>
                                                                    <input type="text" class="form-control mb-3" id="phone" name="User_Phone" placeholder="กรุณาป้อนเบอร์โทรศัพท์" value="<?php echo isset($userData['User_PhoneNumber']) ? $userData['User_PhoneNumber'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="email">ชื่อผู้ใช้งาน</label>
                                                                    <input type="text" class="form-control mb-3" id="email" name="Username" placeholder="กรุณาป้อนชื่อผู้ใช้" value="<?php echo isset($userData['Username']) ? $userData['Username'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group position-relative">
                                                                    <label for="password">รหัสผ่าน</label>
                                                                    <input type="password" class="form-control mb-3 pe-5" id="password" name="Password" placeholder="กรุณาป้อนรหัสผ่าน" value="<?php echo isset($userData['Password']) ? $userData['Password'] : ''; ?>">
                                                                    <!-- Iconify icon for eye-off initially -->
                                                                    <iconify-icon icon="fluent:eye-off-20-regular" width="24" height="24" class="position-absolute top-50 end-0 me-3" id="passwordIcon" style="cursor: pointer;"></iconify-icon>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 mt-4 d-flex justify-content-end ">
                                                                <!-- <div class="form-group text-end">
                                                                        <button class="btn btn-success d-flex align-items-center justify-content-center" type="submit" name="Insert_User">
                                                                            <iconify-icon icon="fluent:save-20-regular" width="24" height="24" class="me-1"></iconify-icon>
                                                                            <span class="btn-text-inner form-title">บันทึกข้อมูล</span>
                                                                        </button>
                                                                    </div> -->
                                                                <button type="submit" name="Insert_User" class="btn btn-success">กดเลย</button>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../src/plugins/src/waves/waves.min.js"></script>
    <script src="../layouts/modern-light-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <script src="../src/plugins/src/filepond/filepond.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginFileValidateType.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageCrop.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageResize.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageTransform.min.js"></script>
    <script src="../src/plugins/src/filepond/filepondPluginFileValidateSize.min.js"></script>
    <script src="../src/plugins/src/notification/snackbar/snackbar.min.js"></script>
    <script src="../src/plugins/src/sweetalerts2/sweetalerts2.min.js"></script>
    <script src="../src/assets/js/users/account-settings.js"></script>

    <script src="../src/plugins/src/flatpickr/flatpickr.js"></script>

    <script src="../src/plugins/src/flatpickr/lang/th.js"></script>
    <script src="../src/plugins/src/sweetalerts2/sweetalert2@11.js"></script>


    <!--  END CUSTOM SCRIPTS FILE  -->

    <!-- Filepond Upload Profile -->

    <script>
        let uploadedFilePath = '<?php echo isset($userData['User_Image']) ? "/assets/images/profile/" . $userData['User_Image'] : ''; ?>'; // Path ของรูปภาพจาก PHP

        console.log(uploadedFilePath); // ตรวจสอบ Path

        FilePond.registerPlugin(FilePondPluginFileValidateType, FilePondPluginImageExifOrientation, FilePondPluginImagePreview, FilePondPluginImageCrop, FilePondPluginImageResize, FilePondPluginImageTransform);

        const pond = FilePond.create(document.querySelector('#User_Profile'), {
            imagePreviewHeight: 170,
            imageCropAspectRatio: '1:1',
            imageResizeTargetWidth: 200,
            imageResizeTargetHeight: 200,
            stylePanelLayout: 'compact circle',
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',
            labelIdle: 'ลากไฟล์มาวางที่นี่ หรือ <span class="filepond--label-action">เลือกไฟล์</span>',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '3MB',
            maxFiles: 1,
            allowMultiple: false,
            server: {
                process: 'ajax/profile_upload.php',
            },
            name: 'User_Profile',
            onprocessfile: (error, file) => {
                if (!error) {
                    const serverData = JSON.parse(file.serverId);
                    const filePath = serverData.path;
                    uploadedFilePath = filePath;

                    document.querySelector('#profile_image').value = filePath;
                }
            },
            onremovefile: () => {
                fetch('ajax/profile_delete.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        'file': uploadedFilePath
                    }),
                }).then(response => response.json()).then(data => {
                    if (data.status === 'success') {
                        document.querySelector('#profile_image').value = '';
                    }
                });
            },
        });

        // ถ้ามีรูปภาพอยู่แล้ว ให้โหลดใน FilePond
        if (uploadedFilePath) {
            pond.addFile({
                source: uploadedFilePath, // กำหนดให้เป็น URL ของไฟล์ที่สามารถเข้าถึงได้
                options: {
                    type: 'local', // ระบุว่าเป็นไฟล์ที่มาจากเซิร์ฟเวอร์
                    file: {
                        name: uploadedFilePath.split('/').pop(),
                        type: 'image/webp', // ชนิดไฟล์ที่ถูกต้อง
                    },

                }
            }).then(() => {
                console.log('Existing image loaded:', uploadedFilePath);
            }).catch(error => {
                console.error('Error loading existing image:', error);
            });
        }
    </script>



    <!-- Datepicker -->

    <script>
        var flatpicker = flatpickr(document.getElementById('rangeCalendarFlatpickr'), {
            mode: "single",
            "locale": "th",
            "dateFormat": "d/m/Y",
            minDate: "2024-02-01",
        });

        flatpicker.changeYear(new Date().getFullYear() + 543)
    </script>

    <!-- Password Input -->
    <script>
        const togglePassword = document.getElementById('passwordIcon');
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('passwordIcon');

        togglePassword.addEventListener('click', () => {
            // Toggle password visibility
            const isPasswordHidden = passwordInput.type === 'password';
            passwordInput.type = isPasswordHidden ? 'text' : 'password';

            // Toggle icon
            passwordIcon.setAttribute('icon', isPasswordHidden ? 'fluent:eye-20-regular' : 'fluent:eye-off-20-regular');
        });
    </script>
</body>

</html>

<?php
include '../../db/connection.php';

if (isset($_POST['Insert_User'])) {

    // var_dump($_POST);

    $profile_image = $_POST['profile_image'] ?? '';
    $profile_image = preg_replace('/^(\.\.\/)/', '', $profile_image);

    $user_prefix = $_POST['User_Prefix'] ?? '';
    $user_firstname = $_POST['User_Firstname'] ?? '';
    $user_lastname = $_POST['User_Lastname'] ?? '';
    $user_gender = $_POST['User_Gender'] ?? '';
    $user_birthday = $_POST['User_Birthday'] ?? '';
    $user_phone = $_POST['User_Phone'] ?? '';
    $user_department = $_POST['User_DepartmentID'] ?? '';
    $user_position = $_POST['User_PositionID'] ?? '';
    $username = $_POST['Username'] ?? '';
    $password = $_POST['Password'] ?? '';

    // Convert the date from B.E. (Thai) to A.D. (Gregorian)
    $date_parts = explode('/', $user_birthday); // Split the date into day, month, year
    $day = $date_parts[0];
    $month = $date_parts[1];
    $year_be = $date_parts[2];

    // Convert from Buddhist Era to Gregorian (subtract 543 years)
    $year_ad = (int)$year_be - 543;

    // Reformat to YYYY-MM-DD
    $formatted_birthday = sprintf('%04d-%02d-%02d', $year_ad, $month, $day);
    $user_email = $_POST['User_Email'] ?? '';

    $result = $conn->query("SELECT MAX(User_ID) AS max_id FROM user WHERE User_ID LIKE 'Off_Efin%'");
    $new_id = 'Off_Efin001';
    if ($result && $row = $result->fetch_assoc()) {
        if ($row['max_id']) {
            $last_id_number = (int)substr($row['max_id'], 8);
            $new_id = 'Off_Efin' . str_pad($last_id_number + 1, 3, '0', STR_PAD_LEFT);
        }
    }

    $user_type = 'officer';

    $stmt = $conn->prepare("
        INSERT INTO `user` (
            `User_ID`, `User_Image`, `User_Prefix`, `User_Firstname`, 
            `User_Lastname`, `User_Gender`, `User_Birthday`, `User_Email`, 
            `User_PhoneNumber`, `User_DepartmentID`, `User_PositionID`, 
            `Username`, `Password`, `User_Type`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "sssssssssiisss",
        $new_id,
        $profile_image,
        $user_prefix,
        $user_firstname,
        $user_lastname,
        $user_gender,
        $formatted_birthday,
        $user_email,
        $user_phone,
        $user_department,
        $user_position,
        $username,
        $password,
        $user_type
    );

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: 'บันทึกข้อมูลสำเร็จ'
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'app-user-list.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถบันทึกข้อมูลได้: {$stmt->error}'
            });
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>