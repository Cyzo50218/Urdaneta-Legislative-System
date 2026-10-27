<?php

$requestedPath = $_GET['__vercel_path'] ?? 'index.php';
unset($_GET['__vercel_path'], $_REQUEST['__vercel_path']);

if (!is_string($requestedPath)) {
    http_response_code(404);
    exit('Not Found');
}

$requestedPath = str_replace('\\', '/', $requestedPath);
$requestedPath = ltrim($requestedPath, '/');

if (
    str_contains($requestedPath, "\0")
    || in_array('..', explode('/', $requestedPath), true)
    || !preg_match('/^(?:api|pages)\/.+\.php$|^index\.php$/', $requestedPath)
    || $requestedPath === 'api/index.php'
    || str_starts_with($requestedPath, 'api/connections/')
    || $requestedPath === 'api/helpers/errorHelper.php'
) {
    http_response_code(404);
    exit('Not Found');
}

$projectRoot = realpath(dirname(__DIR__));
$target = realpath($projectRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requestedPath));

if (
    $projectRoot === false
    || $target === false
    || !str_starts_with($target, $projectRoot . DIRECTORY_SEPARATOR)
    || !is_file($target)
) {
    http_response_code(404);
    exit('Not Found');
}

$_SERVER['SCRIPT_NAME'] = '/' . $requestedPath;
$_SERVER['PHP_SELF'] = '/' . $requestedPath;
$_SERVER['SCRIPT_FILENAME'] = $target;

if (!chdir(dirname($target))) {
    http_response_code(500);
    exit('Unable to prepare request');
}

require $target;
