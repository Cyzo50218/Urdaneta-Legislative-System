<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: GET');
header('X-Frame-Options: DENY');
header('Cache-Control: public, max-age=60'); 

require_once __DIR__ . '/connections/connection.php';
require_once __DIR__ . '/helpers/errorHelper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = [
    'success' => false,
    'data' => [
        'assigned_resolutions' => 0,
        'assigned_ordinances'  => 0,
        'tasks_pending'        => 0,
        'upcoming_hearings'    => 0
    ]
];

try {
    $userId = $_SESSION['user_id'] ?? null;
    $userRole = strtolower($_SESSION['role'] ?? '');

    if (!$userId) {
        throw new Exception("User not authenticated");
    }

    $today = date('Y-m-d');

    // If staff, filter by their ID. If Councilor/Mayor, count all assigned items.
    $isStaff = ($userRole === 'staff');
    $resWhere = $isStaff ? "WHERE assigned_to = ?" : "WHERE assigned_to IS NOT NULL AND assigned_to != ''";
    $ordWhere = $isStaff ? "WHERE assigned_to = ?" : "WHERE assigned_to IS NOT NULL AND assigned_to != ''";

    $stmt1 = $mysqli->prepare("SELECT COUNT(*) FROM resolutions $resWhere");
    if (!$stmt1) throw new Exception($mysqli->error);
    if ($isStaff) $stmt1->bind_param('s', $userId);
    $stmt1->execute();
    $stmt1->bind_result($assignedResolutions);
    $stmt1->fetch();
    $stmt1->close();

    $stmt2 = $mysqli->prepare("SELECT COUNT(*) FROM ordinances $ordWhere");
    if (!$stmt2) throw new Exception($mysqli->error);
    if ($isStaff) $stmt2->bind_param('s', $userId);
    $stmt2->execute();
    $stmt2->bind_result($assignedOrdinances);
    $stmt2->fetch();
    $stmt2->close();

    $stmt3 = $mysqli->prepare("SELECT COUNT(*) FROM tbl_tasks_peruser WHERE user_id = ? AND status != 'Completed'");
    if (!$stmt3) throw new Exception($mysqli->error);
    $stmt3->bind_param('s', $userId);
    $stmt3->execute();
    $stmt3->bind_result($tasksPending);
    $stmt3->fetch();
    $stmt3->close();

    $stmt4 = $mysqli->prepare("SELECT COUNT(*) FROM legislative_schedules WHERE schedule_date >= ?");
    if (!$stmt4) throw new Exception($mysqli->error);
    $stmt4->bind_param('s', $today);
    $stmt4->execute();
    $stmt4->bind_result($upcomingHearings);
    $stmt4->fetch();
    $stmt4->close();

    $response['success'] = true;
    $response['data'] = [
        'assigned_resolutions' => (int)$assignedResolutions,
        'assigned_ordinances'  => (int)$assignedOrdinances,
        'tasks_pending'        => (int)$tasksPending,
        'upcoming_hearings'    => (int)$upcomingHearings
    ];

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    if (function_exists('logError')) {
        logError($mysqli, $e->getMessage(), basename(__FILE__)); 
    }
    http_response_code(500);
}

echo json_encode($response);
$mysqli->close();