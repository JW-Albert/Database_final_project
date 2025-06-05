# 資料庫結構

## 教授資料表
```sql
CREATE TABLE Professor (
    professor_id CHAR(5) COMMENT '教授編號',
    name VARCHAR(4) NOT NULL COMMENT '姓名',
    bio VARCHAR(500) COMMENT '簡介',
    extension INT(4) COMMENT '分機',
    email VARCHAR(100) COMMENT '信箱',
    password VARCHAR(50) COMMENT '密碼',
    type VARCHAR(10) COMMENT '類型',
    photo_url VARCHAR(300) COMMENT '圖片連結',
    website_url VARCHAR(300) COMMENT '個人網站',
    PRIMARY KEY(professor_id)
) COMMENT='教授資料表';
```

## 課程表
```sql
CREATE TABLE Course (
    course_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    course_name VARCHAR(20) NOT NULL COMMENT '課程名稱',
    class_name VARCHAR(4) NULL COMMENT '開課班級',
    weekday INT(1) NOT NULL COMMENT '星期',
    period_start INT(1) NOT NULL COMMENT '節數開始',
    period_end INT(1) NOT NULL COMMENT '節數結束',
    PRIMARY KEY(course_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
) COMMENT='課程表';
```

## 系主任表
```sql
CREATE TABLE DepartmentHead (
    head_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    term INT(1) NOT NULL COMMENT '屆數',
    PRIMARY KEY(head_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
) COMMENT='系主任表';
```

## 學歷資料表
```sql
CREATE TABLE Education (
    education_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    date VARCHAR(50) NULL COMMENT '時間',
    school_name VARCHAR(50) NOT NULL COMMENT '校名',
    degree VARCHAR(50) NOT NULL COMMENT '學位',
    PRIMARY KEY(education_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='學歷資料表'
```

## 專長資料表
```sql
CREATE TABLE Expertise (
    expertise_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    name_cn VARCHAR(50) NULL COMMENT '名稱（中文）',
    name_en VARCHAR(50) NOT NULL COMMENT '名稱（英文）',
    PRIMARY KEY(expertise_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='專長資料表';
```

## 校內經歷表
```sql
CREATE TABLE InternalExperience (
    experience_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    position VARCHAR(50) NOT NULL COMMENT '職位',
    date VARCHAR(50) NULL COMMENT '時間',
    organization_name VARCHAR(50) NULL COMMENT '單位名稱',
    PRIMARY KEY(experience_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='校內經歷表';
```

## 校外經歷表
```sql
CREATE TABLE ExternalExperience (
    experience_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    position VARCHAR(50) NOT NULL COMMENT '職位',
    date VARCHAR(50) NULL COMMENT '時間',
    organization_name VARCHAR(50) NULL COMMENT '單位名稱',
    PRIMARY KEY(experience_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='校外經歷表';
```

## 發表期刊論文表
```sql
CREATE TABLE JournalPublication (
    publication_id CHAR(50) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    author VARCHAR(200) NOT NULL COMMENT '發表人',
    doi VARCHAR(50) NOT NULL,
    paper_title VARCHAR(500) NOT NULL COMMENT '論文名稱',
    journal_name VARCHAR(100) NOT NULL COMMENT '期刊名稱',
    page_info VARCHAR(100) COMMENT '頁數',
    publication_date VARCHAR(50) NULL COMMENT '發表時間',
    category VARCHAR(50) NULL COMMENT '類別',
    PRIMARY KEY(publication_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='發表期刊論文表';
```

## 會議論文表
```sql
CREATE TABLE ConferencePaper (
    paper_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    author VARCHAR(200) NOT NULL COMMENT '發表人',
    paper_title VARCHAR(500) NOT NULL COMMENT '論文名稱',
    conference_name VARCHAR(100) NOT NULL COMMENT '期刊名稱',
    page_info VARCHAR(100) NOT NULL COMMENT '頁數',
    publication_date VARCHAR(50) NULL COMMENT '發表時間',
    category VARCHAR(100) NULL COMMENT '類別',
    PRIMARY KEY(paper_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='會議論文表';
```

## 專書及技術報告表
```sql
CREATE TABLE BookAndReport (
    report_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    category VARCHAR(50) NULL COMMENT '類別',
    author VARCHAR(200) NOT NULL COMMENT '發表人',
    book_title VARCHAR(500) NOT NULL COMMENT '專書名稱',
    publisher VARCHAR(100) NOT NULL COMMENT '出版社',
    publication_location VARCHAR(100) NULL COMMENT '出版位置',
    publication_date VARCHAR(100) NULL COMMENT '時間',
    PRIMARY KEY(report_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='專書及技術報告表';
```

## 國科會計畫表
```sql
CREATE TABLE NSCProject (
    project_id CHAR(5) COMMENT '序號',
    project_number CHAR(50) COMMENT '計畫編號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    topic VARCHAR(500) NOT NULL COMMENT '主題',
    period VARCHAR(100) NOT NULL COMMENT '時間',
    department VARCHAR(50) NOT NULL COMMENT '部會',
    PRIMARY KEY(project_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='國科會計畫表';
```

