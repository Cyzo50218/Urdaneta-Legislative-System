<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: POST');

$response = ['success' => false, 'error' => ''];

try {
    require_once __DIR__ . '/../connections/connection.php';

    if (!$mysqli) { throw new Exception("Database connection failed."); }

    $mysqli->set_charset("utf8mb4");

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || empty($input['sections'])) { throw new Exception("No data received."); }

    $mysqli->begin_transaction();
    $sql = "INSERT INTO page_sections (section_key, title, content, content_hash) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);

    foreach ($input['sections'] as $section) {
        $title = trim($section['title']);
        $content = trim($section['content']);
        
        $section_key = 'sec-' . bin2hex(random_bytes(4)) . '-' . time();
        $content_hash = hash('sha256', $content);

        $stmt->bind_param("ssss", $section_key, $title, $content, $content_hash);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
    }

    $mysqli->commit();
    $response['success'] = true;
    $stmt->close();

} catch (Exception $e) {
    if (isset($mysqli)) { $mysqli->rollback(); }
    $response['success'] = false;
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
exit;