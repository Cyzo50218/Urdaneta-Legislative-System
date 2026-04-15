<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['notifications'])) {
  $_SESSION['notifications'] = [];
}

$action = $_POST['action'] ?? '';

if ($action === 'mark_notification_read') {
  $index = (int)($_POST['index'] ?? -1);
  if (isset($_SESSION['notifications'][$index])) {
    $_SESSION['notifications'][$index]['read'] = true;
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'error' => 'Invalid index']);
  }
  exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'error' => 'Unknown action']);
