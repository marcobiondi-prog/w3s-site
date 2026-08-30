<?php

// Avvia la sessione se non è ancora avviata
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Imposta la cartella radice dell'applicazione
define('APP_ROOT', __DIR__ . '/..');

// Costruisce l'URL base dell'applicazione (schema + host + percorso)
$app_scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$app_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$app_path = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
define('APP_BASE_URL', getenv('APP_BASE_URL') ?: $app_scheme . '://' . $app_host . ($app_path === '/' ? '' : $app_path));

// Configurazione database - Credenziali di connessione
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'w3s');

// Funzione helper per connettere al database con gestione errori
function db_connect()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Se la connessione fallisce, lancia un'eccezione
    if ($conn->connect_error) {
        throw new RuntimeException('Connessione fallita: ' . $conn->connect_error);
    }

    // Imposta il charset UTF-8 per evitare problemi con accenti
    $conn->set_charset('utf8mb4');

    return $conn;
}

// Crea la connessione al database
$conn = db_connect();

