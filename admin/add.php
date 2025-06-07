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
    <title>新增資料 - 資料庫管理系統</title>
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
                <h1><i class="fas fa-plus-circle"></i> 新增資料</h1>
                <p>向資料庫添加新的記錄</p>
            </div>
        </div>

        <div class="main-card">
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-users-cog"></i>
                    人員類型設定
                </div>
                <div class="table-input-group">
                    <div class="form-group">
                        <label for="tableName">人員類型：</label>
                        <select id="tableName" required>
                            <option value="">請選擇人員類型</option>
                        </select>
                    </div>
                    <button class="button" onclick="loadTableColumns()">
                        <i class="fas fa-download"></i>
                        載入欄位
                    </button>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-edit"></i>
                    資料欄位
                </div>
                <div id="fieldsContainer" class="fields-container">
                    <!-- 動態欄位將在這裡生成 -->
                </div>
                
                <div class="actions">
                    <!-- <button class="button secondary" onclick="addField()">
                        <i class="fas fa-plus"></i>
                        新增欄位
                    </button> -->
                    <button type="button" class="button success" onclick="submitData()">
                        <i class="fas fa-save"></i>
                        送出資料
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/tableNameMap.js"></script>
    <script>
        let tableColumns = [];
        let selectedColumns = new Set();

        window.onload = async function () {
            try {
                // 只允許這三個資料表
                const allowedTables = ['Professor', 'Admin', 'Staff'];
                const response = await fetch('get_table/main.php');
                const result = await response.json();

                if (result.status === 'success') {
                    const select = document.getElementById('tableName');
                    result.tables.forEach(table => {
                        if (allowedTables.includes(table)) {
                            const option = document.createElement('option');
                            const label = tableNameMap[table] || table;
                            option.value = table;
                            option.textContent = `${label} (${table})`;
                            select.appendChild(option);
                        }
                    });
                } else {
                    alert('資料表載入失敗：' + result.message);
                }
            } catch (error) {
                alert('發生錯誤：' + error.message);
            }
        }


        async function loadTableColumns() {
            const tableName = document.getElementById('tableName').value.trim(); 

            console.log("要送出的 tableName：", tableName);


            // ✅ 明確先 return 阻止錯誤請求
            if (!tableName || !/^[a-zA-Z0-9_]+$/.test(tableName)) {
                alert('請選擇有效的資料表');
                return;
            }

            try {
                const response = await fetch(`get_ele/main.php?table=${encodeURIComponent(tableName)}`);
                const data = await response.json();

                if (data.status === 'success') {
                    tableColumns = data.data.columns;
                    selectedColumns.clear();
                    document.getElementById('fieldsContainer').innerHTML = '';
                    tableColumns.forEach(col => addField(col.name)); // 自動載入所有欄位
                } else {
                    alert('載入欄位失敗：' + data.message);
                }
            } catch (error) {
                alert('載入欄位時發生錯誤：' + error.message);
            }
        }


        function updateAllColumnSelectors() {
            const selects = document.querySelectorAll('.column-select');
            const selectedValues = Array.from(selects).map(select => select.value);

            selects.forEach(select => {
                const currentValue = select.value;
                select.innerHTML = '<option value="">請選擇欄位</option>';

                tableColumns.forEach(column => {
                    const option = document.createElement('option');
                    option.value = column.name;
                    option.textContent = column.name;

                    if (selectedValues.includes(column.name) && column.name !== currentValue) {
                        option.disabled = true;
                    }

                    if (column.name === currentValue) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                });
            });
        }

        function addField(preselectedColumn = null) {
            const container = document.getElementById('fieldsContainer');
            const fieldGroup = document.createElement('div');
            fieldGroup.className = 'field-group';

            // 直接顯示欄位名稱，不用下拉選單
            const columnLabel = document.createElement('label');
            columnLabel.textContent = preselectedColumn;
            columnLabel.style.fontWeight = 'bold';
            columnLabel.style.marginBottom = '6px';

            const valueInput = document.createElement('input');
            valueInput.type = 'text';
            valueInput.placeholder = '請輸入值';
            valueInput.name = preselectedColumn;

            const columnInfo = document.createElement('div');
            columnInfo.className = 'column-info';

            fieldGroup.appendChild(columnLabel);
            fieldGroup.appendChild(valueInput);
            fieldGroup.appendChild(columnInfo);
            container.appendChild(fieldGroup);

            // 顯示欄位資訊
            const column = tableColumns.find(col => col.name === preselectedColumn);
            if (column) {
                let info = [];
                if (column.null === 'NO') {
                    info.push('<span class="required">必填</span>');
                }
                if (column.default !== null) {
                    info.push(`預設值: ${column.default}`);
                }
                if (column.extra) {
                    info.push(`額外: ${column.extra}`);
                }
                columnInfo.innerHTML = info.join(' | ');
            } else {
                columnInfo.innerHTML = '';
            }
        }

        async function submitData() {
            console.log("submitData() 被呼叫了");
            const tableName = document.getElementById('tableName').value;
            if (!tableName) {
                alert('請輸入資料表名稱');
                return;
            }

            const data = {};
            const fields = document.querySelectorAll('.field-group');

            fields.forEach(field => {
                const column = field.querySelector('.column-select').value;
                const value = field.querySelector('input').value;
                if (column) {
                    data[column] = value === '' ? null : value;
                }

            });

            try {
                const response = await fetch('add_data/main.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        table: tableName,
                        data: data
                    })
                });

                const result = await response.json();
                if (result.status === 'success') {
                    alert('資料新增成功！');
                    // 清空表單
                    document.getElementById('tableName').value = '';
                    document.getElementById('fieldsContainer').innerHTML = '';
                    tableColumns = [];
                    selectedColumns.clear();
                } else {
                    alert('新增失敗：' + result.message);
                }
            } catch (error) {
                alert('發生錯誤：' + error.message);
            }
        }
    </script>
</body>

</html>