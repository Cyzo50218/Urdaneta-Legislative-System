<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: GET');
header('X-Frame-Options: DENY');
header('Cache-Control: public, max-age=300');
require_once __DIR__ . '/../connections/connection.php';
require_once __DIR__ . '/../helpers/errorHelper.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = [
    'success' => false,
    'data' => []
];

try {
    $sql = "SELECT 
                resolution_id AS id, 
                title, 
                description, 
                assigned_date AS assigned, 
                status 
            FROM resolutions 
            WHERE assigned_to = ? 
            ORDER BY assigned_date DESC";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $mysqli->error);
    }

    $userId = $_SESSION['user_id'] ?? null; // Get user ID from session
    if ($userId === null) {
        throw new Exception("User not authenticated");
    }

    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $response['success'] = true;
        $response['data'] = $data;
        $result->free();
    } else {
        throw new Exception("Query failed: " . $mysqli->error);
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    logError($mysqli, $e->getMessage());
    http_response_code(500);
}

echo json_encode($response);
