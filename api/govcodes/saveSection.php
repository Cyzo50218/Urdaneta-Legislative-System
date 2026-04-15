<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: POST');
header('X-Frame-Options: DENY');
header('Cache-Control: no-cache, must-revalidate');

$response = [
    'success' => false,
    'message' => ''
];

try {
    require_once __DIR__ . '/../connections/connection.php';

    if (!$mysqli) {
        throw new Exception("Database connection failed.");
    }

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || empty($input['title']) || empty($input['content'])) {
        throw new Exception("Title and Content are required.");
    }

    $title = trim($input['title']);
    $content = trim($input['content']);
    $section_key = 'sec-' . strtolower(preg_replace('/[^a-z0-9]/i', '-', substr($title, 0, 30))) . '-' . time();
    $content_hash = hash('sha256', $content);
    $sql = "INSERT INTO page_sections (section_key, title, content, content_hash) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        throw new Exception("Preparation failed: " . $mysqli->error);
    }

    $stmt->bind_param("ssss", $section_key, $title, $content, $content_hash);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Section saved successfully.";
    } else {
        throw new Exception("Execution failed: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
exit;