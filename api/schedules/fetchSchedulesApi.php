<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: GET');
header('X-Frame-Options: DENY');
header('Cache-Control: public, max-age=300');
require_once __DIR__ . '/../connections/connection.php';
$response = [
    'success' => false,
    'data' => []
];

try {

    $sql = "SELECT 
            id,
            event_id, 
            schedule_date AS date, 
            TIME_FORMAT(schedule_time, '%H:%i') AS time, 
            title, 
                type, 
                chamber, 
                committee, 
                location, 
                city 
        FROM legislative_schedules 
        ORDER BY schedule_date ASC, schedule_time ASC";

    $result = $mysqli->query($sql);

    if ($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $response['success'] = true;
        $response['data'] = $data;
        $result->free();
    } else {
        throw new Exception("Database query failed: " . $mysqli->error);
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    http_response_code(500);
}
echo json_encode($response);
$mysqli->close();
