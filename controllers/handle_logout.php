<?php
require_once __DIR__ . "/conn.php";

// Avvia la sessione se non è ancora avviata
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Svuota il contenuto dell'array della sessione
$_SESSION = [];

// Distrugge anche il cookie della sessione per logout completo
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        "",
        time() - 42000,  // Imposta scadenza nel passato per forzare cancellazione
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Distrugge la sessione completamente
session_destroy();

// Reindirizza alla homepage
header("Location: ../index.php");
exit();

