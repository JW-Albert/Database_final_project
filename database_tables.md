# 資料庫表格結構

## 教授資料表 (Professor)
| 欄位名稱     | 資料類型     | 說明     | 是否必填  |
| ------------ | ------------ | -------- | --------- |
| professor_id | CHAR(5)      | 教授編號 | 是 (主鍵) |
| name         | VARCHAR(4)   | 姓名     | 是        |
| bio          | VARCHAR(500) | 簡介     | 否        |
| extension    | INT(4)       | 分機     | 否        |
| email        | VARCHAR(100) | 信箱     | 否        |
| password     | VARCHAR(50)  | 密碼     | 否        |
| type         | VARCHAR(10)  | 類型     | 否        |
| photo        | VARCHAR(300) | 圖片名稱 | 否        |
| website_url  | VARCHAR(300) | 個人網站 | 否        |

## 課程表 (Course)
| 欄位名稱     | 資料類型    | 說明     | 是否必填  |
| ------------ | ----------- | -------- | --------- |
| course_id    | CHAR(5)     | 序號     | 是 (主鍵) |
| professor_id | CHAR(5)     | 教授編號 | 是 (外鍵) |
| course_name  | VARCHAR(20) | 課程名稱 | 是        |
| class_name   | VARCHAR(4)  | 開課班級 | 否        |
| weekday      | INT(1)      | 星期     | 是        |
| period_start | INT(1)      | 節數開始 | 是        |
| period_end   | INT(1)      | 節數結束 | 是        |

## 系主任表 (DepartmentHead)
| 欄位名稱     | 資料類型 | 說明     | 是否必填  |
| ------------ | -------- | -------- | --------- |
| head_id      | CHAR(5)  | 序號     | 是 (主鍵) |
| professor_id | CHAR(5)  | 教授編號 | 是 (外鍵) |
| term         | INT(1)   | 屆數     | 是        |

## 學歷資料表 (Education)
| 欄位名稱     | 資料類型    | 說明     | 是否必填  |
| ------------ | ----------- | -------- | --------- |
| education_id | CHAR(5)     | 序號     | 是 (主鍵) |
| professor_id | CHAR(5)     | 教授編號 | 是 (外鍵) |
| date         | VARCHAR(50) | 時間     | 否        |
| school_name  | VARCHAR(50) | 校名     | 是        |
| degree       | VARCHAR(50) | 學位     | 是        |

## 專長資料表 (Expertise)
| 欄位名稱     | 資料類型    | 說明         | 是否必填  |
| ------------ | ----------- | ------------ | --------- |
| expertise_id | CHAR(5)     | 序號         | 是 (主鍵) |
| professor_id | CHAR(5)     | 教授編號     | 是 (外鍵) |
| name_cn      | VARCHAR(50) | 名稱（中文） | 否        |
| name_en      | VARCHAR(50) | 名稱（英文） | 是        |

## 校內經歷表 (InternalExperience)
| 欄位名稱          | 資料類型    | 說明     | 是否必填  |
| ----------------- | ----------- | -------- | --------- |
| experience_id     | CHAR(5)     | 序號     | 是 (主鍵) |
| professor_id      | CHAR(5)     | 教授編號 | 是 (外鍵) |
| position          | VARCHAR(50) | 職位     | 是        |
| date              | VARCHAR(50) | 時間     | 否        |
| organization_name | VARCHAR(50) | 單位名稱 | 否        |

## 校外經歷表 (ExternalExperience)
| 欄位名稱          | 資料類型    | 說明     | 是否必填  |
| ----------------- | ----------- | -------- | --------- |
| experience_id     | CHAR(5)     | 序號     | 是 (主鍵) |
| professor_id      | CHAR(5)     | 教授編號 | 是 (外鍵) |
| position          | VARCHAR(50) | 職位     | 是        |
| date              | VARCHAR(50) | 時間     | 否        |
| organization_name | VARCHAR(50) | 單位名稱 | 否        |

