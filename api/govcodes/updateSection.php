<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('X-Frame-Options: DENY');
header('Cache-Control: no-cache, must-revalidate');

require_once __DIR__ . '/../connections/connection.php';

$input = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false];

try {
    // Debugging: If any specific field is missing, name it in the error
    if (empty($input['section_key'])) throw new Exception("Missing section_key");
    if (empty($input['title'])) throw new Exception("Missing title");
    if (empty($input['content'])) throw new Exception("Missing content");

    $title = $input['title'];
    $content = $input['content'];
    $section_key = $input['section_key'];

    $new_hash = hash('sha256', $content);

    $stmt = $mysqli->prepare("UPDATE page_sections SET title = ?, content = ?, content_hash = ? WHERE section_key = ?");
    $stmt->bind_param("ssss", $title, $content, $new_hash, $section_key);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows === 0) {
            throw new Exception("No changes made or Section Key not found.");
        }
        $response['success'] = true;
        $response['message'] = "Update successful";
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = $e->getMessage();
}

echo json_encode($response);