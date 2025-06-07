<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>上傳照片 - 資料庫管理系統</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-link {
            color: #667eea;
            text-decoration: none;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 10px;
            background: rgba(102, 126, 234, 0.1);
        }

        .back-link:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: translateX(-5px);
        }

        .header-content {
            flex: 1;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header p {
            color: #7f8c8d;
            font-size: 1rem;
        }

        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .section-title {
            color: #2c3e50;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 0.95rem;
        }

        select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 12px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: rgba(255, 255, 255, 1);
        }

        .file-upload-area {
            border: 2px dashed rgba(102, 126, 234, 0.3);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            background: rgba(102, 126, 234, 0.05);
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: rgba(102, 126, 234, 0.5);
            background: rgba(102, 126, 234, 0.1);
        }

        .file-upload-area.dragover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.15);
        }

        .file-upload-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 15px;
        }

        .file-upload-text {
            color: #2c3e50;
            font-size: 1.1rem;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .file-upload-hint {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        #photo {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-preview {
            margin-top: 20px;
            padding: 15px;
            background: rgba(46, 204, 113, 0.1);
            border: 2px solid rgba(46, 204, 113, 0.2);
            border-radius: 12px;
            display: none;
        }

        .file-preview.show {
            display: block;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #27ae60;
            font-weight: 500;
        }

        .button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .button:disabled {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .button.success {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .button.success:hover {
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.3);
        }

        .actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            padding-top: 20px;
            border-top: 2px solid rgba(102, 126, 234, 0.1);
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: rgba(102, 126, 234, 0.2);
            border-radius: 3px;
            overflow: hidden;
            margin-top: 15px;
            display: none;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            width: 0%;
            transition: width 0.3s ease;
        }

        .upload-status {
            margin-top: 15px;
            padding: 12px 16px;
            border-radius: 8px;
            display: none;
        }

        .upload-status.success {
            background: rgba(46, 204, 113, 0.1);
            border: 1px solid rgba(46, 204, 113, 0.3);
            color: #27ae60;
        }

        .upload-status.error {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid rgba(231, 76, 60, 0.3);
            color: #e74c3c;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
            
            .main-card {
                padding: 20px;
            }
            
            .file-upload-area {
                padding: 30px 20px;
            }
            
            .actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .button {
                justify-content: center;
                margin-right: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <a href="home_page.php" class="back-link">
                <i class="fas fa-arrow-left"></i>
                返回主選單
            </a>
            <div class="header-content">
                <h1><i class="fas fa-upload"></i> 上傳教師照片</h1>
                <p>請選擇教授編號並上傳照片，照片將儲存於資料庫</p>
            </div>
        </div>

        <div class="main-card">
            <form action="upload_photo/upload_photo_to_blob.php" method="post" enctype="multipart/form-data" id="uploadForm">
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-id-badge"></i>
                        教師編號
                    </div>
                    <div class="form-group">
                        <label for="professor_id">教授編號：</label>
                        <select id="professor_id" name="professor_id" required>
                            <option value="">請選擇教授</option>
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-image"></i>
                        選擇照片文件
                    </div>
                    <div class="form-group">
                        <div class="file-upload-area" id="uploadArea">
                            <input type="file" id="photo" name="photo" accept="image/*" required>
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text">點擊選擇檔案或拖曳到此處</div>
                            <div class="file-upload-hint">支援 JPG、PNG、GIF 格式，最大 10MB</div>
                        </div>
                        <div class="file-preview" id="filePreview">
                            <div class="file-info">
                                <i class="fas fa-check-circle"></i>
                                <span id="fileName"></span>
                            </div>
                        </div>
                        <div class="progress-bar" id="progressBar">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                        <div class="upload-status" id="uploadStatus"></div>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="button success" id="uploadBtn" disabled>
                        <i class="fas fa-upload"></i>
                        上傳檔案
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const uploadArea = document.getElementById('uploadArea');
        const photoInput = document.getElementById('photo');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const uploadBtn = document.getElementById('uploadBtn');
        const uploadForm = document.getElementById('uploadForm');
        const progressBar = document.getElementById('progressBar');
        const progressFill = document.getElementById('progressFill');
        const uploadStatus = document.getElementById('uploadStatus');
        const professorIdInput = document.getElementById('professor_id');

        // 載入教授名單
        async function loadProfessors() {
            const res = await fetch('get_professor_all_data/get_professors.php');
            const data = await res.json();
            const select = document.getElementById('professor_id');
            select.innerHTML = '<option value="">請選擇教授</option>';
            data.forEach(prof => {
                const opt = document.createElement('option');
                opt.value = prof.professor_id;
                opt.textContent = `${prof.name} (${prof.professor_id})`;
                select.appendChild(opt);
            });
            updateUploadButton();
        }

        // 頁面載入時自動載入
        window.onload = function() {
            loadProfessors();
        }

        // 拖拽上傳功能
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                photoInput.files = files;
                handleFileSelect();
            }
        });

        // 檔案選擇處理
        photoInput.addEventListener('change', handleFileSelect);

        function handleFileSelect() {
            const file = photoInput.files[0];
            if (file) {
                // 檢查文件類型
                if (!file.type.startsWith('image/')) {
                    showStatus('請選擇圖片檔案', 'error');
                    return;
                }

                // 檢查文件大小 (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    showStatus('檔案大小不能超過 10MB', 'error');
                    return;
                }

                fileName.textContent = `${file.name} (${formatFileSize(file.size)})`;
                filePreview.classList.add('show');
                updateUploadButton();
                hideStatus();
            }
        }

        // 檢查上傳按鈕狀態
        function updateUploadButton() {
            const hasFile = photoInput.files.length > 0;
            const hasId = professorIdInput.value.trim() !== '';
            uploadBtn.disabled = !(hasFile && hasId);
        }

        professorIdInput.addEventListener('input', updateUploadButton);

        // 格式化檔案大小
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // 顯示狀態訊息
        function showStatus(message, type) {
            uploadStatus.textContent = message;
            uploadStatus.className = `upload-status ${type}`;
            uploadStatus.style.display = 'block';
        }

        function hideStatus() {
            uploadStatus.style.display = 'none';
        }

        // 表單提交處理
        uploadForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!photoInput.files[0] || !professorIdInput.value.trim()) {
                showStatus('請選擇檔案和教授編號', 'error');
                return;
            }

            const formData = new FormData(uploadForm);
            
            // 顯示進度條
            progressBar.style.display = 'block';
            uploadBtn.disabled = true;
            
            try {
                const response = await fetch('upload_photo/upload_photo_to_blob.php', {
                    method: 'POST',
                    body: formData
                });

                // 模擬進度更新
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += 10;
                    progressFill.style.width = progress + '%';
                    
                    if (progress >= 100) {
                        clearInterval(progressInterval);
                    }
                }, 100);

                const result = await response.json();
                if (response.ok && result.status === 'success') {
                    showStatus('檔案上傳成功！', 'success');
                    // 重置表單
                    setTimeout(() => {
                        uploadForm.reset();
                        filePreview.classList.remove('show');
                        progressBar.style.display = 'none';
                        progressFill.style.width = '0%';
                        updateUploadButton();
                    }, 2000);
                } else {
                    throw new Error(result.message || '上傳失敗');
                }
            } catch (error) {
                showStatus('上傳失敗：' + error.message, 'error');
                progressBar.style.display = 'none';
                progressFill.style.width = '0%';
            } finally {
                uploadBtn.disabled = false;
            }
        });
    </script>
</body>

</html>