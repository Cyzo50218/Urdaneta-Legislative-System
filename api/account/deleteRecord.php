<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../connections/connection.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$response = ['success' => false];

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("Unauthorized access. Please log in.");
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $trackingNo = $input['trackingNo'] ?? '';

    if (empty($trackingNo)) {
        throw new Exception("No tracking number provided.");
    }
    $table = "";
    $codeCol = "";
    if (strpos($trackingNo, 'ORD-') === 0) {
        $table = "ordinances";
        $codeCol = "ordinance_code";
    } elseif (strpos($trackingNo, 'RES-') === 0) {
        $table = "resolutions";
        $codeCol = "resolution_code";
    } else {
        throw new Exception("Invalid tracking number format.");
    }

    $mysqli->begin_transaction();
    $stmt1 = $mysqli->prepare("DELETE FROM legislation_details WHERE bill_number = ?");
    $stmt1->bind_param("s", $trackingNo);
    $stmt1->execute();
    $stmt2 = $mysqli->prepare("DELETE FROM $table WHERE $codeCol = ?");
    $stmt2->bind_param("s", $trackingNo);
    $stmt2->execute();

    if ($stmt2->affected_rows === 0) {
        throw new Exception("Record not found or already deleted.");
    }

    $mysqli->commit();
    $response['success'] = true;

} catch (Exception $e) {
    if (isset($mysqli)) $mysqli->rollback();
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
$mysqli->close();