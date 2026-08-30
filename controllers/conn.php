<?php
// Avvia la sessione (necessaria per login/logout su tutte le pagine)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Credenziali per connessione al database locale
$host = "localhost";
$user = "root";
$pass = "";            // In MAMP di solito è "root", in XAMPP lascia vuoto ""
$db   = "w3s";         // Nome del database

// Crea la connessione MySQLi
$conn = new mysqli($host, $user, $pass, $db);

// Controlla se la connessione è riuscita
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Imposta il charset UTF-8 per evitare problemi con accenti e caratteri speciali
$conn->set_charset("utf8mb4");