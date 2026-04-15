<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once __DIR__ . '/../connections/connection.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id       = $_POST['event_id'] ?? null;
        $title    = $_POST['title']    ?? '';
        $type     = $_POST['type']     ?? '';
        $date     = $_POST['date']     ?? '';
        $time     = $_POST['time']     ?? '';
        $location = $_POST['location'] ?? '';

        if (!$id) {
            throw new Exception("No event ID provided.");
        }

        $sql = "UPDATE legislative_schedules 
                SET title = ?, type = ?, schedule_date = ?, schedule_time = ?, location = ? 
                WHERE event_id = ?";
        $stmt = $mysqli->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $mysqli->error);
        }
        $stmt->bind_param('ssssss', $title, $type, $date, $time, $location, $id);
        
        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            throw new Exception($stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }
}

echo json_encode($response);
$mysqli->close();