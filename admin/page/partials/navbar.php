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

<div class="header-container container-xxl">
    <header class="header navbar navbar-expand-sm expand-header">

        <a href="javascript:void(0);" class="sidebarCollapse">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </a>

        <ul class="navbar-item flex-row ms-lg-auto ms-0">

            <!-- ห้ามลบ -->
            <li class="nav-item theme-toggle-item">
                <a href="#" class="nav-link theme-toggle">
                </a>
            </li>
            <!-- ห้ามลบ -->

            <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <!-- Avatar Container -->
                        <div class="avatar-container me-3">
                            <div class="avatar avatar-sm avatar-indicators avatar-online">
                                <img alt="avatar" src="<?= $user_data['User_Image']?>" class="rounded-circle">
                            </div>
                        </div>
                        <!-- Name and Role -->
                        <div>
                            <p class="mb-0 fw-bold text-dark"><?= $user_data['User_Firstname']?></p>
                            <p class="mb-0 text-muted"><?= $user_data['User_Type']?></p>
                        </div>
                        <!-- Dropdown Icon -->
                        <iconify-icon icon="iconamoon:arrow-down-2" width="24" height="24" class="ms-2"></iconify-icon>
                    </div>
                </a>


                <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                    <div class="user-profile-section">
                        <div class="media mx-auto">
                            <div class="media-body">
                                <h5><?= $user_data['User_Firstname']?></h5>
                                <p><?= $user_data['Position_Name']?></p>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-item">
                        <a href="user-profile.html" class="d-flex align-items-center">
                            <iconify-icon icon="iconoir:user" width="24" height="24" class="me-2"></iconify-icon>
                            <span>Profile</span>
                        </a>
                    </div>
                    <div class="dropdown-item">
                        <a href="../../logout.php" class="d-flex align-items-center">
                            <iconify-icon icon="mynaui:logout" width="24" height="24" class="me-2"></iconify-icon>
                            </svg> <span>Log Out</span>
                        </a>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>