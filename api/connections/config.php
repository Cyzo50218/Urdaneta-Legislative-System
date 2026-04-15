<?php
require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->safeLoad(); 

$dsn_react = sprintf(
    '%s:%s@%s/%s?charset=%s',
    rawurlencode(getenv('DB_USER') ?: $_ENV['DB_USER']),
    rawurlencode(getenv('DB_PASSWORD') ?: $_ENV['DB_PASSWORD']),
    rawurlencode(getenv('DB_HOST') ?: $_ENV['DB_HOST']),
    rawurlencode(getenv('DB_NAME') ?: $_ENV['DB_NAME']),
    rawurlencode(getenv('DB_CHARSET') ?: $_ENV['DB_CHARSET'])
);

return [
    'host'    => getenv('DB_HOST') ?: $_ENV['DB_HOST'],
    'ipaddress' => getenv('IP_ADDRESS') ?: $_ENV['IP_ADDRESS'],
    'websocketport' => getenv('IP_WEBSOCKET_PORT') ?: $_ENV['IP_WEBSOCKET_PORT'],
    'user'    => getenv('DB_USER') ?: $_ENV['DB_USER'],
    'pass'    => getenv('DB_PASSWORD') ?: $_ENV['DB_PASSWORD'],
    'dbname'  => getenv('DB_NAME') ?: $_ENV['DB_NAME'],
    'charset' => getenv('DB_CHARSET') ?: $_ENV['DB_CHARSET'],
    'port'    => getenv('DB_PORT') ?: $_ENV['DB_PORT'],
    'dsn_react' => $dsn_react,
];
