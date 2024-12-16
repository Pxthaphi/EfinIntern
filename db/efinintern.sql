-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 16, 2024 at 03:58 PM
-- Server version: 5.7.24
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `efinintern`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Category_ID` int(7) NOT NULL COMMENT 'รหัสหมวดหมู่',
  `Category_Name` varchar(50) NOT NULL COMMENT 'ชื่อหมวดหมู่'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางข้อมูลหมวดหมู่หลัก';

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Category_ID`, `Category_Name`) VALUES
(1, 'Web Design'),
(2, 'Graphic Design'),
(3, 'Video Photograph'),
(4, 'UX/UI');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `Department_ID` int(5) NOT NULL COMMENT 'รหัสแผนก',
  `Department_Name` varchar(50) NOT NULL COMMENT 'ชื่อแผนก'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='แผนก';

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`Department_ID`, `Department_Name`) VALUES
(1, 'MKT2/Brand & Marketing Communication');

-- --------------------------------------------------------

--
-- Table structure for table `intern`
--

CREATE TABLE `intern` (
  `Intern_ID` int(7) NOT NULL COMMENT 'รหัสฝึกงาน',
  `User_ID` varchar(20) NOT NULL COMMENT 'รหัสผู้ใช้',
  `Intern_StartDate` date NOT NULL COMMENT 'วันที่เริ่มฝึกงาน',
  `Intern_EndDate` date NOT NULL COMMENT 'วันที่สิ้นสุดฝึกงาน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางข้อมูลนักศึกษาฝึกงาน';

--
-- Dumping data for table `intern`
--

INSERT INTO `intern` (`Intern_ID`, `User_ID`, `Intern_StartDate`, `Intern_EndDate`) VALUES
(1, 'Intern_Efin001', '2024-11-04', '2025-02-21');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `Position_ID` int(5) NOT NULL,
  `Position_Name` varchar(50) NOT NULL,
  `Department_ID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`Position_ID`, `Position_Name`, `Department_ID`) VALUES
(1, 'Web Design', 1),
(2, 'Graphic Design', 1);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `Project_ID` int(10) NOT NULL,
  `Project_Title` varchar(255) NOT NULL,
  `Project_Detail` longtext NOT NULL,
  `Project_CategoryID` int(7) DEFAULT NULL COMMENT 'รหัสหมวดหมู่หลัก',
  `Project_SubcategoryID` int(7) DEFAULT NULL COMMENT 'รหัสหมวดหมู่ย่อย',
  `Project_UserID` varchar(20) DEFAULT NULL COMMENT 'รหัสผู้ใช้',
  `Project_Create` datetime DEFAULT NULL COMMENT 'เวลาที่สร้าง',
  `Project_Update` datetime DEFAULT NULL COMMENT 'เวลาที่แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`Project_ID`, `Project_Title`, `Project_Detail`, `Project_CategoryID`, `Project_SubcategoryID`, `Project_UserID`, `Project_Create`, `Project_Update`) VALUES
(23, 'Design Web Portfolio', '<h5><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Overview &amp; Features</strong></h5><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Grownest is a user-friendly stock investment app designed to empower investors with the tools they need to succeed. Our app offers a seamless experience with features that keep you informed and in control of your investments. Key features include:</span></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Real-time portfolio updates</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;to keep track of your investments as they change.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Real-time market data</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;for up-to-the-minute insights.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Discover stocks and get insights</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;to make informed investment decisions.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Convert stocks</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;effortlessly within the app.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Portfolio tracking</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;to monitor your investment performance.</span></li></ol><p><br></p><p><img src=\"../..//assets//images/project/detail/675fb88246d6e-image.png.webp\"></p><p><br></p><h5><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Objectives</strong></h5><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Our objectives with Grownest are clear:</span></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Lack of real-time updates on their investments.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Difficulty accessing timely market data.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Limited tools for discovering and researching stocks.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Complicated processes for converting stocks.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Insufficient means to track and analyze portfolio performance.</span></li></ol><p><br></p><p><img src=\"../..//assets//images/project/detail/675fb89359ddc-image.png.webp\"></p><p><br></p><h5><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">How We Solve the Problem</strong></h5><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Grownest addresses these challenges by integrating advanced features and a user-friendly interface:</span></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Real-time portfolio updates</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;ensure that users always have the latest information on their investments.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Real-time market data</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;keeps users informed about market trends and movements.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Discover stocks and get insights</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;feature helps users find new investment opportunities and make educated decisions.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Convert stocks</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;feature simplifies the process of managing and adjusting investments.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Portfolio tracking</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;provides detailed analytics, helping users to understand and improve their investment performance.</span></li></ol><p><br></p><p><img src=\"../..//assets//images/project/detail/675fb8e8bb99e-ปก  1920 x 720.png.webp\"></p>', NULL, NULL, 'Intern_Efin001', NULL, NULL),
(24, 'UI Efin mobile', '<h5><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Overview &amp; Features</strong></h5><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Grownest is a user-friendly stock investment app designed to empower investors with the tools they need to succeed. Our app offers a seamless experience with features that keep you informed and in control of your investments. Key features include:</span></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Real-time portfolio updates</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;to keep track of your investments as they change.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Real-time market data</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;for up-to-the-minute insights.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Discover stocks and get insights</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;to make informed investment decisions.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Convert stocks</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;effortlessly within the app.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">Portfolio tracking</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(27, 37, 46);\">&nbsp;to monitor your investment performance.</span></li></ol><p><br></p><p>// เมื่อมีการลบ tag tagifyCategory.on(\'remove\', (e) =&gt; { console.log(\'Tag removed:\', e.detail); // เคลียร์ค่าใน input ของ category_iddocument.querySelector(\'input[name=category_id]\').value = \'\'; console.log(\'Category ID input cleared\'); });</p><p><br></p>', NULL, NULL, 'Intern_Efin001', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `projectimages`
--

CREATE TABLE `projectimages` (
  `ProjectImages_ID` int(7) NOT NULL COMMENT 'รหัสรูปภาพ',
  `Project_ID` int(7) NOT NULL COMMENT 'รหัสโปรเจค',
  `Cover_Path` text NOT NULL COMMENT 'รูปที่แสดงเป็น Cover',
  `Slideshow_Path` text NOT NULL COMMENT 'รูปที่แสดงแบบ Slide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projectimages`
--

INSERT INTO `projectimages` (`ProjectImages_ID`, `Project_ID`, `Cover_Path`, `Slideshow_Path`) VALUES
(6, 23, '../../assets/images/project/cover/448208999_973270037922170_3513679033703694988_n.webp', '../../assets/images/project/slideshow/426030128_7837000446324034_4741929722710744761_n.webp,../../assets/images/project/slideshow/414441060_812682894188523_7097524946472708293_n.webp,../../assets/images/project/slideshow/356077623_699001258890021_7645202885823199080_n.webp,../../assets/images/project/slideshow/414445460_812681660855313_2870035018526342950_n (1).webp,<br />\r\n<b>Fatal error</b>:  Allowed memory size of 134217728 bytes exhausted (tried to allocate 76038144 bytes) in <b>C:\\MAMP\\htdocs\\EfinIntern\\template\\admin\\page\\ajax\\dropzone_upload.php</b> on line <b>13</b><br />'),
(7, 24, '../../assets/images/project/cover/efinmobile.webp', '../../assets/images/project/slideshow/photo-1515879218367-8466d910aaa4.webp,../../assets/images/project/slideshow/448208999_973270037922170_3513679033703694988_n.webp,../../assets/images/project/slideshow/405986998_795086952614784_4046965968190899370_n.webp,../../assets/images/project/slideshow/411360167_812683804188432_2330236379856072380_n.webp,../../assets/images/project/slideshow/sunflower-with-sun-setting-it_889056-257579.webp,../../assets/images/project/slideshow/ปก  1920 x 720.webp,../../assets/images/project/slideshow/13442650_1339392839418193_7290210081275226584_o.webp,<br />\r\n<b>Fatal error</b>:  Allowed memory size of 134217728 bytes exhausted (tried to allocate 76038144 bytes) in <b>C:\\MAMP\\htdocs\\EfinIntern\\template\\admin\\page\\ajax\\dropzone_upload.php</b> on line <b>13</b><br />,../../assets/images/project/slideshow/356077623_699001258890021_7645202885823199080_n.webp,Failed to compress image below 300KB for: 446794599_8364521276905279_1054042763012721275_n.jpg\r\nNo files uploaded.');

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `Subcategory_ID` int(7) NOT NULL COMMENT 'รหัสหมวดหมู่ย่อย',
  `Subcategory_Name` varchar(50) NOT NULL COMMENT 'ชื่อหมวดหมู่ย่อย',
  `Category_ID` int(7) NOT NULL COMMENT 'รหัสหมวดหมู่หลัก'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางข้อมูลหมวดหมู่ย่อย';

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`Subcategory_ID`, `Subcategory_Name`, `Category_ID`) VALUES
(1, 'Web IR', 1),
(2, 'Web Product', 1),
(3, 'UI Design', 4);

