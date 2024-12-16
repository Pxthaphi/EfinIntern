<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quill.js Example</title>
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
</head>

<body>
    <h1>Quill.js Example</h1>

    <!-- ฟอร์มสำหรับ Quill.js -->
    <form method="POST" action="">
        <div id="editor-container" style="height: 200px; border: 1px solid #ccc;"></div>

        <!-- Hidden Input Field -->
        <input type="hidden" name="project_detail" id="hiddenDescription">

        <button type="submit">Submit</button>
    </form>

    <?php
    // ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST หรือไม่
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // รับค่าที่ส่งมาจากฟอร์ม
        $project_detail = $_POST['project_detail'];

        var_dump($_POST);

        // // แสดงข้อมูลที่รับมา
        // echo "<h2>ข้อมูลที่ได้รับจาก Quill:</h2>";
        // echo "<div style='border: 1px solid #ccc; padding: 10px;'>";
        // echo $project_detail; // แสดงผล HTML ที่ส่งมา
        // echo "</div>";
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
    <script>
        // Initialize Quill.js
        const quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{
                        'header': [1, 2, false]
                    }],
                    ['image', 'video', 'code-block']
                ]
            }
        });

        // Update hidden input field with Quill content on form submit
        document.querySelector('form').addEventListener('submit', function() {
            const hiddenDescription = document.querySelector('#hiddenDescription');
            hiddenDescription.value = quill.root.innerHTML; // Get HTML content from Quill
        });
    </script>
</body>

</html>