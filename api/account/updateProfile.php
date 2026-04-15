<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
header('Cache-Control: no-store, no-cache, must-revalidate');

require_once __DIR__ . '/../connections/connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

try {
    $firstName = trim(filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS));
    $lastName  = trim(filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS));
    $phone     = trim(filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_SPECIAL_CHARS));

    if (empty($firstName) || empty($lastName)) {
        throw new Exception("First and Last names are required.");
    }
    if (!empty($phone) && !preg_match('/^09\d{9}$/', $phone)) {
        throw new Exception("Invalid phone number format. Use 09123456789.");
    }
    $sql = "UPDATE tbl_users 
            SET first_name = :fname, 
                last_name = :lname, 
                phone_number = :phone 
            WHERE user_id = :uid";
            
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        ':fname' => $firstName,
        ':lname' => $lastName,
        ':phone' => $phone,
        ':uid'   => $userId
    ]);

    if ($result) {
        $_SESSION['full_name'] = $firstName . ' ' . $lastName;
        
        echo json_encode([
            'success' => true, 
            'message' => 'Profile updated successfully.'
        ]);
    } else {
        throw new Exception("No changes were made.");
    }

} catch (Exception $e) {
    error_log("Security Update Error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}