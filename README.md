# 資料庫操作系統

這是一個基於 PHP 和 MySQL 的資料庫操作系統，提供完整的資料庫 CRUD（新增、讀取、更新、刪除）功能。

## 專案結構

```
├── index.html              # 主選單頁面
├── add.html               # 新增資料頁面
├── delete.html            # 刪除資料頁面
├── update.html            # 修改資料頁面
├── query.html             # 查詢資料頁面
├── query_specific.html    # 特定查詢頁面
├── get_row_example.html   # 單列查詢範例頁面
├── get_col_example.html   # 欄位查詢範例頁面
├── get_all_example.html   # 完整查詢範例頁面
├── query_data_example.html # 排序查詢範例頁面
├── add_data/             # 新增資料相關 API
│   └── main.php          # 新增資料處理程式
├── delete_data/          # 刪除資料相關 API
│   └── main.php          # 刪除資料處理程式
├── update_data/          # 修改資料相關 API
│   └── main.php          # 修改資料處理程式
├── query_data/           # 查詢資料相關 API
│   └── main.php          # 查詢資料處理程式
├── get_ele/              # 獲取資料表結構 API
│   └── main.php          # 資料表結構處理程式
├── get_all/              # 完整資料表查詢 API
│   └── main.php          # 完整查詢處理程式
├── get_col/              # 欄位條件查詢 API
│   └── main.php          # 欄位查詢處理程式
├── get_row/              # 單列資料查詢 API
│   └── main.php          # 單列查詢處理程式
└── .git/                 # Git 版本控制目錄
```

## API 說明

### 1. 獲取資料表結構 (get_ele/main.php)

**功能**：獲取指定資料表的所有欄位資訊。

**請求方式**：GET

**參數**：
- `table`：資料表名稱

**回應格式**：
```json
{
    "status": "success",
    "data": {
        "columns": [
            {
                "name": "欄位名稱",
                "type": "資料類型",
                "null": "是否可為空",
                "default": "預設值",
                "extra": "額外資訊"
            }
        ]
    }
}
```

### 2. 新增資料 (add_data/main.php)

**功能**：向指定資料表新增一筆資料。

**請求方式**：POST

**參數**：
```json
{
    "table": "資料表名稱",
    "data": {
        "欄位1": "值1",
        "欄位2": "值2"
    }
}
```

**回應格式**：
```json
{
    "status": "success",
    "message": "新增成功"
}
```

### 3. 刪除資料 (delete_data/main.php)

**功能**：根據條件刪除資料表中的資料。

**請求方式**：POST

**參數**：
```json
{
    "table": "資料表名稱",
    "conditions": [
        {
            "column": "欄位名稱",
            "operator": "運算符",
            "value": "值"
        }
    ]
}
```

**回應格式**：
```json
{
    "status": "success",
    "affected_rows": "受影響的行數"
}
```

### 4. 修改資料 (update_data/main.php)

**功能**：根據條件更新資料表中的資料。

**請求方式**：POST

**參數**：
```json
{
    "table": "資料表名稱",
    "conditions": [
        {
            "column": "欄位名稱",
            "operator": "運算符",
            "value": "值"
        }
    ],
    "values": {
        "欄位1": "新值1",
        "欄位2": "新值2"
    }
}
```

**回應格式**：
```json
{
    "status": "success",
    "affected_rows": "受影響的行數"
}
```

### 5. 查詢資料 (query_data/main.php)

**功能**：根據條件查詢資料表中的資料。

**請求方式**：POST

**參數**：
```json
{
    "table": "資料表名稱",
    "conditions": [
        {
            "column": "欄位名稱",
            "operator": "運算符",
            "value": "值"
        }
    ],
    "sort": {
        "column": "排序欄位",
        "order": "排序方向"
    }
}
```

**回應格式**：
```json
{
    "status": "success",
    "data": [
        {
            "欄位1": "值1",
            "欄位2": "值2"
        }
    ]
}
```

