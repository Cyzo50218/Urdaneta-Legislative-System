<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('X-Frame-Options: DENY');
header('Cache-Control: no-cache, must-revalidate');
require_once __DIR__ . '/../connections/connection.php';

$input = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false];

try {
    if (empty($input['section_key'])) throw new Exception("Invalid Key");

    $stmt = $mysqli->prepare("DELETE FROM page_sections WHERE section_key = ?");
    $stmt->bind_param("s", $input['section_key']);
    
    if ($stmt->execute()) {
        $response['success'] = true;
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);