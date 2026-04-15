<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: POST');
header('X-Frame-Options: DENY');
header('Cache-Control: no-cache');

require_once __DIR__ . '/errorHelper.php';
require_once __DIR__ . '/../connections/connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = ['success' => false];

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $errorMessage = $input['error'] ?? null;
    $scriptName = $input['script'] ?? 'frontend';

    if (!$errorMessage) {
        throw new Exception('No error message provided');
    }
    logError($mysqli, $errorMessage, $scriptName);

    $response['success'] = true;
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    http_response_code(500);
}

echo json_encode($response);
