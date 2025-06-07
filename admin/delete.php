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
    <title>刪除教授資料 - 資料庫管理系統</title>
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

        .warning-banner {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .warning-banner i {
            font-size: 1.5rem;
        }

        .warning-content h3 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .warning-content p {
            font-size: 0.9rem;
            opacity: 0.9;
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
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 0.95rem;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 12px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: rgba(255, 255, 255, 1);
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

        .button.secondary {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
        }

        .button.secondary:hover {
            box-shadow: 0 8px 20px rgba(149, 165, 166, 0.3);
        }

        .button.danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .button.danger:hover {
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
        }

        .button.success {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .button.success:hover {
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.3);
        }

        .field-group {
            background: rgba(231, 76, 60, 0.05);
            border: 2px solid rgba(231, 76, 60, 0.2);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .field-group:hover {
            border-color: rgba(231, 76, 60, 0.3);
        }

        .condition-row {
            display: grid;
            grid-template-columns: 1fr auto 1fr auto;
            gap: 15px;
            align-items: end;
            margin-bottom: 15px;
        }

        .column-info {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-top: 8px;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 8px;
        }

        .required {
            color: #e74c3c;
            font-weight: 600;
        }

        .fields-container {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .fields-container::-webkit-scrollbar {
            width: 6px;
        }

        .fields-container::-webkit-scrollbar-track {
            background: rgba(231, 76, 60, 0.1);
            border-radius: 3px;
        }

        .fields-container::-webkit-scrollbar-thumb {
            background: rgba(231, 76, 60, 0.3);
            border-radius: 3px;
        }

        .fields-container::-webkit-scrollbar-thumb:hover {
            background: rgba(231, 76, 60, 0.5);
        }

        .actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            padding-top: 20px;
            border-top: 2px solid rgba(231, 76, 60, 0.1);
        }

        .table-input-group {
            display: flex;
            gap: 10px;
            align-items: end;
        }

        .table-input-group .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .danger-area {
            margin: 30px 0;
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

            .table-input-group {
                flex-direction: column;
            }

            .condition-row {
                grid-template-columns: 1fr;
                gap: 10px;
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
                <h1><i class="fas fa-trash-alt"></i> 刪除教授全部資料</h1>
                <p>選擇教授後，將會刪除該教授在所有相關資料表的資料，請謹慎操作！</p>
            </div>
        </div>
        <div class="main-card danger-area">
            <div class="form-section">
                <label for="professorSelect">選擇教授：</label>
                <select id="professorSelect"></select>
            </div>
            <button class="button danger" onclick="deleteProfessorAllData()">
                <i class="fas fa-trash"></i>
                刪除該教授所有資料
            </button>
        </div>
    </div>
    <script>
        // 載入教授名單
        async function loadProfessors() {
            const res = await fetch('get_professor_all_data/get_professors.php');
            const data = await res.json();
            const select = document.getElementById('professorSelect');
            select.innerHTML = '';
            data.forEach(prof => {
                const opt = document.createElement('option');
                opt.value = prof.professor_id;
                opt.textContent = prof.name + ' (' + prof.professor_id + ')';
                select.appendChild(opt);
            });
        }

        // 刪除教授所有資料
        async function deleteProfessorAllData() {
            const id = document.getElementById('professorSelect').value;
            if (!id) {
                alert('請先選擇教授');
                return;
            }
            if (!confirm('確定要刪除該教授在所有資料表的所有資料嗎？此操作無法復原！')) return;
            const res = await fetch('./delete_professor_all_data/delete_professor_all_data.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ professor_id: id })
            });
            const result = await res.json();
            if (result.status === 'success') {
                alert('刪除成功！');
                loadProfessors();
            } else {
                alert('刪除失敗：' + result.message);
            }
        }

        window.onload = loadProfessors;
    </script>
</body>

</html>