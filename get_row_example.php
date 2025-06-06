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
    <title>單列資料查詢範例</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .result {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #666;
            text-decoration: none;
        }

        .back-link:hover {
            color: #333;
        }
    </style>
</head>

<body>
    <a href="home_page.php" class="back-link">← 返回主選單</a>
    <h1>單列資料查詢範例</h1>

    <div class="form-group">
        <label for="tableName">資料表名稱：</label>
        <input type="text" id="tableName" placeholder="請輸入資料表名稱">
    </div>

    <div class="form-group">
        <label for="columnName">欄位名稱：</label>
        <input type="text" id="columnName" placeholder="請輸入要查詢的欄位名稱">
    </div>

    <div class="form-group">
        <label for="value">查詢值：</label>
        <input type="text" id="value" placeholder="請輸入要查詢的值">
    </div>

    <button onclick="fetchData()">查詢</button>

    <div id="result" class="result"></div>

    <script>
        async function fetchData() {
            const tableName = document.getElementById('tableName').value;
            const columnName = document.getElementById('columnName').value;
            const value = document.getElementById('value').value;
            const resultDiv = document.getElementById('result');

            if (!tableName || !columnName || !value) {
                resultDiv.innerHTML = '<p class="error">請填寫所有必要欄位</p>';
                return;
            }

            try {
                // 先使用 get_all/main.php 獲取所有資料
                const response = await fetch(`get_all/main.php?table=${encodeURIComponent(tableName)}`);
                const data = await response.json();

                if (data.status === 'success') {
                    // 在獲取的資料中尋找符合條件的行
                    const filteredData = data.data.filter(row => row[columnName] === value);

                    if (filteredData.length === 0) {
                        resultDiv.innerHTML = '<p>未找到符合條件的資料</p>';
                        return;
                    }

                    let tableHtml = '<h3>查詢結果：</h3>';
                    tableHtml += '<table>';
                    tableHtml += '<tr><th>欄位</th><th>值</th></tr>';

                    for (const [key, value] of Object.entries(filteredData[0])) {
                        tableHtml += `<tr><td>${key}</td><td>${value}</td></tr>`;
                    }

                    tableHtml += '</table>';
                    resultDiv.innerHTML = tableHtml;
                } else {
                    resultDiv.innerHTML = `<p class="error">錯誤：${data.message}</p>`;
                }
            } catch (error) {
                resultDiv.innerHTML = `<p class="error">發生錯誤：${error.message}</p>`;
            }
        }
    </script>
</body>

</html>