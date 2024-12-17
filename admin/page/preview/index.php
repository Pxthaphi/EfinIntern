<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//---- รับชื่อไฟล์และประเภทมาจาก Path ----//
$file_name = $_GET['file'] ?? '';
$type = $_GET['type'] ?? '';

//---- เชื่อมต่อ Database ----//
include '../../../db/connection.php';

//---- ส่งชื่อไฟล์สำหรับลบเมื่อปิดหน้าเว็บ ----//
$_SESSION['file_to_delete'] = $file_name;

//---- รับข้อมูลมาจาก JSON ที่เก็บใน Folder Data ----//
if ($file_name && file_exists('data/' . $file_name)) {
	$projectData = json_decode(file_get_contents('data/' . $file_name), true);

	//---- รับข้อมูลมาเก็บในแต่ละตัวแปร ----//
	$project_id = $projectData['project_id'] ?? '';
	$projectCover = $projectData['project_cover'] ?? '';
	$projectName = $projectData['project_title'] ?? 'ไม่มีชื่อโปรเจค';
	$projectDescription = $projectData['project_description'] ?? 'ไม่มีรายละเอียด';
	$projectOwner = $projectData['project_owner'] ?? 'ไม่มีเจ้าของโปรเจค';
	$projectSlideshow = $projectData['project_slideshow'] ?? '';
	$ProjectCategory = $projectData['Project_category'] ?? '';
	$ProjectSubcategory = $projectData['Project_subcategory'] ?? '';

	//---- แปลงข้อมูล project_owner จาก JSON string เป็น Array ----//
	$projectOwnerArray = json_decode($projectOwner, true);

	//---- ดึงค่า User_ID จาก project_owner ----//
	$userID = $projectOwnerArray[0]['User_ID'] ?? '';

	//---------- ขั้นตอนการดึงข้อมูลจากตาราง User เพื่อเอามาแสดงที่ Project Owner ------------ //
	$query = "SELECT user.User_ID, user.User_Image, user.User_Prefix, user.User_Firstname, user.User_Lastname, position.Position_Name
            FROM `user` 
            JOIN position ON user.User_PositionID = position.Position_ID 
            WHERE User_ID = ?";

	$stmt = mysqli_prepare($conn, $query);
	if ($stmt === false) {
		die("Query preparation failed: " . mysqli_error($conn));
	}

	mysqli_stmt_bind_param($stmt, "s", $userID);

	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	// Fetch data
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$User_ID = $row['User_ID'];
			$User_Image = "../" . $row['User_Image'];
			$userPrefix = $row['User_Prefix'];
			$userFirstName = $row['User_Firstname'];
			$userLastName = $row['User_Lastname'];
			$userPosition = $row['Position_Name'];
		}
	} else {
		echo "No results found.";
	}
	mysqli_stmt_close($stmt);

	//---------- สิ้นสุดขั้นตอนการดึงข้อมูลจากตาราง User เพื่อเอามาแสดงที่ Project Owner ------------ //

	//---- ถ้าเป็นหน้าเพิ่มข้อมูล เมื่อกดย้อนกลับให้ส่งข้อมูลไปแสดงเหมือนเดิม ----//
	if ($type == "add") {
		$redirect = "../app-blog-create.php";
		$_SESSION['cover_image'] = $projectCover;
		$_SESSION['project_title'] = $projectName;
		$_SESSION['project_detail'] = $projectDescription;
		$_SESSION['project_owner'] = $projectOwner;
		$_SESSION['slideshow_image'] = $projectSlideshow;
	} else if ($type == "edit") {
		$redirect = "../app-blog-edit.php?project_id=$project_id";
	} else {
		$redirect = "../app-blog-list.php";
	}

	//---- เพิ่ม ../ajax/ ข้างหน้า Path เดิม ----//
	$projectCover = "../ajax/" . $projectCover;

	//---- ดึงข้อมูล image จาก tag html ที่ได้จาก quill editor ----//
	$projectDescription = preg_replace(
		'/(<img\s+[^>]*src=["\'])([^"\']+)/i',
		'$1../$2',
		$projectDescription
	);

	//---- เพิ่ม , เมื่อมีรูปหลายรูปที่เอาไปแสดงที่ Slideshow ----//
	$imagePaths = explode(',', $projectSlideshow);

	//---- แก้ไข Path รูปที่เอาไปแสดงที่ Slideshow ----//
	$imagePaths = array_map(function ($path) {
		return "../ajax/" . $path;
	}, $imagePaths);

	//---- แปลง Tag html จาก <ol> เป็น <ul> ----//
	$projectDescription = preg_replace('/<ol([^>]*)>/', '<ul$1>', $projectDescription);
	$projectDescription = str_replace('</ol>', '</ul>', $projectDescription);

	// //---------- ขั้นตอนแปลงและดึงข้อมูล Category และ Subcategory ------------ //
	// $categoryData = json_decode($ProjectCategory, true);
	// $subcategoryData = json_decode($ProjectSubcategory, true);

	$categoryId = $ProjectCategory;
	$subcategoryId = $ProjectSubcategory;

	// //---- ดึงเอาเฉพาะ ID ของ Category และ Subcategory ----//
	// $categoryId = isset($categoryData[0]['id']) ? $categoryData[0]['id'] : null;
	// $subcategoryId = isset($subcategoryData[0]['id']) ? $subcategoryData[0]['id'] : null;

	if ($categoryId && $subcategoryId) {
		$query_category = "SELECT category.Category_Name, subcategory.Subcategory_Name 
            FROM category 
            JOIN subcategory ON category.Category_ID = subcategory.Category_ID 
            WHERE category.Category_ID = ? AND subcategory.Subcategory_ID = ?";

		$stmt = mysqli_prepare($conn, $query_category);
		if ($stmt === false) {
			die("Query preparation failed: " . mysqli_error($conn));
		}

		mysqli_stmt_bind_param($stmt, "ii", $categoryId, $subcategoryId);

		mysqli_stmt_execute($stmt);

		$result_category = mysqli_stmt_get_result($stmt);

		if (mysqli_num_rows($result_category) > 0) {
			while ($row_category = mysqli_fetch_assoc($result_category)) {
				$Category = $row_category['Category_Name'];
				$Subcategory = $row_category['Subcategory_Name'];
			}
		} else {
			echo "No results found for category/subcategory.";
		}
		mysqli_stmt_close($stmt);
	}

	mysqli_close($conn);

	//---------- สิ้นสุดขั้นตอนแปลงและดึงข้อมูล Category และ Subcategory ------------ //

} else {
	echo "ไม่พบข้อมูลโปรเจค.";
}
?>


