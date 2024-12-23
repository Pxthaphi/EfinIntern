<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $last_project_session = $_SESSION['last_project_id'] ?? '';
    if ($last_project_session != '') {
        unset($_SESSION['cover_image']);
        unset($_SESSION['project_title']);
        unset($_SESSION['project_detail']);
        unset($_SESSION['project_owner']);
        unset($_SESSION['slideshow_image']);
        unset($_SESSION['category']);
        unset($_SESSION['category_id']);
        unset($_SESSION['subcategory']);
        unset($_SESSION['subcategory_id']);
        unset($_SESSION['project_time']);
        unset($_SESSION['last_project']);
    }
}
