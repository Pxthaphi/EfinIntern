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
    <title>Post Content | CORK - Multipurpose Bootstrap Dashboard Template </title>
    <link rel="icon" type="image/x-icon" href="../src/assets/img/favicon.ico" />
    <link href="../layouts/modern-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="../layouts/modern-light-menu/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="../src/assets/css/light/elements/custom-pagination.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../src/assets/css/light/apps/blog-post.css">

    <link href="../src/assets/css/dark/elements/custom-pagination.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../src/assets/css/dark/apps/blog-post.css">
    <!--  END CUSTOM STYLE FILE  -->
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
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">App</a></li>
                                <li class="breadcrumb-item"><a href="#">Blog</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Post</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->



                    <div class="row layout-top-spacing">

                        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
                            <?php
                            $Project_ID = $_GET['Project_ID'] ?? '';

                            include '../../db/connection.php';

                            $sql = "SELECT project.*, 
                                            GROUP_CONCAT(projectimages.ProjectImages_ID) AS ProjectImages_IDs, 
                                            GROUP_CONCAT(projectimages.Cover_Path) AS Cover_Paths, 
                                            GROUP_CONCAT(projectimages.Slideshow_Path) AS Slideshow_Paths,
                                            GROUP_CONCAT(user.User_Image) AS User_Images, 
                                            GROUP_CONCAT(user.User_ID) AS User_IDs, 
                                            GROUP_CONCAT(intern.Intern_StartDate) AS Intern_StartDates
                                    FROM `project` 
                                    LEFT JOIN projectimages ON project.Project_ID = projectimages.Project_ID
                                    LEFT JOIN projectusers ON project.Project_ID = projectusers.Project_ID 
                                    LEFT JOIN user ON projectusers.User_ID = user.User_ID 
                                    LEFT JOIN intern ON intern.User_ID = user.User_ID
                                    WHERE project.Project_ID = ?
                                    GROUP BY project.Project_ID";

                            $stmt = $conn->prepare($sql);

                            $stmt->bind_param("s", $Project_ID);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();

                                $coverPaths = $row['Cover_Paths'];
                                $slideshowPaths = $row['Slideshow_Paths'];
                                $userImages = $row['User_Images'];
                                $userIDs = $row['User_IDs'];
                                $internStartDates = $row['Intern_StartDates'];
                                $projectDescription = $row['Project_Detail'];

                                $coverPathsArray = explode(',', $coverPaths);
                                $slideshowPathsArray = explode(',', $slideshowPaths);
                                $userImagesArray = explode(',', $userImages);
                                $userIDsArray = explode(',', $userIDs);
                                $internStartDatesArray = explode(',', $internStartDates);

                                //---- แปลง Tag html จาก <ol> เป็น <ul> ----//
                                $projectDescription = preg_replace('/<ol([^>]*)>/', '<ul$1>', $projectDescription);
                                $projectDescription = str_replace('</ol>', '</ul>', $projectDescription);
                            } else {
                                echo "No project found.";
                            }
                            ?>

                            <div class="single-post-content">

                                <div class="featured-image">
                                    <!-- Display the first cover image -->
                                    <img src="<?= $coverPathsArray[0] ?>" alt="Featured Image" class="fix-procard" />
                                </div>

                                <div class="post-content col-lg-10 col-sm-10 col-12 fix-procard ">
                                    <!-- Additional content can go here -->
                                    <?= $projectDescription; ?>
                                </div>

                                <div class="post-info">
                                    <hr>

                                    <div class="post-tags mt-5">
                                        <span class="badge badge-primary mb-2">Admin</span>
                                        <span class="badge badge-primary mb-2">Themeforeset</span>
                                        <span class="badge badge-primary mb-2">Dashboard</span>
                                        <span class="badge badge-primary mb-2">Top 10</span>
                                    </div>

                                    <div class="post-dynamic-meta mt-5 mb-5">
                                        <button class="btn btn-secondary me-4 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart">
                                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                            </svg>
                                            <span class="btn-text-inner">1.1k</span>
                                        </button>

                                        <div class="avatar--group mb-2">
                                            <?php
                                            // Display avatars of users
                                            foreach ($userImagesArray as $userImage) {
                                                echo '<div class="avatar avatar-sm m-0">';
                                                echo '<img alt="avatar" src="' . $userImage . '" class="rounded-circle">';
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>

                    </div>

                </div>

                <!--  BEGIN FOOTER  -->
                <div class="footer-wrapper">
                    <div class="footer-section f-section-1">
                        <p class="">Copyright © <span class="dynamic-year">2022</span> <a target="_blank" href="https://designreset.com/cork-admin/">DesignReset</a>, All rights reserved.</p>
                    </div>
                    <div class="footer-section f-section-2">
                        <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg></p>
                    </div>
                </div>
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
        <script src="../src/plugins/src/highlight/highlight.pack.js"></script>
        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!-- END PAGE LEVEL SCRIPTS -->
</body>

</html>