## 產學合作計畫表
```sql
CREATE TABLE IndustryProject (
    project_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    topic VARCHAR(500) NOT NULL COMMENT '主題',
    period VARCHAR(50) NOT NULL COMMENT '時間',
    leader VARCHAR(50) NOT NULL COMMENT '主持人',
    PRIMARY KEY(project_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='產學合作計畫表';
```

## 校外獎勵及指導學生獲獎表
```sql
CREATE TABLE ExternalAward (
    award_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    year INT(3) NOT NULL COMMENT '年度',
    award_name VARCHAR(500) NOT NULL COMMENT '獎項名稱',
    organization VARCHAR(200) NOT NULL COMMENT '單位',
    award_date VARCHAR(100) NOT NULL COMMENT '時間',
    topic VARCHAR(200) NOT NULL COMMENT '主題',
    PRIMARY KEY(award_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='校外獎勵及指導學生獲獎表';
```

## 校內獎勵及指導學生獲獎表
```sql
CREATE TABLE InternalAward (
    award_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    year INT(5) NOT NULL COMMENT '年度',
    award_name VARCHAR(500) NOT NULL COMMENT '獎項名稱',
    organization VARCHAR(100) NOT NULL COMMENT '單位',
    award_date VARCHAR(100) NOT NULL COMMENT '時間',
    topic VARCHAR(200) NOT NULL COMMENT '主題',
    PRIMARY KEY(award_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='校內獎勵及指導學生獲獎表';
```

## 校內外演講表
```sql
CREATE TABLE Lecture (
    lecture_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    topic VARCHAR(500) NOT NULL COMMENT '主題',
    location VARCHAR(50) NOT NULL COMMENT '地點',
    date VARCHAR(100) NOT NULL COMMENT '時間',
    PRIMARY KEY(lecture_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='校內外演講表';
```

## 專書論文表
```sql
CREATE TABLE BookPaper (
    paper_id CHAR(5) COMMENT '序號',
    professor_id CHAR(5) NOT NULL COMMENT '教授編號',
    author VARCHAR(200) NOT NULL COMMENT '發表人',
    title VARCHAR(500) NOT NULL COMMENT '標題',
    journal_name VARCHAR(300) NOT NULL COMMENT '期刊名稱',
    publisher VARCHAR(100) NOT NULL COMMENT '出版社',
    date VARCHAR(100) NULL COMMENT '日期',
    PRIMARY KEY(paper_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='專書論文表';
```

## 核准專利表
```sql
CREATE TABLE Patent (
    patent_id CHAR(5) COMMENT '序號',
    patent_number CHAR(100) COMMENT '專利編號',
    professor_id CHAR(10) NOT NULL COMMENT '教授編號',
    name VARCHAR(500) NOT NULL COMMENT '名稱',
    category VARCHAR(100) NOT NULL COMMENT '類別',
    date VARCHAR(50) NOT NULL COMMENT '日期',
    PRIMARY KEY(patent_id),
    FOREIGN KEY(professor_id) REFERENCES Professor(professor_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) COMMENT='核准專利表';
```

## 資訊系表
```sql
CREATE TABLE CSDepartment (
    department_id CHAR(5) COMMENT '代號',
    phone_extension INT(12) COMMENT '電話',
    email VARCHAR(100) COMMENT '信箱',
    PRIMARY KEY(department_id)
) COMMENT='資訊系表';
```

## 系所介紹表
```sql
CREATE TABLE Introduction (
    id CHAR(5) COMMENT '代號',
    intro VARCHAR(500) COMMENT '文字介紹',
    photo VARCHAR(300) COMMENT '圖片名稱',
    PRIMARY KEY(id)
) COMMENT='系所介紹表';
```

## 首頁廣告圖片表
```sql
CREATE TABLE MainPageBannerPicture (
    id CHAR(5) COMMENT '代號',
    photo VARCHAR(300) COMMENT '圖片名稱',
    PRIMARY KEY(id)
) COMMENT='首頁廣告圖片表';
```

## 行政人員表
```sql
CREATE TABLE Staff (
    staff_id CHAR(5) COMMENT '人員編號',
    name VARCHAR(20) NOT NULL COMMENT '姓名',
    position VARCHAR(20) COMMENT '職位',
    task_name VARCHAR(50) NOT NULL COMMENT '工作',
    password VARCHAR(50) COMMENT '密碼',
    extension INT(4) COMMENT '分機',
    email VARCHAR(100) COMMENT '信箱',
    photo VARCHAR(300) COMMENT '圖片名稱',
    PRIMARY KEY(staff_id)
) COMMENT='行政人員表';
```

## 管理員表
```sql
CREATE TABLE Admin (
    id CHAR(5) COMMENT '編號',
    account VARCHAR(50) COMMENT '帳號',
    password VARCHAR(50) COMMENT '密碼'
) COMMENT='管理員表';
```