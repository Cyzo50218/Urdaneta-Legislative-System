<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: GET');
header('X-Frame-Options: DENY');
header('Cache-Control: no-cache, must-revalidate');

$response = [
    'success' => false,
    'data' => []
];

try {
    require_once __DIR__ . '/../connections/connection.php';

    if (!$mysqli) {
        throw new Exception("Database connection failed.");
    }

    $sql = "SELECT section_key, title, content, content_hash FROM page_sections ORDER BY id ASC";
    $result = $mysqli->query($sql);

    if ($result) {
        $sections = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $current_hash = hash('sha256', $row['content']);
                
                if ($current_hash !== $row['content_hash']) {
                    $row['content'] = "[SECURITY ALERT: This section content has been modified unauthorizedly.]";
                    $row['tampered'] = true;
                } else {
                    $row['tampered'] = false;
                }
                $sections[] = $row;
            }
        }
        $response['success'] = true;
        $response['data'] = $sections;
        $result->free();
    } else {
        throw new Exception("Query error: " . $mysqli->error);
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = $e->getMessage();
    http_response_code(500);
}
echo json_encode($response);
exit;