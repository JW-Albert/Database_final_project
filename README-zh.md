# 逢甲大學資訊工程學系網站 -0.5.6

這是逢甲大學資訊工程學系的官方網站專案。

## 專案結構

```
public_html/
├── js/
│   └── config.js         # BASE_PATH 設定檔
├── members/
│   ├── chairman/         # 系主任資訊
│   ├── chair/           # 講座教授
│   ├── distinguished/    # 特聘教授
│   ├── distinguished_chair/  # 特約教授
│   ├── faculties/       # 教師詳細資料
│   ├── full_time/       # 專任教授
│   ├── honorary/        # 榮譽特聘教授
│   ├── part_time/       # 兼任教授
│   ├── retired/         # 退休教授
│   └── staff/           # 行政人員
├── pics/                # 圖片資源
└── index.html          # 首頁
```

## 重要設定

### BASE_PATH 設定

網站使用 `js/config.js` 中定義的 `BASE_PATH` 變數來處理不同部署環境。這對於以下方面至關重要：

1. 正確生成所有內部連結的 URL
2. 正確載入靜態資源（圖片、CSS、JavaScript）
3. 在不同環境中保持一致的導航

`js/config.js` 中的設定範例：
```javascript
const BASE_PATH = '/~D1210799';  // 開發環境
// const BASE_PATH = '';         // 正式環境
```

### 路徑修改注意事項

當部署到不同環境時，需要：

1. 更新 `js/config.js` 中的 `BASE_PATH`
2. 修改 HTML 檔案中的圖片路徑：
   - 教師照片：`pics/professors/`
   - 系所標誌：`pics/iecs_logo.png`
   - 其他靜態資源

### 路徑使用原則

1. **靜態資源路徑（CSS/JS/圖片）**
   - HTML 標籤（如 `<link>`、`<script>`、`<img>`）的 `href` 和 `src` 屬性
   - 請使用絕對路徑，例如：
     ```html
     <link rel="stylesheet" href="/~D1210799/main.css">
     <script src="/~D1210799/js/config.js"></script>
     <img src="/~D1210799/pics/iecs_logo.png">
     ```

2. **動態插入的內容**
   - 使用 JavaScript 動態插入的內容可以使用 `BASE_PATH`
   - 例如：
     ```javascript
     const img = document.createElement('img');
     img.src = BASE_PATH + '/pics/xxx.png';
     document.body.appendChild(img);
     ```

3. **header.html/footer.html 中的路徑**
   - 使用以 `/` 開頭的絕對路徑
   - 載入後使用 `fixBasePathForLinksAndImages(container)` 自動補上 `BASE_PATH`

## 功能特色

- 響應式設計，支援各種裝置
- 動態載入教師資訊
- 詳細的教師個人資料，包含：
  - 基本資訊
  - 課程資訊
  - 學歷
  - 專長領域
  - 經歷
  - 發表論文
  - 研究計畫
  - 獲獎紀錄
  - 演講記錄

## 技術架構

- HTML5
- CSS3
- JavaScript (ES6+)
- PHP (API 端點)
- MySQL (資料庫)

## 安裝說明

1. 複製專案
2. 設定網頁伺服器
3. 設定資料庫
4. 更新 `js/config.js` 中的 `BASE_PATH`
5. 更新 HTML 檔案中的圖片路徑
6. 部署到網頁伺服器

## 貢獻指南

提交 Pull Request 前請先閱讀貢獻指南。

## 授權條款

本專案採用 MIT 授權條款 - 詳見 LICENSE 檔案。 
