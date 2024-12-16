<header class="header--white header-nav">
    <div class="header--builder">
        <div class="container">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <div class="h-100 d-flex flex-row align-items-center">
                    <a href="projects.php">
                        <img src="./assets/images/logo/efin_internspace.webp" class="efinLogo" alt="Efin_logo" width="400px">
                    </a>
                </div>
                <?php
                include 'db/connection.php';
                $User_ID = $_SESSION['User_ID'];
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
                ?>
                    <div class="d-flex flex-row align-items-center p-0 h-100">
                        <a href="projects.php" class="nav-text fw-bold fs-13-md me-4" style="color: black !important;"> หน้าแรก</a>

                        <!-- Dropdown Profile -->
                        <div class="dropdown">
                            <a href="#" class="ms-2 d-flex justify-content-center align-items-center" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <!-- Avatar Container -->
                                <div class="avatar-container me-2">
                                    <div class="avatar avatar-sm avatar-indicators avatar-online">
                                        <img alt="avatar" src="<?= substr($user_data['User_Image'], 6) ?>" class="rounded-circle" width="40" height="40">
                                    </div>
                                </div>
                                <!-- Name and Role -->
                                <div>
                                    <p class="mb-0 fw-bold fs-13-md text-dark"><?= $user_data['User_Firstname'] . " " . $user_data['User_Lastname'] ?></p>
                                </div>
                                <iconify-icon icon="iconamoon:arrow-down-2" width="24" height="24"></iconify-icon>
                            </a>
                            <ul class="dropdown-menu align-items-start" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item fs-16" href="profile.php">โปรไฟล์ของฉัน</a></li>
                                <li><a class="dropdown-item fs-16" href="settings.php">การตั้งค่า</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item fs-16" href="logout.php">ออกจากระบบ</a></li>
                            </ul>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="d-flex flex-row align-items-center p-0 h-100">
                        <a href="projects.php" class="nav-text fw-bold fs-13-md" style="color: black !important;"> หน้าแรก</a>
                        <a href="login.php" class="nav-text fw-bold text-white active-button fs-13-md" id="navbtn"> เข้าสู่ระบบ</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</header>