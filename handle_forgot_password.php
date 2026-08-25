<?php

require_once __DIR__ . "/includes/models/conn.php";

// Controlla che il form sia stato inviato tramite POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: forgot_password.php");
    exit();
}

// Recupera i dati
$email = trim($_POST["email"]);

// Controllo campi vuoti
if (empty($email)) {
    header("Location: forgot_password.php?error=empty_email");
    exit();
}

// Cerca l'utente tramite email
$sql = "SELECT id_utente, nome, email FROM utenti WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Controlla se l'utente esiste
if ($result->num_rows == 1) {
    $utente = $result->fetch_assoc();

    // Genera un token casuale
    $token = bin2hex(random_bytes(32));

    // Token scade tra 1 ora
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Salva il token nel database
    $sql_update = "UPDATE utenti SET reset_token = ?, reset_token_expiry = ? WHERE id_utente = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssi", $token, $expiry, $utente["id_utente"]);
    $stmt_update->execute();
    $stmt_update->close();

    // Crea il link di reset
    $reset_link = "http://localhost/database/esercizio-w3s/w3s-site/w3s-site/reset_password.php?token=" . $token;

    // Prepara l'email
    $subject = "W3S - Recupero Password";
    $message = "Ciao " . $utente["nome"] . ",\n\n";
    $message .= "Hai richiesto il recupero della password per il tuo account W3S.\n\n";
    $message .= "Clicca sul link sottostante per reimpostare la tua password:\n";
    $message .= $reset_link . "\n\n";
    $message .= "Questo link scade tra 1 ora.\n\n";
    $message .= "Se non hai richiesto il recupero della password, ignora questa email.\n\n";
    $message .= "Cordiali saluti,\nIl team W3S";

    $headers = "From: noreply@w3s.local\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Invia l'email
    if (mail($email, $subject, $message, $headers)) {
        header("Location: forgot_password.php?success=1");
        exit();
    } else {
        // Errore nell'invio email
        header("Location: forgot_password.php?error=email_send_failed");
        exit();
    }
} else {
    // Email non trovata
    header("Location: forgot_password.php?error=email_not_found");
    exit();
}

$stmt->close();
$conn->close();

?>
