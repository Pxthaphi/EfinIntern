<?php
include('../../db/connection.php');

$User_ID = $_SESSION['User_ID'];

if (!isset($User_ID)) {
?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "กรุณาล็อกอินก่อนใช้งาน!!",
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            document.location.href = '../../index.php'; // เปลี่ยนไปที่หน้าเข้าสู่ระบบ
        });
    </script>
<?php
    exit();
}

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_data = null;
$prefix = "";
$avatar_image = "";
$user_name = "";
$index = "";

if ($User_ID) {
    $sql = "SELECT user.* , position.* FROM user JOIN position ON user.User_PositionID = position.Position_ID WHERE User_ID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $User_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        $stmt->close();    
    } else {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }
}
?>

<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">

        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="./index.php">
                        <img src="../src/assets/img/logo.svg" class="navbar-logo" alt="logo">
                    </a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                    </svg>
                </div>
            </div>
        </div>
        <div class="profile-info">
            <div class="user-info">
                <div class="profile-img">
                    <img src="<?= $user_data['User_Image']?>" alt="avatar">
                </div>
                <div class="profile-content">
                    <h6 class=""><?= $user_data['User_Firstname']?></h6>
                    <p class=""><?= $user_data['User_Type']?></p>
                </div>
            </div>
        </div>

        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu menu-heading">
                <div class="heading">
                    </svg><span>MAIN MENU</span></div>
            </li>

            <li class="menu <?php if ($menu == "Dashboard") {
                                echo "active";
                            } ?> ">
                <a href="./index.php" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            <li class="menu <?php if ($menu == "Project") {
                                echo "active";
                            } ?>">
                <a href="./app-blog-list.php" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M7.25 6a.75.75 0 0 0-.75.75v7.5a.75.75 0 0 0 1.5 0v-7.5A.75.75 0 0 0 7.25 6M12 6a.75.75 0 0 0-.75.75v4.5a.75.75 0 0 0 1.5 0v-4.5A.75.75 0 0 0 12 6m4 .75a.75.75 0 0 1 1.5 0v9.5a.75.75 0 0 1-1.5 0z" />
                            <path fill="currentColor" d="M3.75 2h16.5c.966 0 1.75.784 1.75 1.75v16.5A1.75 1.75 0 0 1 20.25 22H3.75A1.75 1.75 0 0 1 2 20.25V3.75C2 2.784 2.784 2 3.75 2M3.5 3.75v16.5c0 .138.112.25.25.25h16.5a.25.25 0 0 0 .25-.25V3.75a.25.25 0 0 0-.25-.25H3.75a.25.25 0 0 0-.25.25" />
                        </svg>
                        <span>โปรเจค</span>
                    </div>
                </a>
            </li>
            <li class="menu <?php if ($menu == "User") {
                                echo "active";
                            } ?>">
                <a href="app-user-list.php" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M19.75 4A2.25 2.25 0 0 1 22 6.25v11.505a2.25 2.25 0 0 1-2.25 2.25H4.25A2.25 2.25 0 0 1 2 17.755V6.25A2.25 2.25 0 0 1 4.25 4zm0 1.5H4.25a.75.75 0 0 0-.75.75v11.505c0 .414.336.75.75.75h15.5a.75.75 0 0 0 .75-.75V6.25a.75.75 0 0 0-.75-.75m-10 7a.75.75 0 0 1 .75.75v.493l-.008.108c-.163 1.113-1.094 1.65-2.492 1.65s-2.33-.537-2.492-1.65l-.008-.11v-.491a.75.75 0 0 1 .75-.75zm3.502.496h4.498a.75.75 0 0 1 .102 1.493l-.102.007h-4.498a.75.75 0 0 1-.102-1.493zh4.498zM8 8.502a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m5.252.998h4.498a.75.75 0 0 1 .102 1.493L17.75 11h-4.498a.75.75 0 0 1-.102-1.493zh4.498z" />
                        </svg>
                        <span>ผู้ใช้</span>
                    </div>
                </a>
            </li>
            <li class="menu <?php if ($menu == "Intern") {
                                echo "active";
                            } ?>">
                <a href="app-intern-list.php" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5">
                                <path d="M1 20v-1a7 7 0 0 1 7-7v0a7 7 0 0 1 7 7v1" />
                                <path d="M13 14v0a5 5 0 0 1 5-5v0a5 5 0 0 1 5 5v.5" />
                                <path stroke-linejoin="round" d="M8 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8m10-3a3 3 0 1 0 0-6a3 3 0 0 0 0 6" />
                            </g>
                        </svg>
                        <span>นักศึกษาฝึกงาน</span>
                    </div>
                </a>
            </li>
            <li class="menu <?php if ($menu == "Category") {
                                echo "active";
                            } ?>">
                <a href="./category.php" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                                <circle cx="17" cy="7" r="3" />
                                <circle cx="7" cy="17" r="3" />
                                <path d="M14 14h6v5a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM4 4h6v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1z" />
                            </g>
                        </svg>
                        <span>หมวดหมู่</span>
                    </div>
                </a>
            </li>
            <li class="menu <?php if ($menu == "Position") {
                                echo "active";
                            } ?>">
                <a href="#" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                            <path fill="currentColor" d="M16 2a5 5 0 0 0-1.001 9.9v3.099H9.733A2.733 2.733 0 0 0 7 17.732V20.1A5.002 5.002 0 0 0 8 30a5 5 0 0 0 1-9.9v-2.368c0-.405.329-.733.733-.733h12.534c.405 0 .733.328.733.733V20.1a5.002 5.002 0 0 0 1 9.9a5 5 0 0 0 1-9.9v-2.368a2.733 2.733 0 0 0-2.733-2.733H17V11.9A5.002 5.002 0 0 0 16 2m-3 5a3 3 0 1 1 6 0a3 3 0 0 1-6 0M5 25a3 3 0 1 1 6 0a3 3 0 0 1-6 0m19-3a3 3 0 1 1 0 6a3 3 0 0 1 0-6" />
                        </svg>
                        <span>ตำแหน่ง</span>
                    </div>
                </a>
            </li>
        </ul>

    </nav>

</div>