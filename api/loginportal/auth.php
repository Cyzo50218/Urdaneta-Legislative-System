<?php
session_start();
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

function sanitize_role($role) {
  $allowed = ['staff','councilor','mayor'];
  return in_array($role, $allowed, true) ? $role : 'staff';
}

switch ($action) {
  case 'login':
    $role = sanitize_role($_POST['loginType'] ?? 'staff');
    $_SESSION['user'] = [
      'name'  => explode('@', $_POST['email'])[0],
      'email' => $_POST['email'],
      'role'  => $role,
    ];
    echo json_encode(['success' => true, 'user' => $_SESSION['user']]);
    break;

  case 'register':
    $role = sanitize_role($_POST['registerType'] ?? 'staff');
    $_SESSION['user'] = [
      'name'  => trim(($_POST['firstName'] ?? '') . ' ' . ($_POST['lastName'] ?? '')),
      'email' => $_POST['email'],
      'role'  => $role,
    ];
    echo json_encode(['success' => true, 'user' => $_SESSION['user']]);
    break;

  case 'logout':
    unset($_SESSION['user']);
    echo json_encode(['success' => true]);
    break;

  default:
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Unknown action']);
}
