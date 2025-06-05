# Database_final_project
This is our final group project for the database course. We're going to build a website for the Department of Computer Science and Information Engineering at Feng Chia University using HTML and SQL.

### structure definitions
```
  /   index.html      首頁
  ├── api/            PHP-API
  │   ├── ....php     php
  ├── about.html      系所簡介
  ├── contact.html    聯絡我們
  ├── members/                        系所職位分類
  │   ├── pics/                       系所成員照片
  │   ├── index.html                  系所成員
  │   ├── chairman.html               系主任
  │   ├── honorary.html               榮譽特聘教授
  │   ├── chair.html                  講座教授
  │   ├── distinguished_chair.html    特約教授
  │   ├── distinguished.html          特聘教授
  │   ├── full_time.html              專任教授
  │   ├── part_time.html              兼任教授
  │   ├── staff.html                  行政人員
  │   ├── retired.html                退休教授
  │   └── faculties/    職員個人頁面
  │       └── faculty01.html
  ├── pics/                用到的圖片
  │   ├── fcu_logo.svg     系徽
  │   ├── iecs_logo.png    校徽
  │   └── posters/         首頁廣告海報
  │       ├── 1.jpg
  │       ├── 2.jpg
  │       └── 3.jpg
  ├── admin/          管理後台
  │   ├── index.html
```

### api definitions
- / 主頁
- about 系所簡介
- contact 聯絡我們
- members 系所成員
- chairman 系主任
- glory 榮譽特聘教授
- lectures 講座教授
- treaty 特約教授
- engage 特聘教授
- full_time 專任教授
- part_time 兼任教授
- retire 退休教授
- admin 行政人員

# 逢甲大學資訊工程學系網站專案

## 重要說明：路徑與 config.js 使用原則

### 1. 靜態資源路徑（CSS/JS/圖片）
- **HTML 標籤（如 `<link>`、`<script>`、`<img>`）的 `href` 和 `src` 屬性**，在 HTML 載入時**無法直接用 JS 變數（如 config.js 的 BASE_PATH）**。
- 請直接寫成絕對路徑，例如：
  ```html
  <link rel="stylesheet" href="/~D1210799/main.css">
  <script src="/~D1210799/js/config.js"></script>
  <img src="/~D1210799/pics/iecs_logo.png">
  ```
- 這樣不管網站部署在哪個子目錄都能正確載入。

### 2. config.js 的 BASE_PATH 只能在 JS 動態插入時使用
- 你**不能在 HTML 靜態標籤裡這樣寫**：
  ```html
  <link rel="stylesheet" href="BASE_PATH + '/main.css'">
  ```
- 但你可以在 JS 執行時動態插入：
  ```js
  const css = document.createElement('link');
  css.rel = 'stylesheet';
  css.href = BASE_PATH + '/main.css';
  document.head.appendChild(css);
  ```
- 圖片也可以用 JS 動態插入：
  ```js
  const img = document.createElement('img');
  img.src = BASE_PATH + '/pics/xxx.png';
  document.body.appendChild(img);
  ```

### 3. header.html/footer.html 內的路徑
- 請用 `/about`、`/contact`、`/pics/iecs_logo.png` 這種「以 `/` 開頭」的路徑
- 用 JS 載入 header/footer 後，呼叫 fixBasePathForLinksAndImages(container) 自動補上 BASE_PATH

### 4. 綜合建議
- 重要共用資源（CSS/JS/主圖）建議用絕對路徑 `/~D1210799/xxx`，這樣 SEO、快取、效能都比較好
- header/footer 內容用 JS 補 BASE_PATH

---

如需自動化動態插入 CSS/JS 或有其他路徑管理需求，請參考本專案的 JS 實作或聯絡維護者。
