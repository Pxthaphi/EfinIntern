<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Login | efin Intern Space</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico" />
    <link href="admin/layouts/modern-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="admin/layouts/modern-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="admin/layouts/modern-light-menu/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="admin/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="admin/layouts/modern-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="admin/src/assets/css/light/authentication/auth-cover.css" rel="stylesheet" type="text/css" />

    <link href="admin/layouts/modern-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <link href="admin/src/assets/css/dark/authentication/auth-cover.css" rel="stylesheet" type="text/css" />

    <link href="assets/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />

    <!-- END GLOBAL MANDATORY STYLES -->


</head>

<body class="form">

    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <div class="auth-container d-flex">

        <div class="container mx-auto align-self-center">

            <div class="row">

                <div class="col-6 d-lg-flex d-none h-100 my-auto top-0 start-0 text-center justify-content-center flex-column">
                    <div class="auth-cover-bg-image"></div>
                    <div class="auth-overlay"></div>

                </div>
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center ms-lg-auto me-lg-0 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-12 mb-5">
                                        <img class="logo-login" src="assets/images/logo/efin_internspace.webp" alt="Efin_Internspace">
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">ชื่อผู้ใช้</label>
                                            <input type="text" class="form-control" name="username">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label">รหัสผ่าน</label>
                                            <div class="input-group">
                                                <input type="password" id="passwordInput" class="form-control" name="password">
                                                <span class="toggle-password" id="togglePassword">
                                                    <i class="fas fa-eye-slash" id="toggleIcon"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <div class="mb-3">
                                            <div class="form-check form-check-primary form-check-inline">
                                                <input class="form-check-input me-3 " type="checkbox" id="form-check-default">
                                                <label class="form-check-label" for="form-check-default">
                                                    จดจำรหัสผ่าน
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-4">
                                            <button type="submit" class="btn btn-success w-100" name="login">เข้าสู่ระบบ</button>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <div class="text-center">
                                            <p class="mb-0">Copyright ⓒ 2024 <a href="https://www.efinancethai.com/" class="text-success">efinanceThai.com</a> All rights reserved</p>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="admin/src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sweetalert2@11.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');
        const toggleIcon = document.querySelector('#toggleIcon');

        togglePassword.addEventListener('click', function() {
            // เปลี่ยน type ของ input ระหว่าง 'password' และ 'text'
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            // เปลี่ยนไอคอนระหว่างเปิดตาและปิดตา
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
    </script>


</body>

</html>

<?php

include "db/connection.php";
$errors = array();

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($username) || empty($password)) {
        array_push($errors, "กรุณากรอก username และ password ให้ครบ!!");
    } else {
        $query = "SELECT username, password, User_ID, User_Type FROM user WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            $_SESSION['username'] = $username;
            $_SESSION['user_name'] = $row['username'];
            $_SESSION['User_Type'] = $row['User_Type'];
            $_SESSION['User_ID'] = $row['User_ID'];

            // ตั้งค่า redirect URL ตาม User_Type
            $redirect_url = '';
            if ($_SESSION['User_Type'] == 'superadmin') {
                $redirect_url = 'admin/page/index.php';
            } elseif ($_SESSION['User_Type'] == 'admin') {
                $redirect_url = 'admin/page/index.php';
            } elseif ($_SESSION['User_Type'] == 'officer') {
                $redirect_url = 'projects.php';
            } else {
                $redirect_url = 'index.php';
            }

            // แสดง SweetAlert
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "เข้าสู่ระบบสำเร็จ",
                    text: "โปรดรอสักครู่",
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    window.location = ' . json_encode($redirect_url) . ';
                });
            </script>';
        } else {
            array_push($errors, "ไม่สามารถเข้าสู่ระบบได้ โปรดลองใหม่อีกครั้งนึง");
        }
    }

    if (count($errors) > 0) {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                html: ' . json_encode(implode("<br>", $errors)) . '
            });
        </script>';
    }
}
?>