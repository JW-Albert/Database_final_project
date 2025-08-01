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
    <title>資料庫管理系統</title>
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
            text-align: center;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .header p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 30px;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: white;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .card-description {
            color: #7f8c8d;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .stats {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-title {
            color: #2c3e50;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 12px;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 600;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-database"></i> 資料庫管理系統</h1>
            <p>現代化的數據管理平台，提供完整的CRUD操作功能</p>
        </div>

        <div class="dashboard">
            <a href="add.php" class="card">
                <div class="card-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="card-title">新增資料</div>
                <div class="card-description">添加新的記錄到資料庫中，支援多種數據格式輸入</div>
            </a>

            <a href="delete.php" class="card">
                <div class="card-icon">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <div class="card-title">刪除資料</div>
                <div class="card-description">安全地移除不需要的記錄，支援批量刪除操作</div>
            </a>

            <a href="update.php" class="card">
                <div class="card-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="card-title">修改資料</div>
                <div class="card-description">更新現有記錄的內容，保持數據的準確性</div>
            </a>

            <a href="query.php" class="card">
                <div class="card-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div class="card-title">查詢資料</div>
                <div class="card-description">快速搜尋和瀏覽所有數據記錄</div>
            </a>

            <a href="query_specific.php" class="card">
                <div class="card-icon">
                    <i class="fas fa-filter"></i>
                </div>
                <div class="card-title">查詢特定資料</div>
                <div class="card-description">使用條件篩選查找特定的數據記錄</div>
            </a>

            <a href="upload_photo.php" class="card">
                <div class="card-icon">
                    <i class="fas fa-upload"></i>
                </div>
                <div class="card-title">上傳照片</div>
                <div class="card-description">管理和上傳圖片文件到系統中</div>
            </a>
        </div>
    </div>
</body>

</html>