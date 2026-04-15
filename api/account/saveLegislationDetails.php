<?php

declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: POST');
header('X-Frame-Options: DENY');

require_once __DIR__ . '/../connections/connection.php';
require_once __DIR__ . '/../helpers/errorHelper.php';
require_once __DIR__ . '/../activity/addRecentActivity.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = ['success' => false, 'message' => ''];

try {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) throw new Exception("User not authenticated");
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception("Invalid request method.");

    $trackingNo     = $_POST['trackingNo'] ?? '';
    $title          = $_POST['title'] ?? '';
    $type           = $_POST['type'] ?? '';
    $author         = $_POST['author'] ?? '';
    $status         = $_POST['section'] ?? 'draft';
    $summary        = $_POST['summary'] ?? '';
    $customMetadata = $_POST['custom_metadata'] ?? '[]';
    $lastAction     = $_POST['last_action'] ?? 'Updated Record';

    $committeesArray = $_POST['committee'] ?? [];
    $committeeString = $_POST['committee'] ?? '';

    $checkStmt = $mysqli->prepare("SELECT bill_number, type FROM legislation_details WHERE bill_number = ?");
    $checkStmt->bind_param("s", $trackingNo);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $existingRecord = $result->fetch_assoc();
    $checkStmt->close();

    if ($existingRecord) {
        $trackingNo = $existingRecord['bill_number'];
        $type = $existingRecord['type'];
    }

    if (empty($trackingNo)) throw new Exception("Tracking Number is required.");

    $mainTable = "";
    $idCol = "";
    $normType = strtolower($type);

    if ($normType === 'resolution') {
        $mainTable = "resolutions";
        $idCol = "resolution_code";
    } elseif ($normType === 'ordinance') {
        $mainTable = "ordinances";
        $idCol = "ordinance_code";
    }

    if (!empty($mainTable)) {
        $sqlMain = "UPDATE $mainTable SET title = ?, status = ? WHERE $idCol = ?";
        $stmtMain = $mysqli->prepare($sqlMain);
        $stmtMain->bind_param("sss", $title, $status, $trackingNo);
        $stmtMain->execute();
        $stmtMain->close();
    }

    $sqlLeg = "INSERT INTO legislation_details 
            (bill_number, title, type, authors, committee, additional_fields, last_action, summary, date_filed) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW()) 
            ON DUPLICATE KEY UPDATE 
            title = VALUES(title), 
            committee = VALUES(committee),
            last_action = VALUES(last_action),
            summary = VALUES(summary),
            additional_fields = VALUES(additional_fields)";

    $stmtLeg = $mysqli->prepare($sqlLeg);
    if (!$stmtLeg) throw new Exception("Prepare failed: " . $mysqli->error);

    $stmtLeg->bind_param(
        "ssssssss",
        $trackingNo,
        $title,
        $type,
        $author,
        $committeeString,
        $customMetadata,
        $lastAction,
        $summary
    );
    $stmtLeg->execute();

    $affected = $stmtLeg->affected_rows;
    $stmtLeg->close();

    if ($affected >= 0) {
        $numericId = (int)preg_replace('/[^0-9]/', '', $trackingNo);
        $activityText = "Updated $type #$trackingNo: $lastAction";

        $recentActivityError = '';
        $logOk = addRecentActivity($mysqli, (string)$userId, $numericId, $activityText, $recentActivityError);

        if (!$logOk) {
            error_log("Activity Log Failed: " . $recentActivityError);
            $response['success'] = true;
            $response['log_error'] = true;
        } else {
            $response['success'] = true;
            $response['message'] = "Records updated successfully.";
        }
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    logError($mysqli, $e->getMessage(), basename(__FILE__));
    http_response_code(500);
}

echo json_encode($response);
$mysqli->close();