-- --------------------------------------------------------

--
-- Table structure for table `university`
--

CREATE TABLE `university` (
  `University_ID` int(11) NOT NULL COMMENT 'รหัสมหาวิทยาลัย',
  `University_Name` varchar(100) DEFAULT NULL COMMENT 'ชื่อมหาวิทยาลัย',
  `University_thCode` varchar(20) DEFAULT NULL COMMENT 'ตัวย่อ มหาวิทยาลัย (ไทย)',
  `University_enCode` varchar(10) DEFAULT NULL COMMENT 'ตัวย่อ มหาวิทยาลัย (อังกฤษ)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `university`
--

INSERT INTO `university` (`University_ID`, `University_Name`, `University_thCode`, `University_enCode`) VALUES
(1, 'มหาวิทยาลัยกาฬสินธุ์', 'มกส.', 'KSU'),
(2, 'มหาวิทยาลัยนครพนม', 'มนพ.', 'NPU'),
(3, 'มหาวิทยาลัยนราธิวาสราชนครินทร์', 'มนร.', 'PNU'),
(4, 'มหาวิทยาลัยนเรศวร', 'มน.', 'NU'),
(5, 'มหาวิทยาลัยมหาสารคาม', 'มมส.', 'MSU'),
(6, 'มหาวิทยาลัยรามคำแหง', 'มร.', 'RU'),
(7, 'มหาวิทยาลัยสุโขทัยธรรมาธิราช', 'มสธ.', 'STOU'),
(8, 'มหาวิทยาลัยอุบลราชธานี', 'ม.อบ.', 'UBU'),
(9, 'สถาบันบัณฑิตพัฒนบริหารศาสตร์', 'สพบ.', 'NIDA'),
(10, 'สถาบันเทคโนโลยีปทุมวัน', 'สปท.', 'PIT'),
(11, 'มหาวิทยาลัยราชภัฏกาญจนบุรี', 'มร.กจ.', 'KRU'),
(12, 'มหาวิทยาลัยราชภัฏกำแพงเพชร', 'มรภ.กพ.', 'KPRU'),
(13, 'มหาวิทยาลัยราชภัฏจันทรเกษม', 'มจษ.', 'CRU'),
(14, 'มหาวิทยาลัยราชภัฏชัยภูมิ', 'มชย.', 'CPRU'),
(15, 'มหาวิทยาลัยราชภัฏเชียงราย', 'มร.ชร.', 'CRRU'),
(16, 'มหาวิทยาลัยราชภัฏเชียงใหม่', 'มร.ชม.', 'CMRU'),
(17, 'มหาวิทยาลัยราชภัฏเทพสตรี', 'มรท.', 'TRU'),
(18, 'มหาวิทยาลัยราชภัฏธนบุรี', 'มรธ.', 'DRU'),
(19, 'มหาวิทยาลัยราชภัฏนครปฐม', 'มรน.', 'NPRU'),
(20, 'มหาวิทยาลัยราชภัฏนครราชสีมา', 'มร.นม.', 'NRRU'),
(21, 'มหาวิทยาลัยราชภัฏนครศรีธรรมราช', 'มร.นศ.', 'NSTRU'),
(22, 'มหาวิทยาลัยราชภัฏนครสวรรค์', 'มร.นว.', 'NSRU'),
(23, 'มหาวิทยาลัยราชภัฏบ้านสมเด็จเจ้าพระยา', 'มบส.', 'BSRU'),
(24, 'มหาวิทยาลัยราชภัฏบุรีรัมย์', 'มรภ.บร.', 'BRU'),
(25, 'มหาวิทยาลัยราชภัฏพระนคร', 'มรภ.พระนคร', 'PNRU'),
(26, 'มหาวิทยาลัยราชภัฏพระนครศรีอยุธยา', 'มร.อย.', 'ARU'),
(27, 'มหาวิทยาลัยราชภัฏพิบูลสงคราม', 'มร.พส.', 'PSRU'),
(28, 'มหาวิทยาลัยราชภัฏเพชรบุรี', 'มรภ.พบ.', 'PBRU'),
(29, 'มหาวิทยาลัยราชภัฏเพชรบูรณ์', 'มร.พช.', 'PCRU'),
(30, 'มหาวิทยาลัยราชภัฏภูเก็ต', 'มรภ.', 'PKRU'),
(31, 'มหาวิทยาลัยราชภัฏมหาสารคาม', 'มรม.', 'RMU'),
(32, 'มหาวิทยาลัยราชภัฏยะลา', 'มรย.', 'YRU'),
(33, 'มหาวิทยาลัยราชภัฏร้อยเอ็ด', 'มรภ.รอ.', 'RERU'),
(34, 'มหาวิทยาลัยราชภัฏราชนครินทร์', 'มรร.', 'RRU'),
(35, 'มหาวิทยาลัยราชภัฏรำไพพรรณี', 'มร.รพ.', 'RBRU'),
(36, 'มหาวิทยาลัยราชภัฏลำปาง', 'มร.ลป.', 'LPRU'),
(37, 'มหาวิทยาลัยราชภัฏเลย', 'มรล.', 'LRU'),
(38, 'มหาวิทยาลัยราชภัฏวไลยอลงกรณ์ ในพระบรมราชูปถัมภ์', 'มรว.', 'VRU'),
(39, 'มหาวิทยาลัยราชภัฏศรีสะเกษ', 'มรภ.ศก.', 'SSKRU'),
(40, 'มหาวิทยาลัยราชภัฏสกลนคร', 'มร.สน.', 'SNRU'),
(41, 'มหาวิทยาลัยราชภัฏสงขลา', 'มรภ.สข.', 'SKRU'),
(42, 'มหาวิทยาลัยราชภัฏสวนสุนันทา', 'มร.สส.', 'SSRU'),
(43, 'มหาวิทยาลัยราชภัฏสุราษฎร์ธานี', 'มรส.', 'SRU'),
(44, 'มหาวิทยาลัยราชภัฏสุรินทร์', 'มรภ.สร.', 'SRRU'),
(45, 'มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง', 'มร.มจ.', 'MCRU'),
(46, 'มหาวิทยาลัยราชภัฏอุดรธานี', 'มร.อด.', 'UDRU'),
(47, 'มหาวิทยาลัยราชภัฏอุตรดิตถ์', 'มรอ.', 'URU'),
(48, 'มหาวิทยาลัยราชภัฏอุบลราชธานี', 'มรภ.อบ.', 'UBRU'),
(49, 'มหาวิทยาลัยเทคโนโลยีราชมงคลกรุงเทพ', 'มทร.กรุงเทพ', 'RMUTK'),
(50, 'มหาวิทยาลัยเทคโนโลยีราชมงคลตะวันออก', 'มทร.ตะวันออก', 'RMUTTO'),
(51, 'มหาวิทยาลัยเทคโนโลยีราชมงคลธัญบุรี', 'มทร.ธัญบุรี', 'RMUTT'),
(52, 'มหาวิทยาลัยเทคโนโลยีราชมงคลพระนคร', 'มทร.พระนคร', 'RMUTP'),
(53, 'มหาวิทยาลัยเทคโนโลยีราชมงคลรัตนโกสินทร์', 'มทร.รัตนโกสินทร์', 'RMUTR'),
(54, 'มหาวิทยาลัยเทคโนโลยีราชมงคลล้านนา', 'มทร.ล้านนา', 'RMUTL'),
(55, 'มหาวิทยาลัยเทคโนโลยีราชมงคลศรีวิชัย', 'มทร.ศรีวิชัย', 'RMUTSV'),
(56, 'มหาวิทยาลัยเทคโนโลยีราชมงคลสุวรรณภูมิ', 'มทร.สุวรรณภูมิ', 'RUS'),
(57, 'มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน', 'มทร.อีสาน', 'RMUTI'),
(58, 'สถาบันการอาชีวศึกษาเกษตรภาคเหนือ', 'สอกน.', 'NVIA'),
(59, 'สถาบันการอาชีวศึกษาเกษตรภาคใต้', 'สอกต.', 'SVIA'),
(60, 'สถาบันการอาชีวศึกษาเกษตรภาคกลาง', 'สอกก.', 'IVACRER'),
(61, 'สถาบันการอาชีวศึกษาเกษตรภาคตะวันออกเฉียงเหนือ', 'สอกฉ.', 'NEVIA'),
(62, 'สถาบันการอาชีวศึกษากรุงเทพมหานคร', 'สอกท.', 'IVEB'),
(63, 'สถาบันการอาชีวศึกษาภาคเหนือ ๑', 'สอน. ๑', 'IVEN 1'),
(64, 'สถาบันการอาชีวศึกษาภาคเหนือ ๒', 'สอน. ๒', 'IVENR 2'),
(65, 'สถาบันการอาชีวศึกษาภาคเหนือ ๓', 'สอน. ๓', 'IVEN 3'),
(66, 'สถาบันการอาชีวศึกษาภาคเหนือ ๔', 'สอน. ๔', 'IVENR 4'),
(67, 'สถาบันการอาชีวศึกษาภาคใต้ ๑', 'สอต. ๑', 'VEIS 1'),
(68, 'สถาบันการอาชีวศึกษาภาคใต้ ๒', 'สอต. ๒', 'VEIS 2'),
(69, 'สถาบันการอาชีวศึกษาภาคใต้ ๓', 'สอต. ๓', 'VEIS 3'),
(70, 'สถาบันการอาชีวศึกษาภาคกลาง ๑', 'สอก. ๑', 'CVEI 1'),
(71, 'สถาบันการอาชีวศึกษาภาคกลาง ๒', 'สอก. ๒', 'IVECR 2'),
(72, 'สถาบันการอาชีวศึกษาภาคกลาง ๓', 'สอก. ๓', 'IVEC 3'),
(73, 'สถาบันการอาชีวศึกษาภาคกลาง ๔', 'สอก. ๔', 'IVEC 4'),
(74, 'สถาบันการอาชีวศึกษาภาคกลาง ๕', 'สอก. ๕', 'VEI 5'),
(75, 'สถาบันการอาชีวศึกษาภาคตะวันออก', 'สออ.', 'EIVT'),
(76, 'สถาบันการอาชีวศึกษาภาคตะวันออกเฉียงเหนือ ๑', 'สอฉ. ๑', 'IVENE 1'),
(77, 'สถาบันการอาชีวศึกษาภาคตะวันออกเฉียงเหนือ ๒', 'สอฉ. ๒', 'IVENE 2'),
(78, 'สถาบันการอาชีวศึกษาภาคตะวันออกเฉียงเหนือ ๓', 'สอฉ. ๓', 'IVENE 3'),
(79, 'สถาบันการอาชีวศึกษาภาคตะวันออกเฉียงเหนือ ๔', 'สอฉ. ๔', 'IVENE 4'),
(80, 'สถาบันการอาชีวศึกษาภาคตะวันออกเฉียงเหนือ ๕', 'สอฉ. ๕', 'IVENE 5'),
(81, 'โรงเรียนนายร้อยตำรวจ', 'รร.นรต.', 'RPCA'),
(82, 'โรงเรียนนายร้อยพระจุลจอมเกล้า', 'รร.จปร.', 'CRMA'),
(83, 'โรงเรียนนายเรือ', 'รร.นร.', 'RTNA'),
(84, 'โรงเรียนนายเรืออากาศนวมินทกษัตริยาธิราช', 'รร.นนก.', 'NKRAFA'),
(85, 'วิทยาลัยพยาบาลกองทัพบก', NULL, NULL),
(86, 'วิทยาลัยพยาบาลกองทัพเรือ', NULL, NULL),
(87, 'วิทยาลัยพยาบาลตำรวจ', NULL, NULL),
(88, 'วิทยาลัยพยาบาลทหารอากาศ', NULL, NULL),
(89, 'วิทยาลัยแพทยศาสตร์พระมงกุฎเกล้า', 'วพม.', 'PCM'),
(90, 'วิทยาลัยการชลประทาน', 'วชป.', NULL),
(91, 'สถาบันการบินพลเรือน', 'สบพ.', 'CATC'),
(92, 'สถาบันการพลศึกษา', 'สพล.', 'IPE'),
(93, 'สถาบันบัณฑิตพัฒนศิลป์', 'BPI', NULL),
(94, 'สถาบันพระบรมราชชนก', 'สบช.', 'PI'),
(95, 'ศูนย์ฝึกพาณิชย์นาวี', 'MMTC', NULL),
(96, 'มหาวิทยาลัยเกษตรศาสตร์', 'มก.', 'KU'),
(97, 'มหาวิทยาลัยขอนแก่น', 'มข.', 'KKU'),
(98, 'จุฬาลงกรณ์มหาวิทยาลัย', 'จุฬาฯ', 'CU'),
(99, 'มหาวิทยาลัยเชียงใหม่', 'มช.', 'CMU'),
(100, 'มหาวิทยาลัยทักษิณ', 'มทษ.', 'TSU'),
(101, 'มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี', 'มจธ.', 'KMUTT'),
(102, 'มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าพระนครเหนือ', 'มจพ.', 'KMUTNB'),
(103, 'มหาวิทยาลัยเทคโนโลยีสุรนารี', 'มทส.', 'SUT'),
(104, 'มหาวิทยาลัยธรรมศาสตร์', 'มธ.', 'TU'),
(105, 'มหาวิทยาลัยนวมินทราธิราช', 'นมร.', 'NMU'),
(106, 'มหาวิทยาลัยบูรพา', 'มบ.', 'BUU'),
(107, 'มหาวิทยาลัยพะเยา', 'มพ.', 'UP'),
(108, 'มหาวิทยาลัยมหาจุฬาลงกรณราชวิทยาลัย', 'มจร', 'MCU'),
(109, 'มหาวิทยาลัยมหามกุฏราชวิทยาลัย', 'มมร', 'MBU'),
(110, 'มหาวิทยาลัยมหิดล', 'มม.', 'MU'),
(111, 'มหาวิทยาลัยแม่โจ้', 'มมจ.', 'MJU'),
(112, 'มหาวิทยาลัยแม่ฟ้าหลวง', 'มฟล.', 'MFU'),
(113, 'มหาวิทยาลัยวลัยลักษณ์', 'มวล.', 'WU'),
(114, 'มหาวิทยาลัยศรีนครินทรวิโรฒ', 'มศว', 'SWU'),
(115, 'มหาวิทยาลัยศิลปากร', 'มศก.', 'SU'),
(116, 'มหาวิทยาลัยสงขลานครินทร์', 'ม.อ.', 'PSU'),
(117, 'มหาวิทยาลัยสวนดุสิต', 'มสด.', 'SDU'),
(118, 'สถาบันการพยาบาลศรีสวรินทิรา สภากาชาดไทย', NULL, NULL),
(119, 'สถาบันดนตรีกัลยาณิวัฒนา', 'สกว.', 'PGVIM'),
(120, 'สถาบันเทคโนโลยีพระจอมเกล้าเจ้าคุณทหารลาดกระบัง', 'สจล.', 'KMITL'),
(121, 'สถาบันบัณฑิตศึกษาจุฬาภรณ์', 'สบจ.', 'CGI'),
(122, 'วิทยาลัยวิทยาศาสตร์การแพทย์เจ้าฟ้าจุฬาภรณ์', 'PCCMS', NULL),
(123, 'มหาวิทยาลัยกรุงเทพสุวรรณภูมิ', 'มกส.', 'BSU'),
(124, 'มหาวิทยาลัยการจัดการและเทคโนโลยีอีสเทิร์น', 'มจท.', 'UMT'),
(125, 'มหาวิทยาลัยเกริก', 'มกร.', 'KRU'),
(126, 'มหาวิทยาลัยเกษมบัณฑิต', 'มกบ.', 'KBU'),
(127, 'มหาวิทยาลัยคริสเตียน', 'มคต.', 'CTU'),
(128, 'มหาวิทยาลัยเฉลิมกาญจนา', 'มฉน.', 'CNU'),
(129, 'มหาวิทยาลัยตาปี', 'มตป.', 'TPU'),
(130, 'มหาวิทยาลัยเจ้าพระยา', 'มจพ.', 'CPU'),
(131, 'มหาวิทยาลัยชินวัตร', 'มชว.', 'SIU'),
(132, 'มหาวิทยาลัยเซนต์จอห์น', 'มซจ.', 'SJU'),
(133, 'มหาวิทยาลัยเทคโนโลยีมหานคร', 'มทม.', 'MUT'),
(134, 'มหาวิทยาลัยธนบุรี', 'มธร.', 'TRU'),
(135, 'มหาวิทยาลัยธุรกิจบัณฑิตย์', 'มธบ.', 'DPU'),
(136, 'มหาวิทยาลัยนอร์ทกรุงเทพ', 'มนก.', 'NBU'),
(137, 'มหาวิทยาลัยนอร์ท-เชียงใหม่', 'มนช.', 'NCU'),
(138, 'มหาวิทยาลัยนานาชาติแสตมฟอร์ด', 'มนชส.', 'STIU'),
(139, 'มหาวิทยาลัยนานาชาติเอเชีย-แปซิฟิก', 'มนอ.', 'AIU'),
(140, 'มหาวิทยาลัยเนชั่น', 'มนช.', 'NTU'),
(141, 'มหาวิทยาลัยปทุมธานี', 'มปท.', 'PTU'),
(142, 'มหาวิทยาลัยพายัพ', 'มพย.', 'PYU'),
(143, 'มหาวิทยาลัยพิษณุโลก', 'มพล.', 'PLU'),
(144, 'มหาวิทยาลัยฟาฏอนี', 'มฟน.', 'FTU'),
(145, 'มหาวิทยาลัยฟาร์อีสเทอร์น', 'มฟอ.', 'FEU'),
(146, 'มหาวิทยาลัยภาคกลาง', 'ม.ภก.', 'UCT'),
(147, 'มหาวิทยาลัยภาคตะวันออกเฉียงเหนือ', 'มภน.', 'NEU'),
(148, 'มหาวิทยาลัยรังสิต', 'มรส.', 'RSU'),
(149, 'มหาวิทยาลัยรัตนบัณฑิต', 'มรบ.', 'RBAC'),
(150, 'มหาวิทยาลัยราชธานี', 'มรธ.', 'RTU'),
(151, 'มหาวิทยาลัยราชพฤกษ์', 'มรพ.', 'RPU'),
(152, 'มหาวิทยาลัยวงษ์ชวลิตกุล', 'มว.', 'VU'),
(153, 'มหาวิทยาลัยเว็บสเตอร์ (ประเทศไทย)', 'มวท.', 'WTUT'),
(154, 'มหาวิทยาลัยเวสเทิร์น', 'มท.', 'WTU'),
(155, 'มหาวิทยาลัยศรีปทุม', 'มศป.', 'SPU'),
(156, 'มหาวิทยาลัยสยาม', 'มส.', 'SU'),
(157, 'มหาวิทยาลัยหอการค้าไทย', 'มกค.', 'UTCC'),
(158, 'มหาวิทยาลัยหัวเฉียวเฉลิมพระเกียรติ', 'มฉก.', 'HCU'),
(159, 'มหาวิทยาลัยหาดใหญ่', 'มหญ.', 'HU'),
(160, 'มหาวิทยาลัยอัสสัมชัญ', 'มอช.', 'ABAC'),
(161, 'มหาวิทยาลัยอีสเทิร์นเอเชีย', 'มอท.', 'EAU'),
(162, 'มหาวิทยาลัยเอเชียอาคเนย์', 'มออ.', 'SAU'),
(163, 'สถาบันกันตนา', 'สก.', 'KI'),
(164, 'สถาบันการจัดการปัญญาภิวัฒน์', 'สกป.', 'PIM'),
(165, 'สถาบันวิทยาการจัดการแห่งแปซิฟิค', 'สวป.', 'PIMS'),
(166, 'สถาบันการเรียนรู้เพื่อปวงชน', 'สรพ.', 'LIFE'),
(167, 'สถาบันเทคโนโลยีไทย-ญี่ปุ่น', 'สทญ.', 'TNI'),
(168, 'สถาบันเทคโนโลยียานยนต์มหาชัย', 'สทม.', 'MIAT'),
(169, 'สถาบันเทคโนโลยีแห่งสุวรรณภูมิ', 'สทส.', 'SVIT'),
(170, 'สถาบันเทคโนโลยีแห่งอโยธยา', 'สทอ.', 'ITA'),
(171, 'สถาบันเทคโนโลยีแห่งเอเชีย', 'สทอ.', 'AIT'),
(172, 'สถาบันรัชต์ภาคย์', 'สรภ.', 'RPI'),
(173, 'สถาบันวิทยสิริเมธี', 'สวส.', 'VISTEC'),
(174, 'สถาบันอาศรมศิลป์', 'สอศ.', 'ASIA'),
(175, 'วิทยาลัยเฉลิมกาญจนาระยอง', 'วฉก.', 'CKC'),
(176, 'วิทยาลัยเชียงราย', 'ว.ชร.', 'CRC'),
(177, 'วิทยาลัยเซนต์หลุยส์', 'วซล.', 'SLC'),
(178, 'วิทยาลัยเซาธ์อีสท์บางกอก', 'ว.ซอบ.', 'SBC'),
(179, 'วิทยาลัยดุสิตธานี', 'วดธ.', 'DTC'),
(180, 'วิทยาลัยทองสุข', 'วทส.', 'TSC'),
(181, 'วิทยาลัยเทคโนโลยีจิตรลดา', 'วทจด.', 'CDTC'),
(182, 'วิทยาลัยเทคโนโลยีพนมวันท์', 'วทพ.', 'PCT'),
(183, 'วิทยาลัยเทคโนโลยีภาคใต้', 'วทต.', 'SCT'),
(184, 'วิทยาลัยเทคโนโลยีสยาม', 'วทส.', 'STC'),
(185, 'วิทยาลัยนครราชสีมา', 'วนม.', 'NMC'),
(186, 'วิทยาลัยนานาชาติเซนต์เทเรซา', 'วนซท.', 'STIC'),
(187, 'วิทยาลัยนานาชาติราฟเฟิลส์', 'วนร.', 'RIC'),
(188, 'วิทยาลัยบัณฑิตเอเซีย', 'วบอ.', 'CAS'),
(189, 'วิทยาลัยพิชญบัณฑิต', 'วพบ.', 'PCBC'),
(190, 'วิทยาลัยพุทธศาสนานานาชาติ', 'วพน.', 'IBC'),
(191, 'วิทยาลัยนอร์ทเทิร์น', 'วนท.', 'NTC'),
(192, 'วิทยาลัยสันตพล', 'วสพ.', 'STC'),
(193, 'วิทยาลัยแสงธรรม', 'วสธ.', 'STC'),
(194, 'วิทยาลัยอินเตอร์เทคลำปาง', 'วอล.', 'LIT'),
(195, 'สถาบันเทคโนโลยีพระจอมเกล้า', NULL, NULL),
(196, 'มหาวิทยาลัยนานาชาติศิรินสยาม', NULL, NULL),
(197, 'มหาวิทยาลัยบริติช (ประเทศไทย)', NULL, NULL),
(198, 'วิทยาลัยบัณฑิตสกลนคร', NULL, NULL),
(199, 'วิทยาลัยพัฒนา', NULL, NULL),
(200, 'มหาวิทยาลัยโยนก', 'มยน.', 'YNU'),
(201, 'มหาวิทยาลัยราชภัฏกาฬสินธุ์', 'มกส.', 'KSU'),
(202, 'มหาวิทยาลัยราชภัฏนครพนม', NULL, NULL),
(203, 'วิทยาลัยรามาอโยธยา', NULL, NULL),
(204, 'วิทยาลัยเทคโนโลยีราชธานีอุดร', NULL, NULL),
(205, 'วิทยาลัยศรีโสภณ', 'ว.ศส.', 'SSC'),
(206, 'วิทยาลัยศรีอีสาน (คณาสวัสดิ์อุทิศ)', NULL, NULL),
(207, 'มหาวิทยาลัยอีสาน', 'ม.อส.', 'ESU'),
(208, 'มหาวิทยาลัยเอเชียน', 'ม.เอเชียน', 'AsianU'),
(209, 'มหาวิทยาลัยรัตนบัณฑิตวิทยาศาสตร์และเทคโนโลยี', 'มรวท.', 'RUST'),
(210, 'มหาวิทยาลัยการกีฬาแห่งชาติ', NULL, NULL),
(211, 'มหาวิทยาลัยชุมพร', NULL, NULL),
(212, 'มหาวิทยาลัยตากสิน', NULL, NULL),
(213, 'มหาวิทยาลัยเทคนิคไทย-เยอรมัน ขอนแก่น หรือมหาวิทยาลัยเทคโนโลยีไทย-เยอรมัน ขอนแก่น', NULL, NULL),
(214, 'มหาวิทยาลัยนานาชาตินครแม่สอด', NULL, NULL),
(215, 'มหาวิทยาลัยน่าน', NULL, NULL),
(216, 'มหาวิทยาลัยพ่อหลวง หรือมหาวิทยาลัยประจวบคีรีขันธ์', NULL, NULL),
(217, 'มหาวิทยาลัยพระพุทธยอดฟ้าจุฬาโลก', NULL, NULL),
(218, 'มหาวิทยาลัยแพร่', NULL, NULL),
(219, 'มหาวิทยาลัยมุกดาหาร เฉลิมพระเกียรติ', NULL, NULL),
(220, 'มหาวิทยาลัยแม่ฮ่องสอน', NULL, NULL),
(221, 'มหาวิทยาลัยสุราษฎร์ธานี', NULL, NULL),
(222, 'มหาวิทยาลัยสุรินทร์', NULL, NULL),
(223, 'มหาวิทยาลัยหนองคาย', NULL, NULL),
(224, 'มหาวิทยาลัยอันดามัน', NULL, NULL),
(225, 'มหาวิทยาลัยอุดรธานี', NULL, NULL),
(226, 'สถาบันเทคโนโลยีจิตรลดา', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_ID` varchar(20) NOT NULL COMMENT 'รหัสผู้ใช้',
  `User_Image` varchar(255) NOT NULL COMMENT 'รูปผู้ใช้',
  `User_Prefix` varchar(20) NOT NULL COMMENT 'คำนำหน้าชื่อ',
  `User_Firstname` varchar(80) NOT NULL COMMENT 'ชื่อ',
  `User_Lastname` varchar(80) NOT NULL COMMENT 'นามสกุล',
  `User_Gender` varchar(5) NOT NULL COMMENT 'เพศ',
  `User_Birthday` date NOT NULL COMMENT 'วัน/เดือน/ปีเกิด',
  `User_Email` varchar(50) NOT NULL COMMENT 'อีเมล',
  `User_PhoneNumber` varchar(10) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `User_UniversityID` int(5) DEFAULT NULL COMMENT 'รหัสมหาวิทยาลัย',
  `User_DepartmentID` int(5) NOT NULL COMMENT 'รหัสแผนก',
  `User_PositionID` int(5) NOT NULL COMMENT 'รหัสตำแหน่ง',
  `Username` varchar(40) NOT NULL COMMENT 'ชื่อผู้ใช้',
  `Password` varchar(40) NOT NULL COMMENT 'พาสเวิร์ด',
  `User_Type` varchar(20) NOT NULL COMMENT 'ประเภทผู้ใช้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางข้อมูลผู้ใช้';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`User_ID`, `User_Image`, `User_Prefix`, `User_Firstname`, `User_Lastname`, `User_Gender`, `User_Birthday`, `User_Email`, `User_PhoneNumber`, `User_UniversityID`, `User_DepartmentID`, `User_PositionID`, `Username`, `Password`, `User_Type`) VALUES
('Intern_Efin001', '../../assets/images/profile/Intern_Efin001.webp', 'นาย', 'ปฐพี', 'ชูเมือง', 'นาย', '2002-09-27', 'pathaphi.pk@gmail.com', '0968537883', 4, 1, 1, 'pathaphi.pk', '123456789', 'intern'),
('Off_Efin001', '../../assets/images/profile/Off_Efin001.webp', 'นาย', 'ปฐพี', 'ชูเมือง', 'ชาย', '2002-09-27', 'pk@gmail.com', '0990990999', NULL, 1, 1, 'pxthaphi', '123456789', 'admin'),
('Off_Efin002', '../../assets/images/profile/Off_Efin002.webp', 'นางสาว', 'ทดสอบ', 'เทส', 'หญิง', '2002-07-09', 'test@gmail.com', '0999999999', NULL, 1, 2, 'testqex', '09909909999za', 'officer'),
('Off_Efin003', '../../assets/images/profile/SuperAdmin_Efin002.webp', 'นาย', 'รังสิมันต์', 'ใบมะ', 'ชาย', '2024-07-24', 'baima@gmail.com', '0998752514', NULL, 1, 2, 'baima123', '123456789baima', 'officer'),
('SuperAdmin_Efin001', '../../assets/images/profile/SuperAdmin_Efin001.webp', 'นาย', 'PK', 'Admin', 'ชาย', '2024-12-11', 'admin@gmail.com', '0997832546', NULL, 1, 1, 'admin', 'admin123456', 'superadmin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`Category_ID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`Department_ID`);

--
-- Indexes for table `intern`
--
ALTER TABLE `intern`
  ADD PRIMARY KEY (`Intern_ID`),
  ADD KEY `Intern & User` (`User_ID`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`Position_ID`),
  ADD KEY `Department_ID` (`Department_ID`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`Project_ID`),
  ADD KEY `Project_UserID` (`Project_UserID`),
  ADD KEY `Project_CategoryID` (`Project_CategoryID`),
  ADD KEY `Project_SubcategoryID` (`Project_SubcategoryID`);

--
-- Indexes for table `projectimages`
--
ALTER TABLE `projectimages`
  ADD PRIMARY KEY (`ProjectImages_ID`),
  ADD KEY `Picture` (`Project_ID`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`Subcategory_ID`),
  ADD KEY `Category_ID` (`Category_ID`);

--
-- Indexes for table `university`
--
ALTER TABLE `university`
  ADD PRIMARY KEY (`University_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_ID`),
  ADD KEY `User&University` (`User_UniversityID`),
  ADD KEY `User In Department` (`User_DepartmentID`),
  ADD KEY `User have position` (`User_PositionID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `Category_ID` int(7) NOT NULL AUTO_INCREMENT COMMENT 'รหัสหมวดหมู่', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `Department_ID` int(5) NOT NULL AUTO_INCREMENT COMMENT 'รหัสแผนก', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `intern`
--
ALTER TABLE `intern`
  MODIFY `Intern_ID` int(7) NOT NULL AUTO_INCREMENT COMMENT 'รหัสฝึกงาน', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `Position_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `Project_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `projectimages`
--
ALTER TABLE `projectimages`
  MODIFY `ProjectImages_ID` int(7) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรูปภาพ', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `Subcategory_ID` int(7) NOT NULL AUTO_INCREMENT COMMENT 'รหัสหมวดหมู่ย่อย', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `university`
--
ALTER TABLE `university`
  MODIFY `University_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสมหาวิทยาลัย', AUTO_INCREMENT=227;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `intern`
--
ALTER TABLE `intern`
  ADD CONSTRAINT `Intern & User` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `position`
--
ALTER TABLE `position`
  ADD CONSTRAINT `position_ibfk_1` FOREIGN KEY (`Department_ID`) REFERENCES `department` (`Department_ID`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`Project_UserID`) REFERENCES `user` (`User_ID`),
  ADD CONSTRAINT `project_ibfk_2` FOREIGN KEY (`Project_CategoryID`) REFERENCES `category` (`Category_ID`),
  ADD CONSTRAINT `project_ibfk_3` FOREIGN KEY (`Project_SubcategoryID`) REFERENCES `subcategory` (`Subcategory_ID`);

--
-- Constraints for table `projectimages`
--
ALTER TABLE `projectimages`
  ADD CONSTRAINT `Picture` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD CONSTRAINT `subcategory_ibfk_1` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `User In Department` FOREIGN KEY (`User_DepartmentID`) REFERENCES `department` (`Department_ID`),
  ADD CONSTRAINT `User have position` FOREIGN KEY (`User_PositionID`) REFERENCES `position` (`Position_ID`),
  ADD CONSTRAINT `User&University` FOREIGN KEY (`User_UniversityID`) REFERENCES `university` (`University_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
