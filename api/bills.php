<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['bills'])) {
  $_SESSION['bills'] = [];
}

$action = $_POST['action'] ?? '';

if ($action === 'toggle_track') {
  $billId = $_POST['billId'] ?? '';
  foreach ($_SESSION['bills'] as &$bill) {
    if ($bill['id'] === $billId) {
      $bill['tracked'] = !$bill['tracked'];
      echo json_encode(['success' => true, 'tracked' => $bill['tracked']]);
      exit;
    }
  }
  echo json_encode(['success' => false, 'error' => 'Bill not found']);
  exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'error' => 'Unknown action']);
