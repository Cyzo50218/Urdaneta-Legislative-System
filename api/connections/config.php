<?php
require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->safeLoad();

$readEnv = static function (string $name, ?string $default = null): ?string {
    $value = getenv($name);

    if ($value !== false) {
        return $value;
    }

    $value = $_ENV[$name] ?? $_SERVER[$name] ?? $default;

    return is_string($value) ? $value : $default;
};

$values = [
    'DB_USER' => $readEnv('DB_USER'),
    'DB_PASSWORD' => $readEnv('DB_PASSWORD'),
    'DB_HOST' => $readEnv('DB_HOST'),
    'DB_NAME' => $readEnv('DB_NAME'),
];

$missing = [];
foreach ($values as $name => $value) {
    if ($value === null || ($name !== 'DB_PASSWORD' && trim($value) === '')) {
        $missing[] = $name;
    }
}

if ($missing !== []) {
    throw new RuntimeException(
        'Missing required database environment variables: ' . implode(', ', $missing)
    );
}

$charset = $readEnv('DB_CHARSET', 'utf8mb4');
$port = $readEnv('DB_PORT', '3306');
$host = $values['DB_HOST'];
$user = $values['DB_USER'];
$password = $values['DB_PASSWORD'];
$dbname = $values['DB_NAME'];

$dsnReact = sprintf(
    '%s:%s@%s/%s?charset=%s',
    rawurlencode($user),
    rawurlencode($password),
    rawurlencode($host),
    rawurlencode($dbname),
    rawurlencode($charset)
);

return [
    'host'    => $host,
    'ipaddress' => $readEnv('IP_ADDRESS', ''),
    'websocketport' => $readEnv('IP_WEBSOCKET_PORT', ''),
    'user'    => $user,
    'pass'    => $password,
    'dbname'  => $dbname,
    'charset' => $charset,
    'port'    => $port,
    'dsn_react' => $dsnReact,
];
