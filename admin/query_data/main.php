<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=utf-8');

// 取得POST資料
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// 檢查必要參數
if (!isset($data['data']) || !is_array($data['data'])) {
    echo json_encode([
        'status' => 'error',
        'message' => '缺少資料參數或資料格式不正確'
    ]);
    exit;
}

// 檢查排序參數
if (!isset($data['sort_column']) || !isset($data['sort_order'])) {
    echo json_encode([
        'status' => 'error',
        'message' => '缺少排序參數'
    ]);
    exit;
}

$dataArray = $data['data'];
$sortColumn = $data['sort_column'];
$sortOrder = strtoupper($data['sort_order']); // 轉換為大寫

// 驗證排序方向
if ($sortOrder !== 'ASC' && $sortOrder !== 'DESC') {
    echo json_encode([
        'status' => 'error',
        'message' => '排序方向必須是 ASC 或 DESC'
    ]);
    exit;
}

// 驗證排序欄位是否存在於資料中
if (!empty($dataArray) && !isset($dataArray[0][$sortColumn])) {
    echo json_encode([
        'status' => 'error',
        'message' => '指定的排序欄位不存在於資料中'
    ]);
    exit;
}

try {
    // 使用 usort 進行排序
    usort($dataArray, function($a, $b) use ($sortColumn, $sortOrder) {
        $valueA = $a[$sortColumn];
        $valueB = $b[$sortColumn];

        // 處理 NULL 值
        if ($valueA === null && $valueB === null) return 0;
        if ($valueA === null) return ($sortOrder === 'ASC') ? -1 : 1;
        if ($valueB === null) return ($sortOrder === 'ASC') ? 1 : -1;

        // 處理數字
        if (is_numeric($valueA) && is_numeric($valueB)) {
            return ($sortOrder === 'ASC') ? 
                $valueA <=> $valueB : 
                $valueB <=> $valueA;
        }

        // 處理字串
        return ($sortOrder === 'ASC') ? 
            strcmp($valueA, $valueB) : 
            strcmp($valueB, $valueA);
    });

    // 回傳排序後的結果
    echo json_encode([
        'status' => 'success',
        'data' => $dataArray,
        'sort_info' => [
            'column' => $sortColumn,
            'order' => $sortOrder,
            'total_rows' => count($dataArray)
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => '排序過程發生錯誤：' . $e->getMessage()
    ]);
}
?> 