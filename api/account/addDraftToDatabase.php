<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../connections/connection.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$response = ['success' => false];

try {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        http_response_code(401); 
        throw new Exception("Session Expired, Log in again");
    }
    
    $userId = $_SESSION['user_id']; 
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) throw new Exception("Invalid input data");
    
    $type = $input['type'] ?? 'ordinance'; 
    $billTypeInput = ($type === 'resolution') ? 'resolution' : 'ordinance';
    $meta = $input['metadata'] ?? [];
    $content = $input['content'] ?? ''; 

    if ($billTypeInput === 'ordinance') {
        $table = "ordinances";
        $codeCol = "ordinance_code";
    } else {
        $table = "resolutions";
        $codeCol = "resolution_code";
    }

    // --- TRACKING NUMBER GENERATION WITH COLLISION CHECK ---
    $typeLabel = ($billTypeInput === 'resolution') ? 'RES' : 'ORD';
    $year = date('Y');
    $trackingNo = "";
    $exists = true;
    do {
        $randomTrack = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        $tempNo = "{$typeLabel}-{$year}-{$randomTrack}";
        $checkStmt = $mysqli->prepare("SELECT $codeCol FROM $table WHERE $codeCol = ? LIMIT 1");
        $checkStmt->bind_param("s", $tempNo);
        $checkStmt->execute();
        $checkStmt->store_result();
        
        if ($checkStmt->num_rows === 0) {
            $trackingNo = $tempNo;
            $exists = false;
        }
        $checkStmt->close();
    } while ($exists);
    $agendaRandom = rand(100, 999);
    $docRandom = rand(10, 999);
    $formalType = ($billTypeInput === 'resolution') ? 'Resolution' : 'Ordinance';
    $agendaNo = "Agenda {$agendaRandom} - City {$formalType} No. {$randomTrack}, Series of {$year}";

    $finalTitle = !empty($meta['Title']) ? trim($meta['Title']) : '';
    if (empty($finalTitle)) {
        $finalTitle = !empty($content) ? mb_strimwidth(strip_tags($content), 0, 100, "...") : "Untitled Draft";
    }

    $mysqli->begin_transaction();

    $status = 'assigned'; 
    $committee = !empty($meta['Committee']) ? $meta['Committee'] : 'Unassigned';
    $sessionType = 'Regular Session';

    $stmtLgu = $mysqli->prepare("INSERT INTO $table 
        ($codeCol, title, committee, status, assigned_to, assigned_date, agenda_no, session_type) 
        VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)");

    $stmtLgu->bind_param("sssssss", 
        $trackingNo, 
        $finalTitle, 
        $committee, 
        $status, 
        $userId, 
        $agendaNo, 
        $sessionType
    );

    if (!$stmtLgu->execute()) {
        throw new Exception("LGU Table Error: " . $stmtLgu->error);
    }

    $fieldsArray = [
        ["name" => "WHEREAS,", "value" => $meta['WhereasDescription'] ?? ''],
        ["name" => "SECTION 1.", "value" => $meta['Sec1_Desc'] ?? ''],
        ["name" => "SECTION 2.", "value" => $meta['Sec2_Desc'] ?? ''],
        ["name" => "SECTION 3.", "value" => $meta['Sec3_Desc'] ?? ''],
        ["name" => "SECTION 4.", "value" => $meta['Sec4_Desc'] ?? ''],
        ["name" => "SECTION 5.", "value" => $meta['Sec5_Desc'] ?? ''],
        ["name" => "SECTION 6.", "value" => $meta['Sec6_Desc'] ?? ''],
        ["name" => "SECTION 7.", "value" => $meta['Sec7_Desc'] ?? ''],
        ["name" => "SECTION 8.", "value" => $meta['Sec8_Desc'] ?? ''],
        ["name" => "SECTION 9.", "value" => $meta['Sec9_Desc'] ?? '']
    ];
    if (!empty($meta['DeadlineOrHearingDate'])) {
        $fieldsArray[] = ["name" => "Session Date", "value" => $meta['DeadlineOrHearingDate']];
    }

    $additionalFields = json_encode($fieldsArray);
    $stmtDetails = $mysqli->prepare("INSERT INTO legislation_details 
        (bill_number, type, chamber, title, authors, additional_fields, date_filed, last_action, committee, summary) 
        VALUES (?, ?, 'LGU', ?, ?, ?, CURDATE(), 'Draft Generated', ?, ?)");

    $author = !empty($meta['PrincipalAuthor']) ? $meta['PrincipalAuthor'] : 'AI Assistant';
    $summary = !empty($meta['Summary']) ? $meta['Summary'] : mb_strimwidth(strip_tags($content), 0, 255, "...");
        $stmtDetails->bind_param("sssssss", 
        $trackingNo, 
        $type, 
        $finalTitle, 
        $author, 
        $additionalFields,
        $committee, 
        $summary
    );

    if (!$stmtDetails->execute()) {
        throw new Exception("Details Table Error: " . $stmtDetails->error);
    }

    $mysqli->commit();
    $response['success'] = true;
    $response['trackingNo'] = $trackingNo;

} catch (Exception $e) {
    if (isset($mysqli)) $mysqli->rollback();
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
$mysqli->close();