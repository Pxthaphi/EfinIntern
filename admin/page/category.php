<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $menu = "Category";
    $submenu = "Create";
    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Scrum Task Board | CORK - Multipurpose Bootstrap Dashboard Template </title>
    <link rel="icon" type="image/x-icon" href="../src/assets/img/favicon.ico" />
    <link href="../layouts/modern-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="../layouts/modern-light-menu/loader.js"></script>
    <link rel="stylesheet" href="../../../../template/assets/fonts/fonts/addfont.css">
    <script src="../src/icontify/iconify-icon.min.js"></script>


    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/modern-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->

    <link href="../src/assets/css/light/components/modal.css" rel="stylesheet" type="text/css">
    <link href="../src/assets/css/light/apps/scrumboard.css" rel="stylesheet" type="text/css" />

    <link href="../src/assets/css/dark/components/modal.css" rel="stylesheet" type="text/css">
    <link href="../src/assets/css/dark/apps/scrumboard.css" rel="stylesheet" type="text/css" />

    <!-- END PAGE LEVEL STYLES -->
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

                    <div class="d-flex justify-content-end action-btn layout-top-spacing mb-5">
                        <button id="add-list" class="btn btn-success">เพิ่มหมวดหมู่</button>
                    </div>
                    <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="add-task-title modal-title" id="addTaskModalTitleLabel1">Add Task</h5>
                                    <h5 class="edit-task-title modal-title" id="addTaskModalTitleLabel2">Edit Task</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="compose-box">
                                        <div class="compose-content" id="addTaskModalTitle">
                                            <div class="addTaskAccordion" id="add_task_accordion">
                                                <div class="task-content task-text-progress">
                                                    <div id="collapseTwo" class="collapse show" data-parent="#add_task_accordion">
                                                        <div class="task-content-body">
                                                            <form action="javascript:void(0);">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="task-title mb-4 d-flex">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 me-3 align-self-center">
                                                                                <path d="M12 20h9"></path>
                                                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                                                            </svg>
                                                                            <input id="s-task" type="text" placeholder="Task" class="form-control" name="task">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="task-badge d-flex">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star me-3">
                                                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                            </svg>
                                                                            <textarea id="s-text" placeholder="Task Text" class="form-control" name="taskText"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div> -->
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-bs-dismiss="modal"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg> <span class="btn-text-inner">Discard</span></button>
                                    <button data-btnfn="addTask" class="btn add-tsk btn-primary">Add Task</button>
                                    <button data-btnfn="editTask" class="btn edit-tsk btn-success" style="display: none;">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="addListModal" tabindex="-1" role="dialog" aria-labelledby="addListModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title add-list-title" id="addListModalTitleLabel1">เพิ่มข้อมูลหมวดหมู่</h5>
                                    <h5 class="modal-title edit-list-title" id="addListModalTitleLabel2">แก้ไขข้อมูลหมวดหมู่</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="compose-box">
                                        <div class="compose-content" id="addListModalTitle">
                                            <form action="javascript:void(0);">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="list-title d-flex">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list me-3 align-self-center">
                                                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                                                <line x1="3" y1="6" x2="3" y2="6"></line>
                                                                <line x1="3" y1="12" x2="3" y2="12"></line>
                                                                <line x1="3" y1="18" x2="3" y2="18"></line>
                                                            </svg>
                                                            <input id="s-list-name" type="text" placeholder="ป้อนชื่อหมวดหมู่" class="form-control" name="task">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-bs-dismiss="modal"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg> <span class="btn-text-inner">ยกเลิก</span></button>
                                    <button class="btn add-list btn-success form-title">บันทึกข้อมูล</button>
                                    <button class="btn edit-list btn-success" style="display: none;">บันทึกข้อมูล</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="deleteConformation" tabindex="-1" role="dialog" aria-labelledby="deleteConformationLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" id="deleteConformationLabel">
                                <div class="modal-header">
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </div>
                                    <h5 class="modal-title" id="exampleModalLabel">ต้องการจะลบหมวดหมู่?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="">หากคุณลบหมวดหมู่ย่อย ข้อมูลจะหายไปถาวรและไม่สามารถกู้คืนได้ คุณแน่ใจหรือไม่ว่าต้องการดำเนินการต่อ?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-danger" data-remove="task">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row scrumboard" id="cancel-row">
                        <div class="col-lg-12 layout-spacing">

                            <div class="task-list-section">

                                <!-- <div data-section="s-new" class="task-list-container" data-connect="sorting">
                                    <div class="connect-sorting">
                                        <div class="task-container-header">
                                            <h6 class="s-heading" data-listTitle="In Progress">In Progress</h6>
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                                        <circle cx="12" cy="12" r="1"></circle>
                                                        <circle cx="19" cy="12" r="1"></circle>
                                                        <circle cx="5" cy="12" r="1"></circle>
                                                    </svg>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right left" aria-labelledby="dropdownMenuLink-1">
                                                    <a class="dropdown-item list-edit" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item list-delete" href="javascript:void(0);">Delete</a>
                                                    <a class="dropdown-item list-clear-all" href="javascript:void(0);">Clear All</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="connect-sorting-content" data-sortable="true">

                                            <div data-draggable="true" class="card img-task">
                                                <div class="card-body">

                                                    <div class="task-content">
                                                        <img src="../src/assets/img/taskboard.jpg" class="img-fluid" alt="scrumboard">
                                                    </div>

                                                    <div class="task-header">
                                                        <div class="">
                                                            <h4 class="" data-taskTitle="Creating a new Portfolio on Dribble">Creating a new Portfolio on Dribble</h4>
                                                        </div>
                                                    </div>

                                                    <div class="task-body">

                                                        <div class="task-bottom">
                                                            <div class="tb-section-1">
                                                                <span data-taskDate="28 Apr"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                                    </svg> 28 Apr </span>
                                                            </div>
                                                            <div class="tb-section-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 s-task-edit">
                                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                                </svg>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 s-task-delete">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                                </svg>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div data-draggable="true" class="card simple-title-task">
                                                <div class="card-body">

                                                    <div class="task-header">
                                                        <div class="">
                                                            <h4 class="" data-taskTitle="Singapore Team Meet">Singapore Team Meet</h4>
                                                        </div>
                                                        <div class="">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 s-task-edit">
                                                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 s-task-delete">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                        </div>

                                        <div class="add-s-task">
                                            <a class="addTask"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                                </svg> Add Task</a>
                                        </div>

                                    </div>
                                </div> -->

                                <!-- <div data-section="s-in-progress" class="task-list-container" data-connect="sorting">
                                    <div class="connect-sorting">
                                        <div class="task-container-header">
                                            <h6 class="s-heading" data-listTitle="Complete">Complete</h6>
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                                        <circle cx="12" cy="12" r="1"></circle>
                                                        <circle cx="19" cy="12" r="1"></circle>
                                                        <circle cx="5" cy="12" r="1"></circle>
                                                    </svg>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right left" aria-labelledby="dropdownMenuLink-2">
                                                    <a class="dropdown-item list-edit" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item list-delete" href="javascript:void(0);">Delete</a>
                                                    <a class="dropdown-item list-clear-all" href="javascript:void(0);">Clear All</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="connect-sorting-content" data-sortable="true">
                                            <div data-draggable="true" class="card simple-title-task ui-sortable-handle">
                                                <div class="card-body">

                                                    <div class="task-header">
                                                        <div class="">
                                                            <h4 class="" data-tasktitle="Dinner with Kelly Young">Dinner with Kelly Young</h4>
                                                        </div>
                                                        <div class="">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 s-task-edit">
                                                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 s-task-delete">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div data-draggable="true" class="card task-text-progress">
                                                <div class="card-body">

                                                    <div class="task-header">

                                                        <div class="">
                                                            <h4 class="" data-taskTitle="Launch New SEO Wordpress Theme ">Launch New SEO Wordpress Theme </h4>
                                                        </div>

                                                        <div class="">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 s-task-edit">
                                                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                            </svg>
                                                        </div>

                                                    </div>

                                                    <div class="task-body">

                                                        <div class="task-content">
                                                            <p class="" data-taskText="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

                                                            <div class="">
                                                                <div class="progress br-30">
                                                                    <div class="progress-bar bg-success" role="progressbar" data-progressState="20" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>

                                                                <p class="progress-count">20%</p>

                                                            </div>
                                                        </div>

                                                        <div class="task-bottom">
                                                            <div class="tb-section-1">
                                                                <span data-taskDate="28 Apr"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                                    </svg> 28 Apr </span>
                                                            </div>
                                                            <div class="tb-section-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 s-task-delete">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                                </svg>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                        <div class="add-s-task">
                                            <a class="addTask"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                                </svg> Add Task</a>
                                        </div>

                                    </div>
                                </div> -->

                                <!-- <div data-section="s-approved" class="task-list-container" data-connect="sorting">

                                    <div class="connect-sorting">
                                        <div class="task-container-header">
                                            <h6 class="s-heading" data-listTitle="New">New</h6>
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                                        <circle cx="12" cy="12" r="1"></circle>
                                                        <circle cx="19" cy="12" r="1"></circle>
                                                        <circle cx="5" cy="12" r="1"></circle>
                                                    </svg>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right left" aria-labelledby="dropdownMenuLink-3">
                                                    <a class="dropdown-item list-edit" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item list-delete" href="javascript:void(0);">Delete</a>
                                                    <a class="dropdown-item list-clear-all" href="javascript:void(0);">Clear All</a>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="connect-sorting-content" data-sortable="true">

                                        </div>

                                        <div class="add-s-task">
                                            <a class="addTask"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                                </svg> Add Task</a>
                                        </div>

                                    </div>
                                </div> -->

                            </div>
                        </div>
                    </div>

                </div>

            </div>

           <!--  BEGIN FOOTER  -->
           <?php include "partials/footer.php"?>
            <!--  END FOOTER  -->
              
        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="../src/plugins/src/global/vendors.min.js"></script>
    <script src="../src/plugins/src/jquery-ui/jquery-ui.min.js"></script>
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../src/plugins/src/waves/waves.min.js"></script>
    <script src="../layouts/modern-light-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="../src/assets/js/apps/scrumboard.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
</body>

</html>