## 發表期刊論文表 (JournalPublication)
| 欄位名稱         | 資料類型     | 說明     | 是否必填  |
| ---------------- | ------------ | -------- | --------- |
| publication_id   | CHAR(50)     | 序號     | 是 (主鍵) |
| professor_id     | CHAR(5)      | 教授編號 | 是 (外鍵) |
| author           | VARCHAR(200) | 發表人   | 是        |
| doi              | VARCHAR(50)  | DOI      | 是        |
| paper_title      | VARCHAR(500) | 論文名稱 | 是        |
| journal_name     | VARCHAR(100) | 期刊名稱 | 是        |
| page_info        | VARCHAR(100) | 頁數     | 否        |
| publication_date | VARCHAR(50)  | 發表時間 | 否        |
| category         | VARCHAR(50)  | 類別     | 否        |

## 會議論文表 (ConferencePaper)
| 欄位名稱         | 資料類型     | 說明     | 是否必填  |
| ---------------- | ------------ | -------- | --------- |
| paper_id         | CHAR(5)      | 序號     | 是 (主鍵) |
| professor_id     | CHAR(5)      | 教授編號 | 是 (外鍵) |
| author           | VARCHAR(200) | 發表人   | 是        |
| paper_title      | VARCHAR(500) | 論文名稱 | 是        |
| conference_name  | VARCHAR(100) | 期刊名稱 | 是        |
| page_info        | VARCHAR(100) | 頁數     | 是        |
| publication_date | VARCHAR(50)  | 發表時間 | 否        |
| category         | VARCHAR(100) | 類別     | 否        |

## 專書及技術報告表 (BookAndReport)
| 欄位名稱             | 資料類型     | 說明     | 是否必填  |
| -------------------- | ------------ | -------- | --------- |
| report_id            | CHAR(5)      | 序號     | 是 (主鍵) |
| professor_id         | CHAR(5)      | 教授編號 | 是 (外鍵) |
| category             | VARCHAR(50)  | 類別     | 否        |
| author               | VARCHAR(200) | 發表人   | 是        |
| book_title           | VARCHAR(500) | 專書名稱 | 是        |
| publisher            | VARCHAR(100) | 出版社   | 是        |
| publication_location | VARCHAR(100) | 出版位置 | 否        |
| publication_date     | VARCHAR(100) | 時間     | 否        |

## 國科會計畫表 (NSCProject)
| 欄位名稱       | 資料類型     | 說明     | 是否必填  |
| -------------- | ------------ | -------- | --------- |
| project_id     | CHAR(5)      | 序號     | 是 (主鍵) |
| project_number | CHAR(50)     | 計畫編號 | 否        |
| professor_id   | CHAR(5)      | 教授編號 | 是 (外鍵) |
| topic          | VARCHAR(500) | 主題     | 是        |
| period         | VARCHAR(100) | 時間     | 是        |
| department     | VARCHAR(50)  | 部會     | 是        |

## 產學合作計畫表 (IndustryProject)
| 欄位名稱     | 資料類型     | 說明     | 是否必填  |
| ------------ | ------------ | -------- | --------- |
| project_id   | CHAR(5)      | 序號     | 是 (主鍵) |
| professor_id | CHAR(5)      | 教授編號 | 是 (外鍵) |
| topic        | VARCHAR(500) | 主題     | 是        |
| period       | VARCHAR(50)  | 時間     | 是        |
| leader       | VARCHAR(50)  | 主持人   | 是        |

## 校外獎勵及指導學生獲獎表 (ExternalAward)
| 欄位名稱     | 資料類型     | 說明     | 是否必填  |
| ------------ | ------------ | -------- | --------- |
| award_id     | CHAR(5)      | 序號     | 是 (主鍵) |
| professor_id | CHAR(5)      | 教授編號 | 是 (外鍵) |
| year         | INT(3)       | 年度     | 是        |
| award_name   | VARCHAR(500) | 獎項名稱 | 是        |
| organization | VARCHAR(200) | 單位     | 是        |
| award_date   | VARCHAR(100) | 時間     | 是        |
| topic        | VARCHAR(200) | 主題     | 是        |

## 校內獎勵及指導學生獲獎表 (InternalAward)
| 欄位名稱     | 資料類型     | 說明     | 是否必填  |
| ------------ | ------------ | -------- | --------- |
| award_id     | CHAR(5)      | 序號     | 是 (主鍵) |
| professor_id | CHAR(5)      | 教授編號 | 是 (外鍵) |
| year         | INT(5)       | 年度     | 是        |
| award_name   | VARCHAR(500) | 獎項名稱 | 是        |
| organization | VARCHAR(100) | 單位     | 是        |
| award_date   | VARCHAR(100) | 時間     | 是        |
| topic        | VARCHAR(200) | 主題     | 是        |

