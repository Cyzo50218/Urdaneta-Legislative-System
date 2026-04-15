<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; connect-src 'self'; object-src 'none';");
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Cookie security settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');

require_once __DIR__ . '/../connections/connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function generate_uuid()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'register':
        $fname    = trim(filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_SPECIAL_CHARS));
        $lname    = trim(filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_SPECIAL_CHARS));
        $email    = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $pass     = $_POST['password'] ?? '';
        $role     = $_POST['registerType'] ?? 'staff';
        $region   = trim(filter_input(INPUT_POST, 'region', FILTER_SANITIZE_SPECIAL_CHARS));
        $province = trim(filter_input(INPUT_POST, 'province', FILTER_SANITIZE_SPECIAL_CHARS));
        $city     = trim(filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS));
        $barangay = trim(filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_SPECIAL_CHARS));

        if (!$email || strlen($pass) < 8 || empty($fname) || empty($region) || empty($city) || empty($barangay)) {
            echo json_encode(['success' => false, 'message' => 'Please fill all required fields correctly.']);
            exit;
        }

        $check = $conn->prepare("SELECT email FROM tbl_users WHERE email = ? LIMIT 1");
        $check->execute([$email]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'This email is already registered.']);
            exit;
        }

        $uuid = generate_uuid();
        $hash = password_hash($pass, PASSWORD_ARGON2ID);

        try {
            $sysName  = $_SESSION['systemName'] ?? '';
            $sysLoc   = $_SESSION['systemLocation'] ?? '';
            $sysAdmin = $_SESSION['systemAdministrator'] ?? 0;

            $sql = "INSERT INTO tbl_users 
            (user_id, first_name, last_name, email, password_hash, region, province, city, barangay, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$uuid, $fname, $lname, $email, $hash, $region, $province, $city, $barangay, $role]);
            session_unset();
            session_regenerate_id(true);

            $_SESSION['systemName']          = $sysName;
            $_SESSION['systemLocation']      = $sysLoc;
            $_SESSION['systemAdministrator'] = $sysAdmin;

            $_SESSION['user_id']    = $uuid;
            $_SESSION['role']       = $role;
            $_SESSION['full_name']  = $fname . ' ' . $lname;
            $_SESSION['last_login'] = time();

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error during registration.']);
        }
        break;

    case 'login':
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            echo json_encode(['success' => false, 'message' => 'Please provide credentials.']);
            exit;
        }

        $stmt = $conn->prepare("SELECT user_id, password_hash, first_name, last_name, role FROM tbl_users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $sysName = $_SESSION['systemName'] ?? '';
            $sysLoc  = $_SESSION['systemLocation'] ?? '';
            $sysAdmin = $_SESSION['systemAdministrator'] ?? 0;
            session_unset();
            session_regenerate_id(true);
            $_SESSION['systemName']          = $sysName;
            $_SESSION['systemLocation']      = $sysLoc;
            $_SESSION['systemAdministrator'] = $sysAdmin;
            $_SESSION['user_id']             = $user['user_id'];
            $_SESSION['role']                = $user['role'];
            $_SESSION['full_name']           = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['last_login']          = time();
            session_write_close();
            echo json_encode([
                'success' => true
            ]);
            exit;
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Action not found']);
}
