<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: GET');
header('X-Frame-Options: DENY');
header('Cache-Control: public, max-age=30');

require_once __DIR__ . '/connections/connection.php';
require_once __DIR__ . '/helpers/errorHelper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = ['success' => false, 'data' => []];

try {
    $userId = $_SESSION['user_id'] ?? null;
    $userRole = $_SESSION['role'] ?? null;

    if ($userId === null) {
        http_response_code(401);
        throw new Exception("User not authenticated");
    }

    $resWhere = ($userRole === 'staff') ? "r.assigned_to = ?" : "1=1";
    $ordWhere = ($userRole === 'staff') ? "o.assigned_to = ?" : "1=1";
    $sql = "
    SELECT 
        MAX(type) as type, 
        trackingNo, 
        MAX(title) as title, 
        MAX(author) as author, 
        MAX(status) as status, 
        MAX(deadline) as deadline, 
        MAX(category) as category, 
        MAX(yearTaken) as yearTaken, 
        MAX(agendaNo) as agendaNo, 
        MAX(committee) as committee, 
        MAX(sessionType) as sessionType, 
        MAX(assigned_to) as assigned_to, 
        MAX(assigned_name) as assigned_name,
        MAX(summary) as summary,
        MAX(additional_fields) as additional_fields 
    FROM (
        /* 1. Resolutions */
        SELECT 
            CAST('Resolution' AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS type, 
            CAST(r.resolution_code AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS trackingNo, 
            CAST(r.title AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS title, 
            CAST('Add Author' AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS author, 
            CAST(r.status AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS status, 
            r.assigned_date AS deadline,
            CAST('Resolution' AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS category, 
            YEAR(r.assigned_date) AS yearTaken,
            CAST(r.agenda_no AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS agendaNo, 
            CAST(r.committee AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS committee,
            CAST(r.session_type AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS sessionType,
            CAST(r.assigned_to AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS assigned_to,
            CAST(CONCAT(u.first_name, ' ', u.last_name) AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS assigned_name,
            CAST(NULL AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS summary,
            CAST('[]' AS JSON) AS additional_fields 
        FROM resolutions r
        LEFT JOIN tbl_users u ON r.assigned_to = u.user_id
        WHERE $resWhere

        UNION ALL

        /* 2. Ordinances */
        SELECT 
            CAST('Ordinance' AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS type, 
            CAST(o.ordinance_code AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS trackingNo, 
            CAST(o.title AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS title, 
            CAST('Add Author' AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS author, 
            CAST(o.status AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS status, 
            o.assigned_date AS deadline,
            CAST('Ordinance' AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS category,
            YEAR(o.assigned_date) AS yearTaken,
            CAST(o.agenda_no AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS agendaNo, 
            CAST(o.committee AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS committee,
            CAST(o.session_type AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS sessionType,
            CAST(o.assigned_to AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS assigned_to,
            CAST(CONCAT(u.first_name, ' ', u.last_name) AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS assigned_name,
            CAST(NULL AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS summary,
            CAST('[]' AS JSON) AS additional_fields 
        FROM ordinances o
        LEFT JOIN tbl_users u ON o.assigned_to = u.user_id
        WHERE $ordWhere

        UNION ALL

        /* 3. Legislation Details */
        SELECT 
            CAST(CONCAT(UPPER(LEFT(type, 1)), LOWER(SUBSTRING(type, 2))) AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS type, 
            CAST(bill_number AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS trackingNo, 
            CAST(title AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS title, 
            CAST(authors AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS author,
            CAST('assigned' AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS status, 
            date_filed AS deadline,
            CAST(type AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS category,
            YEAR(date_filed) AS yearTaken,
            CAST(NULL AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS agendaNo,
            CAST(committee AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS committee,
            CAST('N/A' AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS sessionType,
            CAST(NULL AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS assigned_to,
            CAST(NULL AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS assigned_name,
            CAST(summary AS CHAR(255)) COLLATE utf8mb4_unicode_ci AS summary,
            additional_fields
        FROM legislation_details 
    ) AS combined_results
    GROUP BY trackingNo
    ORDER BY deadline DESC";

    $stmt = $mysqli->prepare($sql);

    if ($userRole === 'staff') {
        $stmt->bind_param('ss', $userId, $userId);
        $stmt->execute();
    } else {
        $stmt->execute();
    }

    $result = $stmt->get_result();
    $assignedItems = [];

    while ($row = $result->fetch_assoc()) {
        $statusLower = strtolower($row['status']);
        $statusClass = 'status-default';

        if ($statusLower === 'assigned') $statusClass = 'status-assigned';
        elseif ($statusLower === 'in-progress') $statusClass = 'status-in-progress';
        elseif ($statusLower === 'for-review') $statusClass = 'status-review';
        elseif ($statusLower === 'approved' || $statusLower === 'enacted') $statusClass = 'status-approved';
        elseif ($statusLower === 'rejected') $statusClass = 'status-rejected';
        elseif ($statusLower === 'archived') $statusClass = 'status-archived';
        elseif (strpos($statusLower, 'committee') !== false) $statusClass = 'status-committee';
        elseif (strpos($statusLower, 'reading') !== false) $statusClass = 'status-reading';
        if ($userRole === 'staff') {
            $displayAssignedTo = "Me";
        } else {
            $displayAssignedTo = $row['assigned_name'] ?? "Unassigned";
        }

        $folder = strtolower($row['type']) . 's';
        $hyperlink = "/uploads/{$folder}/" . $row['trackingNo'] . ".pdf";
        $deadlineFormatted = ($row['deadline']) ? date("M d, Y", strtotime($row['deadline'])) : 'N/A';

        $assignedItems[] = [
            'trackingNo'   => $row['trackingNo'],
            'title'        => $row['title'],
            'type'         => $row['type'],
            'author'       => $row['author'],
            'status'       => ucwords($row['status'], "- "),
            'statusClass'  => $statusClass,
            'deadline'     => $deadlineFormatted,
            'category'     => $row['category'],
            'yearTaken'    => (string)$row['yearTaken'],
            'agendaNo'     => $row['agendaNo'] ?? 'N/A',
            'committee'    => $row['committee'] ?? 'N/A',
            'sessionType'  => $row['sessionType'] ?? 'Regular Session',
            'assignedTo'   => $displayAssignedTo,
            'assignedName' => $row['assigned_name'],
            'summary'      => $row['summary'] ?? '', 
            'additional_fields' => json_decode($row['additional_fields'] ?? '[]', true),
            'hyperlink'    => $hyperlink,
            'attachments'  => [],
            'locked'       => ($statusClass === 'status-approved')
        ];
    }

    $response['success'] = true;
    $response['data'] = $assignedItems;
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}
echo json_encode($response);
