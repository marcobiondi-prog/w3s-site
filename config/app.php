<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_ROOT', __DIR__ . '/..');
$app_scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$app_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$app_path = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
define('APP_BASE_URL', getenv('APP_BASE_URL') ?: $app_scheme . '://' . $app_host . ($app_path === '/' ? '' : $app_path));

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'w3s');

function db_connect()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new RuntimeException('Connessione fallita: ' . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4');

    return $conn;
}

$conn = db_connect();