<!doctype html>
<html lang="en-US">

<head>

	<title>Preview - Project Detail</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="HandheldFriendly" content="true">
	<meta name="author" content="bslthemes" />

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.googleapis.com" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Epilogue%3Aital%2Cwght%400%2C100%3B0%2C200%3B0%2C300%3B0%2C400%3B0%2C500%3B0%2C600%3B0%2C700%3B0%2C800%3B0%2C900%3B1%2C100%3B1%2C200%3B1%2C300%3B1%2C400%3B1%2C500%3B1%2C600%3B1%2C700%3B1%2C800%3B1%2C900&#038;display=swap" type="text/css" media="all" />
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link rel="stylesheet" href="../../../../template/assets/fonts/fonts/addfont.css">

	<!-- CSS STYLES -->
	<link rel="stylesheet" href="../../../assets/css/vendors/bootstrap.css" type="text/css" media="all" />
	<link rel="stylesheet" href="../../../assets/fonts/font-awesome/css/font-awesome.css" type="text/css" media="all" />
	<link rel="stylesheet" href="../../../assets/css/vendors/magnific-popup.css" type="text/css" media="all" />
	<link rel="stylesheet" href="../../../assets/css/vendors/splitting.css" type="text/css" media="all" />
	<link rel="stylesheet" href="../../../assets/css/vendors/swiper.css" type="text/css" media="all" />
	<link rel="stylesheet" href="../../../assets/css/vendors/animate.css" type="text/css" media="all" />
	<link rel="stylesheet" href="../../../assets/css/vendors/jquery.pagepiling.css" type="text/css" media="all" />
	<link rel="stylesheet" href="../../../assets/css/style.css" type="text/css" media="all" />

	<link rel="stylesheet" href="../../../../template/assets/css/nav-footer.css" type="text/css" media="all">
	<link rel="stylesheet" href="../../../../template/assets/css/customs-style.css">
	<link rel="stylesheet" href="../../../../template/assets/css/othercustom.css">
	<!-- <link rel="stylesheet" href="../../../../template/assets/css/custom.css"> -->

	<!-- Favicon -->
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<style>
</style>

