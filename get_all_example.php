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
    <title>查詢所有資料 - 資料庫管理系統</title>
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

        input[type="text"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 12px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        input[type="text"]:focus {
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
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
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

        .bio-cell {
            height: 120px;
            max-width: 300px;
            overflow-y: auto;
            white-space: pre-wrap;
            line-height: 1.4;
            font-size: 0.9rem;
        }

        .bio-cell::-webkit-scrollbar {
            width: 4px;
        }

        .bio-cell::-webkit-scrollbar-track {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 2px;
        }

        .bio-cell::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.3);
            border-radius: 2px;
        }

        .result-count {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            color: #2c3e50;
            font-weight: 500;
        }

        .result-count i {
            margin-right: 8px;
            color: #667eea;
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

        .error {
            background: rgba(231, 76, 60, 0.1);
            border: 2px solid rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            padding: 15px;
            border-radius: 12px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 16px;
            margin-top: 20px;
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

            .result-table {
                font-size: 0.9rem;
            }

            .result-table th,
            .result-table td {
                padding: 10px 8px;
            }

            .bio-cell {
                max-width: 200px;
                height: 100px;
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
                <h1><i class="fas fa-database"></i> 查詢所有資料</h1>
                <p>檢視資料表中的所有記錄和完整內容</p>
            </div>
        </div>

        <div class="main-card">
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-table"></i>
                    資料表設定
                </div>
                <div class="form-group">
                    <label for="tableName">資料表名稱：</label>
                    <input type="text" id="tableName" placeholder="請輸入資料表名稱" required>
                </div>
                <button class="button" onclick="fetchData()">
                    <i class="fas fa-search"></i>
                    查詢所有資料
                </button>
            </div>
        </div>

        <div id="result">
            <!-- 查詢結果將在這裡顯示 -->
        </div>
    </div>

    <script>
        const columnNameMap = {
            professor_id: "教師編號",
            name: "姓名",
            bio: "簡介",
            extension: "分機",
            email: "電子郵件",
            type: "職稱",
            photo: "照片",
            website_url: "個人網站",
            retire: "狀態"
            // password 不需要加
        };

        function createTable(data) {
            if (!data || data.length === 0) {
                return `
                    <div class="main-card">
                        <div class="no-results">
                            <i class="fas fa-database"></i>
                            <div>資料表中沒有資料</div>
                        </div>
                    </div>
                `;
            }

            let tableHtml = `
                <div class="main-card">
                    <div class="section-title">
                        <i class="fas fa-table"></i>
                        查詢結果
                    </div>
                    <div class="table-container">
                        <table class="result-table">
                            <thead>
                                <tr>
            `;

            // 建立表頭
            Object.keys(data[0]).forEach(key => {
                if (key !== 'password') {
                    const displayName = columnNameMap[key] || key;
                    tableHtml += `<th>${displayName}</th>`;
                }
            });

            tableHtml += `
                                </tr>
                            </thead>
                            <tbody>
            `;

            // 加入資料行
            data.forEach(row => {
                tableHtml += '<tr>';
                Object.entries(row).forEach(([key, value]) => {
                    if (key !== 'password') {
                        if (key === 'bio') {
                            tableHtml += `<td><div class="bio-cell">${value || ''}</div></td>`;
                        } else if (key === 'retire') {
                            const status = value == 1 ? '退休' : '在職';
                            tableHtml += `<td>${status}</td>`;
                        } else {
                            tableHtml += `<td>${value || ''}</td>`;
                        }
                    }
                });
                tableHtml += '</tr>';
            });

            tableHtml += `
                            </tbody>
                        </table>
                    </div>
                    <div class="result-count">
                        <i class="fas fa-database"></i>
                        共找到 ${data.length} 筆資料
                    </div>
                </div>
            `;

            return tableHtml;
        }

        async function fetchData() {
            const tableName = document.getElementById('tableName').value;
            const resultDiv = document.getElementById('result');

            if (!tableName) {
                resultDiv.innerHTML = `
                    <div class="main-card">
                        <div class="error">
                            <i class="fas fa-exclamation-triangle"></i>
                            請填寫資料表名稱
                        </div>
                    </div>
                `;
                return;
            }

            try {
                // 使用 get_all/main.php 獲取所有資料
                const response = await fetch(`get_all/main.php?table=${encodeURIComponent(tableName)}`);
                const data = await response.json();

                if (data.status === 'success') {
                    resultDiv.innerHTML = createTable(data.data);
                } else {
                    resultDiv.innerHTML = `
                        <div class="main-card">
                            <div class="error">
                                <i class="fas fa-exclamation-triangle"></i>
                                錯誤：${data.message}
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="main-card">
                        <div class="error">
                            <i class="fas fa-exclamation-triangle"></i>
                            發生錯誤：${error.message}
                        </div>
                    </div>
                `;
            }
        }
    </script>
</body>

</html>