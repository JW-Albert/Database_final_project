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
        .table-scroll-x {
            width: 100%;
            overflow-x: auto;
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
        // 定義中文表格名稱
        const tableNameMap = {
            'Professor': '教授',
            'Course': '課程',
            'DepartmentHead': '系主任',
            'Education': '學歷',
            'Expertise': '專長',
            'ExternalAward': '校外獲獎',
            'ExternalExperience': '校外經歷',
            'IndustryProject': '產學合作',
            'InternalAward': '校內獲獎',
            'InternalExperience': '校內經歷',
            'JournalPublication': '發表期刊',
            'Lecture': '演講',
            'NSCProject': '國科會計畫',
            'Patent': '專利'
        };

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

            // 預先取得所有欄位註解
            window.tableMeta = {};
            for (const table in data) {
                const metaRes = await fetch(`get_professor_all_data/get_table_structure.php?table=${table}`);
                const metaData = await metaRes.json();

                window.tableMeta[table] = {};
                metaData.forEach(field => {
                    window.tableMeta[table][field.Field] = field.Comment || field.Field;
                });
            }

            // 顯示主表
            document.getElementById('professorData').innerHTML = renderTable('Professor', data.Professor);

            // 顯示所有相關表
            let html = '';
            for (const table in data) {
                if (table !== 'Professor') {
                    const displayName = tableNameMap[table] ? `${tableNameMap[table]} ${table}` : table;
                    html += `<h3>${displayName}</h3>` + renderTable(table, data[table]);
                }
            }
            document.getElementById('relatedTables').innerHTML = html;
        }

        // 渲染可編輯表格
        function renderTable(table, rows) {
            // 主鍵定義
            const primaryKeys = {
                'Professor': 'professor_id',
                'Course': 'id',
                'DepartmentHead': 'id',
                'Education': 'id',
                'Expertise': 'expertise_id',
                'ExternalAward': 'id',
                'ExternalExperience': 'id',
                'IndustryProject': 'id',
                'InternalAward': 'id',
                'InternalExperience': 'id',
                'JournalPublication': 'id',
                'Lecture': 'id',
                'NSCProject': 'id',
                'Patent': 'id'
            };
            
            const primaryKey = primaryKeys[table];
            
            // 如果沒有資料，顯示不同的訊息
            if (!rows || rows.length === 0) {
                if (table === 'Professor') {
                    return `<div style="color:#888;margin-bottom:16px;">教授基本資料不存在</div>`;
                }
                return `
                    <div style="color:#888;margin-bottom:16px;">
                        此表格目前無資料
                        <div style="text-align:right;">
                            <button class="button" onclick="addNewRow('${table}')" style="margin-left: 10px;">
                                <i class="fas fa-plus"></i>
                                新增資料
                            </button>
                        </div>
                    </div>
                    <div id="newRows_${table}"></div>
                `;
            }
            
            // 取得欄位 comment 對應表
            const tableMeta = window.tableMeta || {};
            let html = `<table class="data-table"><thead><tr>`;
            // 過濾掉 password 欄位
            const columns = Object.keys(rows[0]).filter(col => col !== 'password');
            columns.forEach(col => {
                const comment = (tableMeta[table] && tableMeta[table][col]) ? tableMeta[table][col] : col;
                html += `<th>${comment}${col === primaryKey ? ' (主鍵)' : ''}</th>`;
            });
            
            // Professor 表格不顯示操作欄
            if (table !== 'Professor') {
                html += `<th>操作</th>`;
            }
            
            html += `</tr></thead><tbody>`;
            
            rows.forEach((row, idx) => {
            html += '<tr>';
            columns.forEach(col => {
                const val = row[col];
                const isPrimaryKey = col === primaryKey;
                const inputClass = isPrimaryKey ? 'primary-key-field' : '';
                const title = isPrimaryKey ? 'title="主鍵欄位，不能修改"' : '';
                html += `<td><input class="${inputClass}" ${title} data-table="${table}" data-row="${idx}" data-col="${col}" value="${val ?? ''}" ${isPrimaryKey ? 'style="background-color: #fff3cd; border-color: #ffc107;"' : ''}></td>`;
            });
                
                // Professor 表格不顯示刪除按鈕
                if (table !== 'Professor') {
                    html += `<td><button class="button danger" onclick="deleteRow('${table}', ${idx})" style="padding: 4px 8px; font-size: 0.8rem;"><i class="fas fa-trash"></i></button></td>`;
                }
                
                html += '</tr>';
            });
            html += '</tbody></table>';
            
            // Professor 表格不顯示新增按鈕
            if (table !== 'Professor') {
                html += `
                    <div style="text-align:right";>
                        <button class="button" onclick="addNewRow('${table}')" style="margin-bottom: 15px;">
                            <i class="fas fa-plus"></i>
                            新增一列資料
                        </button>
                    </div>
                    <div id="newRows_${table}"></div>
                `;
            }
            
            // 讓表格可橫向捲動
            return `<div class="table-scroll-x">${html}</div>`;
        }

        // 新增一列空資料
        async function addNewRow(table) {
            // 首先取得該表格的欄位結構
            try {
                const res = await fetch(`get_professor_all_data/get_table_structure.php?table=${table}`);
                const structure = await res.json();
                
                const container = document.getElementById(`newRows_${table}`);
                const newRowIndex = container.children.length;
                
                // 主鍵定義
                const primaryKeys = {
                    'Professor': 'professor_id',
                    'Course': 'id',
                    'DepartmentHead': 'id',
                    'Education': 'id',
                    'Expertise': 'expertise_id',
                    'ExternalAward': 'id',
                    'ExternalExperience': 'id',
                    'IndustryProject': 'id',
                    'InternalAward': 'id',
                    'InternalExperience': 'id',
                    'JournalPublication': 'id',
                    'Lecture': 'id',
                    'NSCProject': 'id',
                    'Patent': 'id'
                };
                
                const primaryKey = primaryKeys[table];
                
                let html = `<div class="new-row" style="border: 2px dashed #667eea; padding: 15px; margin-bottom: 10px; border-radius: 8px; background: rgba(102, 126, 234, 0.05);">`;
                html += `<h4 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-plus-circle"></i> 新增資料列</h4>`;
                html += `<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">`;
                
                structure.forEach(field => {
                    const isPrimaryKey = field.Field === primaryKey;
                    const isRequired = field.Null === 'NO' && !field.Extra.includes('auto_increment');
                    const placeholder = isPrimaryKey && field.Extra.includes('auto_increment') ? '自動產生' : '';
                    let disabled = isPrimaryKey && field.Extra.includes('auto_increment') ? 'disabled' : '';

                    // 如果是 professor_id 欄位，且不是 Professor 主表，則自動填入並不可更改
                    let value = '';
                    if (field.Field === 'professor_id') {
                        value = document.getElementById('professorSelect').value;
                        disabled = 'disabled';
                    }
                    
                    html += `
                        <div>
                            <label style="font-size: 0.9rem; color: #2c3e50; margin-bottom: 4px; display: block;">
                                ${field.Comment || field.Field}
                                ${isRequired ? '<span style="color: #e74c3c;">*</span>' : ''}
                                ${isPrimaryKey ? ' (主鍵)' : ''}
                            </label>
                            <input 
                                data-table="${table}" 
                                data-row="new_${newRowIndex}" 
                                data-col="${field.Field}" 
                                placeholder="${placeholder}"
                                value="${value}"
                                ${disabled}
                                style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; ${isPrimaryKey ? 'background-color: #f8f9fa;' : ''}"
                            >
                            <div style="font-size: 0.75rem; color: #666; margin-top: 2px;">
                                ${field.Type} ${field.Null === 'NO' ? '(必填)' : '(可空)'}
                            </div>
                        </div>
                    `;
                });
                
                html += `</div>`;
                html += `<button class="button danger" onclick="removeNewRow(this)" style="margin-top: 10px; padding: 6px 12px; font-size: 0.9rem;">`;
                html += `<i class="fas fa-times"></i> 移除此列</button>`;
                html += `</div>`;
                
                container.insertAdjacentHTML('beforeend', html);
            } catch (error) {
                alert('取得表格結構失敗：' + error.message);
            }
        }

        // 移除新增的行
        function removeNewRow(button) {
            button.closest('.new-row').remove();
        }

        // 標記要刪除的行
        function deleteRow(table, rowIndex) {
            if (confirm('確定要刪除這筆資料嗎？')) {
                const row = document.querySelector(`input[data-table="${table}"][data-row="${rowIndex}"]`).closest('tr');
                row.style.backgroundColor = '#ffe6e6';
                row.style.textDecoration = 'line-through';
                row.setAttribute('data-delete', 'true');
                
                // 添加恢復按鈕
                const lastCell = row.lastElementChild;
                lastCell.innerHTML = `
                    <button class="button success" onclick="restoreRow('${table}', ${rowIndex})" style="padding: 4px 8px; font-size: 0.8rem;">
                        <i class="fas fa-undo"></i> 恢復
                    </button>
                `;
            }
        }

        // 恢復被標記刪除的行
        function restoreRow(table, rowIndex) {
            const row = document.querySelector(`input[data-table="${table}"][data-row="${rowIndex}"]`).closest('tr');
            row.style.backgroundColor = '';
            row.style.textDecoration = '';
            row.removeAttribute('data-delete');
            
            // 恢復刪除按鈕
            const lastCell = row.lastElementChild;
            lastCell.innerHTML = `
                <button class="button danger" onclick="deleteRow('${table}', ${rowIndex})" style="padding: 4px 8px; font-size: 0.8rem;">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }

        // 收集所有欄位並送出
        async function submitAllUpdates() {
            const inputs = document.querySelectorAll('input[data-table]');
            const updates = {};
            const inserts = {};
            const deletes = {};
            
            // 主鍵定義
            const primaryKeys = {
                'Professor': 'professor_id',
                'Course': 'id',
                'DepartmentHead': 'id',
                'Education': 'id',
                'Expertise': 'expertise_id',
                'ExternalAward': 'id',
                'ExternalExperience': 'id',
                'IndustryProject': 'id',
                'InternalAward': 'id',
                'InternalExperience': 'id',
                'JournalPublication': 'id',
                'Lecture': 'id',
                'NSCProject': 'id',
                'Patent': 'id'
            };
            
            // 檢查是否有修改主鍵
            const modifiedPrimaryKeys = [];
            
            inputs.forEach(input => {
                const table = input.dataset.table;
                const row = input.dataset.row;
                const col = input.dataset.col;
                const primaryKey = primaryKeys[table];
                
                // 處理新增資料
                if (row.startsWith('new_')) {
                    inserts[table] = inserts[table] || [];
                    const newRowIndex = row.replace('new_', '');
                    inserts[table][newRowIndex] = inserts[table][newRowIndex] || {};
                    inserts[table][newRowIndex][col] = input.value;
                    return;
                }
                
                // 檢查主鍵修改
                if (col === primaryKey && input.defaultValue !== input.value) {
                    modifiedPrimaryKeys.push(`${table}.${col}`);
                }
                
                updates[table] = updates[table] || [];
                updates[table][row] = updates[table][row] || {};
                updates[table][row][col] = input.value;
            });
            
            // 收集要刪除的資料
            const deleteRows = document.querySelectorAll('tr[data-delete="true"]');
            deleteRows.forEach(row => {
                const firstInput = row.querySelector('input[data-table]');
                if (firstInput) {
                    const table = firstInput.dataset.table;
                    const rowIndex = firstInput.dataset.row;
                    const primaryKey = primaryKeys[table];
                    
                    // 找到主鍵值
                    const pkInput = row.querySelector(`input[data-col="${primaryKey}"]`);
                    if (pkInput && pkInput.value) {
                        deletes[table] = deletes[table] || [];
                        deletes[table].push(pkInput.value);
                    }
                }
            });
            
            // 如果有修改主鍵，顯示錯誤訊息
            if (modifiedPrimaryKeys.length > 0) {
                alert('錯誤：不允許修改主鍵欄位！\n被修改的主鍵：\n' + modifiedPrimaryKeys.join('\n'));
                return;
            }
            
            // POST 給後端
            try {
                const res = await fetch('update_professor_all_data/update_professor_all_data.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        updates: updates,
                        inserts: inserts,
                        deletes: deletes
                    })
                });
                const result = await res.json();
                
                if (result.status === 'error') {
                    alert('錯誤：' + result.message);
                } else {
                    alert(result.message);
                    // 重新載入資料
                    loadProfessorData();
                }
            } catch (error) {
                alert('發生錯誤：' + error.message);
            }
        }

        // 頁面載入時先載入教授選項
        window.onload = function() {
            loadProfessors();
        };
    </script>
</body>
</html>