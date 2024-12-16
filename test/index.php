<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เพิ่มข้อมูลโปรเจคใหม่</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .upload-area {
      border: 2px dashed #ccc;
      border-radius: 10px;
      text-align: center;
      padding: 20px;
      background-color: #f9f9f9;
    }
    .upload-area:hover {
      border-color: #007bff;
      background-color: #e9f5ff;
    }
    .custom-file-preview {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 10px;
    }
    .file-preview {
      width: 100px;
      height: 100px;
      position: relative;
    }
    .file-preview img {
      max-width: 100%;
      max-height: 100%;
      object-fit: cover;
      border-radius: 5px;
    }
    .file-preview button {
      position: absolute;
      top: 5px;
      right: 5px;
      background-color: #dc3545;
      border: none;
      color: white;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      cursor: pointer;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <h1 class="mb-4">เพิ่มข้อมูลโปรเจคใหม่</h1>
  <form>
    <!-- Upload Area -->
    <div class="mb-4">
      <div class="upload-area" id="upload-area">
        <p>คลิกเพื่อเลือกไฟล์ หรือ ลากไฟล์รูปมาที่นี่</p>
        <p class="text-muted">รองรับเฉพาะไฟล์ PNG, JPG เท่านั้น และไม่เกิน 5 MB ต่อไฟล์</p>
        <input type="file" id="fileInput" class="form-control d-none" multiple accept=".png, .jpg, .jpeg">
      </div>
      <div class="custom-file-preview" id="filePreview"></div>
    </div>

    <!-- Project Name -->
    <div class="mb-3">
      <label for="projectName" class="form-label">ชื่อโปรเจค</label>
      <input type="text" class="form-control" id="projectName" placeholder="ป้อนชื่อโปรเจค">
    </div>

    <!-- Project Description -->
    <div class="mb-3">
      <label for="projectDescription" class="form-label">รายละเอียดโปรเจค</label>
      <textarea class="form-control" id="projectDescription" rows="5" placeholder="ป้อนรายละเอียดโปรเจค..."></textarea>
    </div>

    <!-- Category -->
    <div class="mb-3">
      <label for="category" class="form-label">หมวดหมู่</label>
      <select id="category" class="form-select">
        <option value="">กรุณาเลือกหมวดหมู่</option>
        <option value="1">หมวดหมู่ที่ 1</option>
        <option value="2">หมวดหมู่ที่ 2</option>
      </select>
    </div>

    <!-- Owner -->
    <div class="mb-3">
      <label for="owner" class="form-label">เจ้าของโปรเจค</label>
      <select id="owner" class="form-select">
        <option value="">กรุณาเลือกเจ้าของโปรเจค</option>
        <option value="1">ผู้ใช้งาน 1</option>
        <option value="2">ผู้ใช้งาน 2</option>
      </select>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
  </form>
</div>

<script>
  const uploadArea = document.getElementById('upload-area');
  const fileInput = document.getElementById('fileInput');
  const filePreview = document.getElementById('filePreview');

  // Trigger file input when clicking upload area
  uploadArea.addEventListener('click', () => fileInput.click());

  // Handle file input changes
  fileInput.addEventListener('change', () => {
    const files = Array.from(fileInput.files);
    filePreview.innerHTML = '';
    files.forEach((file, index) => {
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          const div = document.createElement('div');
          div.classList.add('file-preview');
          div.innerHTML = `
            <img src="${e.target.result}" alt="preview">
            <button type="button" data-index="${index}">&times;</button>
          `;
          filePreview.appendChild(div);
        };
        reader.readAsDataURL(file);
      }
    });
  });

  // Remove selected image preview
  filePreview.addEventListener('click', (e) => {
    if (e.target.tagName === 'BUTTON') {
      const index = e.target.getAttribute('data-index');
      const files = Array.from(fileInput.files);
      files.splice(index, 1);
      fileInput.files = new DataTransfer().files;
      filePreview.children[index].remove();
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
