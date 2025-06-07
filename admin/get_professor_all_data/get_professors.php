<?php
session_start();
if (!isset($_SESSION['logged_in'])) { http_response_code(403); exit; }
require_once '../db_config/main.php';

$stmt = $pdo->query("SELECT professor_id, name FROM Professor");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($data);