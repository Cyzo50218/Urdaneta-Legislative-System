<?php
session_start();
require_once '../connections/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT first_name, last_name, email, phone_number, region, province, city, barangay FROM tbl_users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode([
            'success' => true,
            'data' => $user
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} catch (PDOException $e) {
    // Log error internally for debugging
    error_log("Profile Fetch Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error']);
}