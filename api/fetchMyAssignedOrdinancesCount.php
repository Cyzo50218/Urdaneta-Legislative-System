<?php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Access-Control-Allow-Origin: *'); 
header('Cache-Control: private, no-cache, no-store, must-revalidate');

$connPath = __DIR__ . '/connections/connection.php';
if (!file_exists($connPath)) {
    echo json_encode(['success' => false, 'error' => 'Connection file missing']);
    exit;
}
require_once $connPath;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = ['success' => false, 'count' => 0];

try {
    if (!isset($mysqli) || $mysqli->connect_error) {
        throw new Exception("Database connection failed");
    }

    $userId = $_SESSION['user_id'] ?? null;
    $userRole = strtolower($_SESSION['role'] ?? '');

    if (!$userId) {
        http_response_code(401);
        throw new Exception("Unauthorized access");
    }

    $isStaff = ($userRole === 'staff');
    
    $sqlCount = "
        SELECT COUNT(DISTINCT trackingNo) as total FROM (
            SELECT CAST(resolution_code AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS trackingNo 
            FROM resolutions " . ($isStaff ? "WHERE assigned_to = ?" : "") . "
            
            UNION ALL
            
            SELECT CAST(ordinance_code AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS trackingNo 
            FROM ordinances " . ($isStaff ? "WHERE assigned_to = ?" : "") . "
            
            UNION ALL
            
            SELECT CAST(bill_number AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS trackingNo 
            FROM legislation_details " . ($isStaff ? "WHERE 1=0" : "") . " 
        ) AS count_table";

    $stmt = $mysqli->prepare($sqlCount);

    if (!$stmt) {
        throw new Exception("SQL Prepare Failed: " . $mysqli->error);
    }

    if ($isStaff) {
        $stmt->bind_param('ss', $userId, $userId);
    }

    if (!$stmt->execute()) {
        throw new Exception("Execution failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $res = $result->fetch_assoc();
    
    $response['success'] = true;
    $response['count'] = (int)($res['total'] ?? 0);

} catch (Exception $e) {
    error_log("Profile Count Error: " . $e->getMessage());
    $response['error'] = "Unable to retrieve count."; 
    $response['debug'] = $e->getMessage(); 
}

echo json_encode($response);
exit;