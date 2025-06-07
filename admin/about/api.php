<?php
header('Content-Type: application/json');
require_once '../php/config.php';
$conn = getDBConnection();

$stmt = $conn->prepare("SELECT * FROM Introduction ORDER BY id ASC");
$stmt->execute();
$paragraphs = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($paragraphs);
?> 