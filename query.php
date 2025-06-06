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
    <title>查詢資料 - 資料庫管理系統</title>
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
            max-width: 1200px;
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

        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .result-table th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .result-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        }

        .result-table tr:nth-child(even) {
            background: rgba(102, 126, 234, 0.05);
        }

        .result-table tr:hover {
            background: rgba(102, 126, 234, 0.1);
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            margin-top: 20px;
        }

        .no-results i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #bdc3c7;
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

            .result-table {
                font-size: 0.9rem;
            }

            .result-table th,
            .result-table td {
                padding: 10px 8px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <a href="index.html" class="back-link">
                <i class="fas fa-arrow-left"></i>
                返回主選單
            </a>
            <div class="header-content">
                <h1><i class="fas fa-search"></i> 查詢資料</h1>
                <p>快速搜尋和瀏覽所有數據記錄</p>
            </div>
        </div>

        <div class="main-card">
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-table"></i>
                    資料表設定
                </div>
                <div class="table-input-group">
                    <div class="form-group">
                        <label for="tableName">資料表名稱：</label>
                        <input type="text" id="tableName" placeholder="請輸入資料表名稱" required>
                    </div>
                    <button class="button" onclick="loadTableColumns()">
                        <i class="fas fa-download"></i>
                        載入欄位
                    </button>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-filter"></i>
                    查詢條件設定
                </div>
                <div id="fieldsContainer" class="fields-container">
                    <!-- 動態條件欄位將在這裡生成 -->
                </div>
                
                <div class="actions">
                    <button class="button secondary" onclick="addField()">
                        <i class="fas fa-plus"></i>
                        新增條件
                    </button>
                    <button class="button success" onclick="submitQuery()">
                        <i class="fas fa-search"></i>
                        執行查詢
                    </button>
                </div>
            </div>
        </div>

        <div id="resultContainer">
            <!-- 查詢結果將在這裡顯示 -->
        </div>
    </div>

    <script>
        let tableColumns = [];
        let selectedColumns = new Set();

        async function loadTableColumns() {
            const tableName = document.getElementById('tableName').value;
            if (!tableName) {
                alert('請輸入資料表名稱');
                return;
            }

            try {
                const response = await fetch(`http://localhost/get_ele/main.php?table=${encodeURIComponent(tableName)}`);
                const data = await response.json();

                if (data.status === 'success') {
                    tableColumns = data.data.columns;
                    selectedColumns.clear();
                    document.getElementById('fieldsContainer').innerHTML = '';
                    addField(); // 自動新增第一個條件
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

        function addField() {
            const container = document.getElementById('fieldsContainer');
            const fieldGroup = document.createElement('div');
            fieldGroup.className = 'field-group';

            const conditionRow = document.createElement('div');
            conditionRow.className = 'condition-row';

            const columnSelect = document.createElement('select');
            columnSelect.className = 'column-select';
            columnSelect.onchange = function () {
                updateAllColumnSelectors();
                updateColumnInfo(this);
            };

            const operatorSelect = document.createElement('select');
            operatorSelect.innerHTML = `
                <option value="=">=</option>
                <option value="!=">!=</option>
                <option value=">">></option>
                <option value="<"><</option>
                <option value=">=">>=</option>
                <option value="<="><=</option>
                <option value="LIKE">LIKE</option>
            `;

            const valueInput = document.createElement('input');
            valueInput.type = 'text';
            valueInput.placeholder = '請輸入值';

            const removeButton = document.createElement('button');
            removeButton.innerHTML = '<i class="fas fa-trash"></i> 移除';
            removeButton.className = 'button danger';
            removeButton.onclick = function () {
                container.removeChild(fieldGroup);
                updateAllColumnSelectors();
            };

            const columnInfo = document.createElement('div');
            columnInfo.className = 'column-info';

            conditionRow.appendChild(columnSelect);
            conditionRow.appendChild(operatorSelect);
            conditionRow.appendChild(valueInput);
            conditionRow.appendChild(removeButton);

            fieldGroup.appendChild(conditionRow);
            fieldGroup.appendChild(columnInfo);
            container.appendChild(fieldGroup);

            updateAllColumnSelectors();
        }

        function updateColumnInfo(select) {
            const columnName = select.value;
            const columnInfo = select.parentElement.parentElement.querySelector('.column-info');
            const column = tableColumns.find(col => col.name === columnName);

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

        function createTable(data) {
            if (!data || data.length === 0) {
                return `
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <div>沒有符合條件的資料</div>
                    </div>
                `;
            }

            const table = document.createElement('table');
            table.className = 'result-table';

            // 建立表頭
            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            Object.keys(data[0]).forEach(key => {
                const th = document.createElement('th');
                th.textContent = key;
                headerRow.appendChild(th);
            });
            thead.appendChild(headerRow);
            table.appendChild(thead);

            // 建立表格內容
            const tbody = document.createElement('tbody');
            data.forEach(row => {
                const tr = document.createElement('tr');
                Object.values(row).forEach(value => {
                    const td = document.createElement('td');
                    td.textContent = value;
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
            table.appendChild(tbody);

            return table;
        }

        async function submitQuery() {
            const tableName = document.getElementById('tableName').value;
            if (!tableName) {
                alert('請輸入資料表名稱');
                return;
            }

            const conditions = [];
            const fields = document.querySelectorAll('.field-group');

            fields.forEach(field => {
                const conditionRow = field.querySelector('.condition-row');
                const column = conditionRow.querySelector('.column-select').value;
                const operator = conditionRow.querySelector('select:nth-child(2)').value;
                const value = conditionRow.querySelector('input').value;
                if (column) {
                    conditions.push({
                        column: column,
                        operator: operator,
                        value: value
                    });
                }
            });

            try {
                const response = await fetch('http://localhost/query_data/main.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        table: tableName,
                        conditions: conditions
                    })
                });

                const result = await response.json();
                if (result.status === 'success') {
                    const resultContainer = document.getElementById('resultContainer');
                    if (result.data && result.data.length > 0) {
                        resultContainer.innerHTML = '';
                        resultContainer.appendChild(createTable(result.data));
                    } else {
                        resultContainer.innerHTML = createTable([]);
                    }
                } else {
                    alert('查詢失敗：' + result.message);
                }
            } catch (error) {
                alert('發生錯誤：' + error.message);
            }
        }
    </script>
</body>

</html>