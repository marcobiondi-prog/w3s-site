<?php

require_once __DIR__ . "/conn.php";

// Accetta solo richieste POST (form submission)
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../viewes/forgot_password.php");
    exit();
}

// Recupera i dati dal form di reset password
$token = isset($_POST["token"]) ? trim($_POST["token"]) : "";
$password = $_POST["password"] ?? "";
$conferma_password = $_POST["conferma_password"] ?? "";

// Valida che tutti i campi siano compilati
if (empty($token) || empty($password) || empty($conferma_password)) {
    header("Location: ../viewes/reset_password.php?token=" . urlencode($token) . "&error=empty_password");
    exit();
}

// Verifica che le password siano identiche
if ($password !== $conferma_password) {
    header("Location: ../viewes/reset_password.php?token=" . urlencode($token) . "&error=passwords_not_match");
    exit();
}

// Cerca l'utente con il token valido e non scaduto (NOW() controlla la scadenza)
$sql = "SELECT id_utente FROM utenti WHERE reset_token = ? AND reset_token_expiry > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

// Se il token non è valido o è scaduto, reindirizza a forgot_password
if ($result->num_rows != 1) {
    header("Location: ../viewes/forgot_password.php");
    exit();
}

$utente = $result->fetch_assoc();
$stmt->close();

// Crea l'hash della password utilizzando password_hash per massima sicurezza
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Aggiorna la password nel database e cancella il token per invalidarlo
$sql_update = "UPDATE utenti SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id_utente = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("si", $password_hash, $utente["id_utente"]);

// Se l'aggiornamento è riuscito, reindirizza a login
if ($stmt_update->execute()) {
    $stmt_update->close();
    header("Location: ../viewes/login.php?reset=success");
    exit();
} else {
    header("Location: ../viewes/reset_password.php?token=" . urlencode($token) . "&error=update_failed");
    exit();
}

$conn->close();

?>