### 6. 完整資料表查詢 (get_all/main.php)

**功能**：查詢指定資料表的所有資料。

**請求方式**：GET

**參數**：
- `table`：資料表名稱

**回應格式**：
```json
{
    "status": "success",
    "data": [
        {
            "欄位1": "值1",
            "欄位2": "值2"
        }
    ]
}
```

### 7. 欄位條件查詢 (get_col/main.php)

**功能**：根據指定欄位和搜尋條件查詢資料。

**請求方式**：GET

**參數**：
- `table`：資料表名稱
- `column`：欄位名稱
- `value`：搜尋值

**回應格式**：
```json
{
    "status": "success",
    "data": [
        {
            "欄位1": "值1",
            "欄位2": "值2"
        }
    ]
}
```

### 8. 單列資料查詢 (get_row/main.php)

**功能**：查詢符合特定欄位值的單一資料列。

**請求方式**：GET

**參數**：
- `table`：資料表名稱
- `column`：欄位名稱
- `value`：精確值

**回應格式**：
```json
{
    "status": "success",
    "data": {
        "欄位1": "值1",
        "欄位2": "值2"
    }
}
```

## HTML 頁面說明

### 1. 主選單 (index.html)

提供所有功能的入口，包括：
- 新增資料
- 刪除資料
- 修改資料
- 查詢資料
- 特定查詢

### 2. 新增資料 (add.html)

功能：
- 輸入資料表名稱
- 載入資料表欄位資訊
- 動態新增輸入欄位
- 顯示欄位資訊（必填、預設值等）
- 提交資料

### 3. 刪除資料 (delete.html)

功能：
- 輸入資料表名稱
- 載入資料表欄位資訊
- 動態新增刪除條件
- 支援多種運算符（=, !=, >, <, >=, <=, LIKE）
- 確認刪除操作

### 4. 修改資料 (update.html)

功能：
- 輸入資料表名稱
- 載入資料表欄位資訊
- 設定修改條件
- 設定更新值
- 支援多種運算符
- 確認更新操作

### 5. 查詢資料 (query.html)

功能：
- 輸入資料表名稱
- 載入資料表欄位資訊
- 動態新增查詢條件
- 支援多種運算符
- 顯示查詢結果
- 顯示結果數量

### 6. 特定查詢 (query_specific.html)

功能：
- 輸入資料表名稱
- 載入資料表欄位資訊
- 動態新增查詢條件
- 支援更多運算符（IN, NOT IN, BETWEEN）
- 支援多值輸入
- 顯示查詢結果
- 顯示結果數量

### 7. 範例頁面

#### 7.1 單列查詢範例 (get_row_example.html)
- 示範如何使用單列查詢 API
- 提供簡單的介面進行測試

#### 7.2 欄位查詢範例 (get_col_example.html)
- 示範如何使用欄位條件查詢 API
- 提供簡單的介面進行測試

#### 7.3 完整查詢範例 (get_all_example.html)
- 示範如何使用完整資料表查詢 API
- 提供簡單的介面進行測試

#### 7.4 排序查詢範例 (query_data_example.html)
- 示範如何使用排序功能
- 提供簡單的介面進行測試

## 使用說明

1. 確保已設定正確的資料庫連接資訊（在每個 PHP 檔案中）
2. 將所有檔案放在 Web 伺服器目錄下
3. 通過瀏覽器訪問 index.html
4. 選擇需要的功能進行操作

## 注意事項

1. 所有 API 都需要正確的資料庫連接設定
2. 資料表名稱必須符合資料庫命名規範
3. 運算符使用時需注意資料類型
4. 刪除和更新操作需要謹慎使用
5. 建議在正式環境中使用前進行充分測試
6. 範例頁面僅供測試和學習使用

## 開發團隊

- 逢甲大學資訊工程學系資料庫課程專案小組

## 授權說明

本專案僅供教育目的使用，未經授權不得用於商業用途。