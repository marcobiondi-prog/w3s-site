<?php

require_once __DIR__ . "/includes/models/conn.php";

// Controlla che il form sia stato inviato tramite POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: forgot_password.php");
    exit();
}

// Recupera i dati
$token = isset($_POST["token"]) ? trim($_POST["token"]) : "";
$password = $_POST["password"] ?? "";
$conferma_password = $_POST["conferma_password"] ?? "";

// Controllo campi vuoti
if (empty($token) || empty($password) || empty($conferma_password)) {
    header("Location: reset_password.php?token=" . urlencode($token) . "&error=empty_password");
    exit();
}

// Controllo password
if ($password !== $conferma_password) {
    header("Location: reset_password.php?token=" . urlencode($token) . "&error=passwords_not_match");
    exit();
}

// Cerca l'utente con il token valido
$sql = "SELECT id_utente FROM utenti WHERE reset_token = ? AND reset_token_expiry > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    header("Location: forgot_password.php");
    exit();
}

$utente = $result->fetch_assoc();
$stmt->close();

// Crea l'hash della password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Aggiorna la password e cancella il token
$sql_update = "UPDATE utenti SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id_utente = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("si", $password_hash, $utente["id_utente"]);

if ($stmt_update->execute()) {
    $stmt_update->close();
    header("Location: login.php?reset=success");
    exit();
} else {
    header("Location: reset_password.php?token=" . urlencode($token) . "&error=update_failed");
    exit();
}

$conn->close();

?>
