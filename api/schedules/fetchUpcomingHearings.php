<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: GET');
header('X-Frame-Options: DENY');
header('Cache-Control: public, max-age=300');

require_once __DIR__ . '/../connections/connection.php';

$response = [
    'success' => false,
    'data' => 0 
];

try {
    $today = date('Y-m-d');
    $sql = "SELECT COUNT(*) AS hearing_count 
            FROM legislative_schedules 
            WHERE schedule_date >= ?";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $mysqli->error);
    }

    $stmt->bind_param('s', $today);
    $stmt->execute();
    $stmt->bind_result($hearingCount);
    $stmt->fetch();

    $response['success'] = true;
    $response['data'] = $hearingCount;

    $stmt->close();
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    http_response_code(500);
}

echo json_encode($response);
$mysqli->close();
