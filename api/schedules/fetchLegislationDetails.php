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
                bill_number AS number, 
                type, 
                chamber, 
                title, 
                authors, 
                status, 
                date_filed AS filed, 
                last_action, 
                summary 
            FROM legislation_details 
            ORDER BY date_filed DESC";

    $result = $mysqli->query($sql);

    if ($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $row['authors'] = array_map('trim', explode(',', $row['authors']));
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
    http_response_code(500);
}

echo json_encode($response);
$mysqli->close();
