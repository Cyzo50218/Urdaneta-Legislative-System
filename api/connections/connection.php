<?php
if (count(get_included_files()) === 1) exit("Direct access not permitted.");
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_path', '/');
    ini_set('session.cookie_secure', 0); 
    
    session_start();
}
$cfg = require __DIR__ . '/config.php';

$host    = $cfg['host'];
$db      = $cfg['dbname'];
$user    = $cfg['user'];
$pass    = $cfg['pass'];
$port    = $cfg['port'] ?? 3306;
$charset = $cfg['charset'] ?? 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,                  
    PDO::ATTR_PERSISTENT         => false,                 
];
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new PDO($dsn, $user, $pass, $options);
    $mysqli = new mysqli($host, $user, $pass, $db, $port);
    $mysqli->set_charset($charset);

} catch (Exception $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database Connection Error']);
    exit;
}

try {
    $procExists = $conn->query("SHOW PROCEDURE STATUS WHERE Db='$db' AND Name='checkSystem'")->fetch();
    
    if (!$procExists) {
        $sql = "CREATE PROCEDURE `checkSystem`()
                BEGIN
                    SELECT systemName, systemLocation, systemAdministrator 
                    FROM system_settings 
                    ORDER BY id ASC 
                    LIMIT 1;
                END";
        $conn->exec($sql);
    }
} catch (Exception $e) {
    error_log("⚠️ System Procedure Check Failed: " . $e->getMessage());
}
