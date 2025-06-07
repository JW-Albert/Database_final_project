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

        .button.success {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .button.success:hover {
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.3);
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
            background: #5a67d8;
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
            <a href="home_page.php" class="back-link">
                <i class="fas fa-arrow-left"></i>
                返回主選單
            </a>
            <div class="header-content">
                <h1><i class="fas fa-search"></i> 查詢資料</h1>
                <p>瀏覽所有數據記錄</p>
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
                    <button class="button success" onclick="loadAllData()">
                        <i class="fas fa-eye"></i>
                        顯示所有資料
                    </button>
                </div>
            </div>
        </div>

        <div id="resultContainer">
            <!-- 查詢結果將在這裡顯示 -->
        </div>
    </div>

    <script src="js/tableNameMap.js"></script>
    <script>
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

        function createTable(data) {
            if (!data || data.length === 0) {
                return `
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <div>沒有資料</div>
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

        async function loadAllData() {
            const tableName = document.getElementById('tableName').value;
            if (!tableName) {
                alert('請選擇人員類型');
                return;
            }

            try {
                const response = await fetch('query_data/main.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        table: tableName,
                        conditions: [] // 空的條件陣列，表示查詢所有資料
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
                    alert('載入資料失敗：' + result.message);
                }
            } catch (error) {
                alert('發生錯誤：' + error.message);
            }
        }
    </script>
</body>

</html>