## 校內外演講表 (Lecture)
| 欄位名稱     | 資料類型     | 說明     | 是否必填  |
| ------------ | ------------ | -------- | --------- |
| lecture_id   | CHAR(5)      | 序號     | 是 (主鍵) |
| professor_id | CHAR(5)      | 教授編號 | 是 (外鍵) |
| topic        | VARCHAR(500) | 主題     | 是        |
| location     | VARCHAR(50)  | 地點     | 是        |
| date         | VARCHAR(100) | 時間     | 是        |

## 專書論文表 (BookPaper)
| 欄位名稱     | 資料類型     | 說明     | 是否必填  |
| ------------ | ------------ | -------- | --------- |
| paper_id     | CHAR(5)      | 序號     | 是 (主鍵) |
| professor_id | CHAR(5)      | 教授編號 | 是 (外鍵) |
| author       | VARCHAR(200) | 發表人   | 是        |
| title        | VARCHAR(500) | 標題     | 是        |
| journal_name | VARCHAR(300) | 期刊名稱 | 是        |
| publisher    | VARCHAR(100) | 出版社   | 是        |
| date         | VARCHAR(100) | 日期     | 否        |

## 核准專利表 (Patent)
| 欄位名稱      | 資料類型     | 說明     | 是否必填  |
| ------------- | ------------ | -------- | --------- |
| patent_id     | CHAR(5)      | 序號     | 是 (主鍵) |
| patent_number | CHAR(100)    | 專利編號 | 否        |
| professor_id  | CHAR(10)     | 教授編號 | 是 (外鍵) |
| name          | VARCHAR(500) | 名稱     | 是        |
| category      | VARCHAR(100) | 類別     | 是        |
| date          | VARCHAR(50)  | 日期     | 是        |

## 資訊系表 (CSDepartment)
| 欄位名稱        | 資料類型     | 說明 | 是否必填  |
| --------------- | ------------ | ---- | --------- |
| department_id   | CHAR(5)      | 代號 | 是 (主鍵) |
| phone_extension | INT(12)      | 電話 | 否        |
| email           | VARCHAR(100) | 信箱 | 否        |

## 系所介紹表 (Introduction)
| 欄位名稱 | 資料類型     | 說明     | 是否必填  |
| -------- | ------------ | -------- | --------- |
| id       | CHAR(5)      | 代號     | 是 (主鍵) |
| intro    | VARCHAR(500) | 文字介紹 | 否        |
| photo    | VARCHAR(300) | 圖片名稱 | 否        |

## 首頁廣告圖片表 (MainPageBannerPicture)
| 欄位名稱 | 資料類型     | 說明     | 是否必填  |
| -------- | ------------ | -------- | --------- |
| id       | CHAR(5)      | 代號     | 是 (主鍵) |
| photo    | VARCHAR(300) | 圖片名稱 | 否        |

## 行政人員表 (Staff)
| 欄位名稱  | 資料類型     | 說明     | 是否必填  |
| --------- | ------------ | -------- | --------- |
| staff_id  | CHAR(5)      | 人員編號 | 是 (主鍵) |
| name      | VARCHAR(20)  | 姓名     | 是        |
| position  | VARCHAR(20)  | 職位     | 否        |
| task_name | VARCHAR(50)  | 工作     | 是        |
| password  | VARCHAR(50)  | 密碼     | 否        |
| extension | INT(4)       | 分機     | 否        |
| email     | VARCHAR(100) | 信箱     | 否        |
| photo     | VARCHAR(300) | 圖片名稱 | 否        |

## 管理員表 (Admin)
| 欄位名稱 | 資料類型    | 說明 | 是否必填  |
| -------- | ----------- | ---- | --------- |
| id       | CHAR(5)     | 編號 | 是 (主鍵) |
| account  | VARCHAR(50) | 帳號 | 否        |
| password | VARCHAR(50) | 密碼 | 否        |