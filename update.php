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
    <title>修改資料 - 資料庫管理系統</title>
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
            max-width: 1000px;
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

        .button.warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
        }

        .button.warning:hover {
            box-shadow: 0 8px 20px rgba(243, 156, 18, 0.3);
        }

        .field-group {
            background: rgba(102, 126, 234, 0.05);
            border: 2px solid rgba(102, 126, 234, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .field-group:hover {
            border-color: rgba(102, 126, 234, 0.2);
        }

        .condition-field-group {
            background: rgba(243, 156, 18, 0.05);
            border: 2px solid rgba(243, 156, 18, 0.1);
        }

        .condition-field-group:hover {
            border-color: rgba(243, 156, 18, 0.2);
        }

        .condition-row {
            display: grid;
            grid-template-columns: 1fr auto 1fr auto;
            gap: 15px;
            align-items: end;
            margin-bottom: 15px;
        }

        .value-row {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
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
            background: rgba(102, 126, 234, 0.1);
            border-radius: 3px;
        }

        .fields-container::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.3);
            border-radius: 3px;
        }

        .fields-container::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.5);
        }

        .actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            padding-top: 20px;
            border-top: 2px solid rgba(102, 126, 234, 0.1);
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

        .two-column-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        table.data-table th,
        table.data-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        table.data-table th {
            background: #f5f6fa;
            font-weight: bold;
            color: #333;
        }

        table.data-table tr:last-child td {
            border-bottom: none;
        }

        table.data-table input {
            width: 95%;
            padding: 4px 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
            background: #fafbfc;
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

            .condition-row,
            .value-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .two-column-layout {
                grid-template-columns: 1fr;
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
                <h1><i class="fas fa-edit"></i> 修改教授相關資料</h1>
                <p>選擇教授並直接編輯所有相關資料，儲存後會同步更新所有資料表</p>
            </div>
        </div>
        <div class="main-card">
            <!-- 教授選擇區塊 -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-user"></i>
                    教授資料設定
                </div>
                <div class="form-group">
                    <label for="professorSelect">選擇教授：</label>
                    <select id="professorSelect"></select>
                </div>
                <button class="button" onclick="loadProfessorData()">
                    <i class="fas fa-download"></i>
                    載入資料
                </button>
                <div id="professorData"></div>
                <div id="relatedTables"></div>
                <button class="button success" onclick="submitAllUpdates()">
                    <i class="fas fa-save"></i>
                    儲存所有修改
                </button>
            </div>
        </div>
    </div>
    <script>
        // 取得所有教授
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

        // 載入該教授所有資料
        async function loadProfessorData() {
            const id = document.getElementById('professorSelect').value;
            const res = await fetch('get_professor_all_data/get_professor_all_data.php?id=' + encodeURIComponent(id));
            const data = await res.json();

            // 顯示主表
            document.getElementById('professorData').innerHTML = renderTable('Professor', data.Professor);

            // 顯示所有相關表
            let html = '';
            for (const table in data) {
                if (table !== 'Professor') {
                    html += `<h3>${table}</h3>` + renderTable(table, data[table]);
                }
            }
            document.getElementById('relatedTables').innerHTML = html;
        }

        // 渲染可編輯表格
        function renderTable(table, rows) {
            if (!rows || rows.length === 0) return '<div style="color:#888;margin-bottom:16px;">無資料</div>';
            // 取得欄位 comment 對應表
            const tableMeta = window.tableMeta || {};
            let html = `<table class="data-table"><thead><tr>`;
            Object.keys(rows[0]).forEach(col => {
                const comment = (tableMeta[table] && tableMeta[table][col]) ? tableMeta[table][col] : col;
                html += `<th>${comment}</th>`;
            });
            html += '</tr></thead><tbody>';
            rows.forEach((row, idx) => {
                html += '<tr>';
                Object.entries(row).forEach(([col, val]) => {
                    html += `<td><input data-table="${table}" data-row="${idx}" data-col="${col}" value="${val ?? ''}"></td>`;
                });
                html += '</tr>';
            });
            html += '</tbody></table>';
            return html;
        }

        // 收集所有欄位並送出
        async function submitAllUpdates() {
            const inputs = document.querySelectorAll('input[data-table]');
            const updates = {};
            inputs.forEach(input => {
                const table = input.dataset.table;
                const row = input.dataset.row;
                const col = input.dataset.col;
                updates[table] = updates[table] || [];
                updates[table][row] = updates[table][row] || {};
                updates[table][row][col] = input.value;
            });
            // POST 給後端
            const res = await fetch('update_professor_all_data/update_professor_all_data.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(updates)
            });
            const result = await res.json();
            alert(result.message);
        }

        // 頁面載入時先載入教授選項
        window.onload = function() {
            loadProfessors();
        };
    </script>
</body>
</html>