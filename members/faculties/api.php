<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../../php/config.php';

try {
    $pdo = getDBConnection();
    
    // 檢查是否有傳入教授編號
    $professor_id = isset($_GET['professor_id']) ? $_GET['professor_id'] : null;
    
    if (!$professor_id) {
        throw new Exception('未指定教授編號');
    }

    // 查詢教授基本資料
    $stmt = $pdo->prepare("SELECT * FROM Professor WHERE professor_id = ?");
    $stmt->execute([$professor_id]);
    $professor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$professor) {
        throw new Exception('找不到該教授資料');
    }

    // 查詢課程資料
    $stmt = $pdo->prepare("SELECT * FROM Course WHERE professor_id = ?");
    $stmt->execute([$professor_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢學歷資料
    $stmt = $pdo->prepare("SELECT * FROM Education WHERE professor_id = ? ORDER BY date DESC");
    $stmt->execute([$professor_id]);
    $education = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢專長資料
    $stmt = $pdo->prepare("SELECT * FROM Expertise WHERE professor_id = ?");
    $stmt->execute([$professor_id]);
    $expertise = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢校內經歷
    $stmt = $pdo->prepare("SELECT * FROM InternalExperience WHERE professor_id = ? ORDER BY date DESC");
    $stmt->execute([$professor_id]);
    $internal_experience = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢校外經歷
    $stmt = $pdo->prepare("SELECT * FROM ExternalExperience WHERE professor_id = ? ORDER BY date DESC");
    $stmt->execute([$professor_id]);
    $external_experience = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢期刊論文
    $stmt = $pdo->prepare("SELECT * FROM JournalPublication WHERE professor_id = ? ORDER BY publication_date DESC");
    $stmt->execute([$professor_id]);
    $journal_publications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢會議論文
    $stmt = $pdo->prepare("SELECT * FROM ConferencePaper WHERE professor_id = ? ORDER BY publication_date DESC");
    $stmt->execute([$professor_id]);
    $conference_papers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢專書及技術報告
    $stmt = $pdo->prepare("SELECT * FROM BookAndReport WHERE professor_id = ? ORDER BY publication_date DESC");
    $stmt->execute([$professor_id]);
    $book_reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢專書論文
    $stmt = $pdo->prepare("SELECT * FROM BookPaper WHERE professor_id = ? ORDER BY date DESC");
    $stmt->execute([$professor_id]);
    $book_papers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢專利
    $stmt = $pdo->prepare("SELECT * FROM Patent WHERE professor_id = ? ORDER BY date DESC");
    $stmt->execute([$professor_id]);
    $patents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢國科會計畫
    $stmt = $pdo->prepare("SELECT * FROM NSCProject WHERE professor_id = ? ORDER BY period DESC");
    $stmt->execute([$professor_id]);
    $nsc_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢產學合作計畫
    $stmt = $pdo->prepare("SELECT * FROM IndustryProject WHERE professor_id = ? ORDER BY period DESC");
    $stmt->execute([$professor_id]);
    $industry_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢校內獎勵
    $stmt = $pdo->prepare("SELECT * FROM InternalAward WHERE professor_id = ? ORDER BY award_date DESC");
    $stmt->execute([$professor_id]);
    $internal_awards = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢校外獎勵
    $stmt = $pdo->prepare("SELECT * FROM ExternalAward WHERE professor_id = ? ORDER BY award_date DESC");
    $stmt->execute([$professor_id]);
    $external_awards = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 查詢演講
    $stmt = $pdo->prepare("SELECT * FROM Lecture WHERE professor_id = ? ORDER BY date DESC");
    $stmt->execute([$professor_id]);
    $lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'professor' => $professor,
            'courses' => $courses,
            'education' => $education,
            'expertise' => $expertise,
            'internal_experience' => $internal_experience,
            'external_experience' => $external_experience,
            'journal_publications' => $journal_publications,
            'conference_papers' => $conference_papers,
            'book_reports' => $book_reports,
            'book_papers' => $book_papers,
            'patents' => $patents,
            'nsc_projects' => $nsc_projects,
            'industry_projects' => $industry_projects,
            'internal_awards' => $internal_awards,
            'external_awards' => $external_awards,
            'lectures' => $lectures
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
 