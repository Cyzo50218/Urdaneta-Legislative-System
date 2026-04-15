<?php

/**
 * Logs an error to the `error_logs` table.
 *
 * @param mysqli $mysqli The MySQLi connection.
 * @param string $message The error message to log.
 * @param string|null $scriptName Optional script name. Defaults to current file.
 */
function logError($mysqli, $message, $scriptName = null) {
    if ($scriptName === null) {
        $scriptName = basename($_SERVER['PHP_SELF']); // current script
    }

    $userId = $_SESSION['user_id'] ?? null;

    $stmt = $mysqli->prepare("INSERT INTO error_logs (user_id, script_name, error_message) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param('iss', $userId, $scriptName, $message);
        $stmt->execute();
        $stmt->close();
    }
}
