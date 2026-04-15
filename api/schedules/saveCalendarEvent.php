<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once __DIR__ . '/../connections/connection.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title    = $_POST['title']    ?? '';
        $type     = $_POST['type']     ?? '';
        $date     = $_POST['date']     ?? '';
        $time     = $_POST['time']     ?? '';
        $location = $_POST['location'] ?? '';
        $city     = $_POST['city']     ?? ''; 
        $chamber  = $_POST['chamber']  ?? 'house'; 

        if (empty($title) || empty($date) || empty($time)) {
            throw new Exception("Missing required fields.");
        }
        $sql = "INSERT INTO legislative_schedules (title, type, schedule_date, schedule_time, location, city, chamber) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('sssssss', $title, $type, $date, $time, $location, $city, $chamber);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Event registered successfully.";
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