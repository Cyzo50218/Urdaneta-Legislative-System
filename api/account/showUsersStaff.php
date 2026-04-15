<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once __DIR__ . '/../connections/connection.php';

try {
    $query = "SELECT user_id, 
                     CONCAT(first_name, ' ', last_name) AS full_name 
              FROM tbl_users 
              WHERE LOWER(role) = 'staff'";
              
    $stmt = $mysqli->prepare($query);
    
    if (!$stmt) {
        throw new Exception($mysqli->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    
    $staff = [];
    while ($row = $result->fetch_assoc()) {
        $staff[] = $row;
    }
    
    echo json_encode(['success' => true, 'staff' => $staff]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    $mysqli->close();
}