<body>

	<!-- Page -->
	<div class="onovo-page footer--fixed">

		<!-- Preloader -->
		<!-- <div class="preloader">
			<div class="preloader__spinner">
				<span class="preloader__double-bounce"></span>
				<span class="preloader__double-bounce preloader__double-bounce--delay"></span>
			</div>
		</div> -->

		<!-- Header -->
		<header class="header--white header-nav">
			<div class="header--builder">
				<div class="container">
					<div class="d-flex flex-row justify-content-between align-items-center ">
						<div class="h-100 d-flex flex-row align-items-center">
							<a href="#">
								<img src="../../../assets/images/logo/efin_internspace.webp" class="efinLogo" alt="Efin_logo" width="400px">
							</a>
							<!-- <span class="divider-logo"></span>
							<a href="projects.html" >
								<img src="./assets/images/logo/intern-logo.webp" class="internLogo d-block" alt="Efin_intern_logo" width="185px"> 
							</a> -->
						</div>
						<div class="d-flex flex-row align-items-center p-0 h-100">
							<a href="#" class="nav-text fw-bold fs-13-md" style="color: black !important;"> หน้าแรก</a>
							<a href="#" class="nav-text fw-bold text-white active-button fs-13-md" id="navbtn"> สมัครฝึกงาน</a>
						</div>
					</div>
				</div>
			</div>
		</header>
		<!-- Wrapper -->
		<div class="wrapper">
			<section class="banner-wrapper">
				<div class="container-fluid">
					<div class="row gx-0 banner-fix">
						<div class="wapprer-banner">
							<div class="col-12 banner-section">
								<a href="<?= $redirect ?>" class="button-backward">
									<div class="arrowfix">
										<img src="../../../assets/images/port_img/Vector.svg" alt="ย้อนกลับ">
									</div>
									ย้อนกลับ
								</a>
								<h1 class="text-white"><?= $projectName ?></h1>
								<h5 class="text-white">#<?= $Subcategory?></h5>
							</div>
						</div>
					</div>
				</div>
				<!-- Gradient Background -->
				<div class="gradient-bg ">
					<svg
						viewBox="0 0 100vw 100vw"
						xmlns='http://www.w3.org/2000/svg'
						class="noiseBg">
						<filter id='noiseFilterBg'>
							<feTurbulence
								type='fractalNoise'
								baseFrequency='0.6'
								stitchTiles='stitch' />
						</filter>
						<rect
							width='100%'
							height='100%'
							preserveAspectRatio="xMidYMid meet"
							filter='url(#noiseFilterBg)' />
					</svg>
					<svg xmlns="http://www.w3.org/2000/svg" class="svgBlur">
						<defs>
							<filter id="goo">
								<feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
								<feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -8" result="goo" />
								<feBlend in="SourceGraphic" in2="goo" />
							</filter>
						</defs>
					</svg>
					<!-- <spline-viewer url="https://prod.spline.design/ao5aABZZLtXoejGZ/scene.splinecode"></spline-viewer> -->
					<!-- <spline-viewer url="https://prod.spline.design/svAzQCqB3ormYXcw/scene.splinecode"></spline-viewer> -->
					<!-- <spline-viewer url="https://prod.spline.design/Oyv5m9tcTMbqJvo7/scene.splinecode"></spline-viewer> -->
					<div class="gradients-container">
						<!-- <div class="g1"></div>
					  <div class="g2"></div>
					  <div class="g3"></div>
					  <div class="g4"></div>
					  <div class="g5"></div>
					  <div class="interactive"></div> -->
					</div>
				</div>
			</section>

			<!-- Section Profile and ดูผลงานทั้งหมด -->
			<section class="onovo-section">
				<div class="container">
					<div class="wrapper-navprofile">
						<div class="d-flex mini-profile">
							<div class="miximg-name d-flex flex-row">
								<img src="<?= $User_Image ?>" alt=""
									class="rounded-circle"
									style="width: 50px; height: 50px;">
								<div class="navigation-profile">
									<h6><?= htmlspecialchars($userPrefix . '' . $userFirstName . ' ' . $userLastName) ?></h6>
									<p><?= htmlspecialchars($userPosition) ?></p>
									<a href="#" class="click-watchall d-flex align-items-center">
										<h5 class="fw-light">ดูผลงานทั้งหมด</h5>
										<div class="arrowfix1">
											<img src="../../../assets/images/port_img/arrow_green.png" alt="">
										</div>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Section Project-Detail รายละเอียดผลงาน -->
			<section class="onovo-section">
				<div class="container" id="containerfix01">
					<div class="row justify-content-center project-details">
						<div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 fix-procard "><img src="<?= $projectCover ?>" alt=""></div>

						<!-- Text content -->
						<!-- -------------------------------- -->
						<div class="col-lg-8 col-sm-10 col-12  text-describ">
							<?= $projectDescription ?>
							<!-- --------- -->
						</div>
						<!-- -------------------------------- -->
					</div>

					<!-- slider ที่สไลด์ได้ๆ -->
					<!-- -------------------------------- -->
					<?php
					if ($projectSlideshow != "") { ?>
						<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
							<div class="carousel-indicators">
								<?php
								// สร้าง indicators สำหรับแต่ละภาพใน slideshow
								foreach ($imagePaths as $index => $imagePath) {
									$activeClass = $index === 0 ? 'active' : '';
									echo "<button type='button' id='bbb' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='$index' class='$activeClass' aria-current='true' aria-label='Slide " . ($index + 1) . "'></button>";
								}
								?>
							</div>
							<div class="carousel-inner">
								<?php
								// สร้าง carousel items สำหรับแต่ละภาพใน slideshow
								foreach ($imagePaths as $index => $imagePath) {
									$activeClass = $index === 0 ? 'active' : '';
									echo "
										<div class='carousel-item $activeClass'>
											<div class='warpper-carousel gx-0 row d-flex justify-content-center'>
											<img src='$imagePath' class='' alt='Image " . ($index + 1) . "' loading='lazy'>
											</div>
										</div>
										";
								}
								?>
							</div>
							<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>
							<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Next</span>
							</button>
						</div>

					<?php
					}
					?>
					<!-- -------------------------------- -->

			</section>

			<!---------card-more------->
			<!-- <section class="onovo-section">
				<div class="container">
					<div class="row">
						<div class="wrapper-navprofile2 d-flex align-items-center justify-content-between">
							<div class="miximg-name d-flex align-items-center">
								<div class="navigation-profile">
									<h4>ผลงานที่เกี่ยวข้อง</h4>
								</div>
							</div>
							<a href="projects.html" class="click-watchall d-flex align-items-center">
								<h5 class="mb-0">ดูผลงานอื่นๆ</h5>
								<div class="arrowfix1">
									<img src="../../../../template/assets/images/port_img/arrow_green.png" alt="">
								</div>
							</a>
						</div>
						<div class="col-lg-4 col-md-6 col-12 cardport">
							<a href="#"><img src="../../../../template/assets/images/port_img/imgdetail3.webp" alt="" class="img-port">
								<h6>Design Web Portfolio</h6>
							</a>
							<div class="describe-card d-flex justify-content-between align-items-center">
								<p>Web Design</p>
								<a href="Profile.html">
									<div class="tag-name d-flex">
										<img src="../../../../template/assets/images/port_img/personal_img1.webp" alt="" class="img-profile">
										<p>นางพงไพร แดนไกล</p>
									</div>
								</a>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12 cardport">
							<a href="#"><img src="../../../../template/assets/images/port_img/imgdetail4.webp" alt="" class="img-port">
								<h6>Design Web Portfolio</h6>
							</a>
							<div class="describe-card d-flex justify-content-between align-items-center">
								<p>Web Design</p>
								<a href="Profile.html">
									<div class="tag-name d-flex">
										<img src="../../../../template/assets/images/port_img/personal_img2.webp" alt="" class="img-profile">
										<p>นางสมร อำพรพุทธ</p>
									</div>
								</a>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12 cardport" id="hidecard">
							<a href="#"><img src="../../../../template/assets/images/port_img/imgdetail5.webp" alt="" class="img-port">
								<h6>Design Web Portfolio</h6>
							</a>
							<div class="describe-card d-flex justify-content-between align-items-center">
								<p>Web Design</p>
								<a href="Profile.html">
									<div class="tag-name d-flex">
										<img src="../../../../template/assets/images/port_img/personal_img3.webp" alt="" class="img-profile">
										<p>นายชาร์ม จานส้อม</p>
									</div>
								</a>
							</div>
						</div>
					</div>
			</section> -->

		</div>
		<!-- Footer -->
		<footer class="border-footer mt-5">
			<div class="container footer-container">
				<div class="row mx-auto footer-content">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 p-0">
						<div class="onovo-text onovo-text-white">
							<img src="../../../assets/images/logo/efin_internspace_footer.webp" class="efinLogo" alt="Efin_logo" width="400px">

							<p class="footer-font">เราจะไม่เพียงแต่นั่งรอโอกาส <br class="d-md-block d-none">
								แต่เรามุ่งมั่นจะสร้างโอกาส <br class="d-md-none d-block">
								ที่ทำให้ <br class="d-md-block d-none">
								เรา สังคมของเรา และทุกคนที่เราเกี่ยวข้องด้วย ดีขึ้น</p>
						</div>

					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 p-0">
						<div class="d-flex flex-column justify-content-end align-items-start align-items-md-end h-100">
							<a href="#" class="fs-16 fw-bold text-white m-md-0 active-button" id="btn-footer"> สมัครฝึกงาน</a>
							<!-- this part -->
							<div class="d-flex flex-row justify-content-end footer-gap align-items-end h-100">
								<a href="#"> <img src="../../../assets/images/icon/facebook.webp" alt="Facebook_contact" class="contact-logo"> </a>
								<a href="#"> <img src="../../../assets/images/icon/X.webp" alt="X_contact" class="contact-logo"> </a>
								<a href="#"> <img src="../../../assets/images/icon/YouTube.webp" alt="YouTube_contact" class="contact-logo"> </a>
							</div>
						</div>

					</div>
				</div>

				<div class="row mx-auto text-center">
					<!-- Copyright -->
					<div class="copyright copyright-text">
						Copyright ⓒ 2024 <mark class="p-0 highlight-hero-text"> efinanceThai.com </mark> All rights reserved
					</div>

				</div>

			</div>
		</footer>
	</div>
	<script>
		window.onload = function() {
			var shadowRoot = document.querySelector('spline-viewer').shadowRoot;
			shadowRoot.querySelector('#logo').remove();
		}
	</script>

	<script src="../../../assets/js/jquery.min.js"></script>
	<script src="../../../assets/js/bootstrap.js"></script>
	<script src="../../../assets/js/swiper.js"></script>
	<script src="../../../assets/js/splitting.js"></script>
	<script src="../../../assets/js/scroll-out.js"></script>
	<script src="../../../assets/js/jquery.pagepiling.js"></script>
	<script src="../../../assets/js/jquery.easy_number_animate.js"></script>
	<script src="../../../assets/js/magnific-popup.js"></script>
	<script src="../../../assets/js/imagesloaded.pkgd.js"></script>
	<script src="../../../assets/js/isotope.pkgd.js"></script>
	<script src="../../../assets/js/common.js"></script>
	<script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.3/build/spline-viewer.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const interBubble = document.querySelector('.interactive');
			let curX = 0;
			let curY = 0;
			let tgX = 0;
			let tgY = 0;

			const move = () => {
				curX += (tgX - curX) / 20;
				curY += (tgY - curY) / 20;
				interBubble.style.transform = `translate(${Math.round(curX)}px, ${Math.round(curY)}px)`;
				requestAnimationFrame(move);
			};

			window.addEventListener('mousemove', event => {
				tgX = event.clientX;
				tgY = event.clientY;
			});

			move();
		});
	</script>

	<!-- ลบไฟล์ preview/data (json) หลังจากที่ปิดหน้าเว็บ -->
	<script>
		window.addEventListener('unload', () => {
			navigator.sendBeacon('delete_file.php');
		});
	</script>

</body>

</html>