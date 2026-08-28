<?php

require_once __DIR__ . "/conn.php";
require_once __DIR__ . "/../models/validator.php";

// Accetta solo richieste POST dal form di login
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../viewes/login.php");
    exit();
}

// Valida il formato email
$email_result = Validator::validateEmail($_POST["email"]);
if (!$email_result['valid']) {
    header("Location: ../viewes/login.php?error=" . $email_result['code']);
    exit();
}

// Valida il formato password
$password_result = Validator::validatePassword($_POST["password"]);
if (!$password_result['valid']) {
    header("Location: ../viewes/login.php?error=" . $password_result['code']);
    exit();
}

$email = $email_result['email'];
$password = $_POST["password"];

// Cerca l'utente nel database tramite email
$sql = "SELECT * FROM utenti WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Se l'utente esiste, verifica la password
if ($result->num_rows == 1) {
    $utente = $result->fetch_assoc();

    // Verifica la password usando password_verify per confronto sicuro
    if (password_verify($password, $utente["password"])) {
        // Login riuscito: salva i dati dell'utente nella sessione
        session_start();
        $_SESSION["user_id"] = $utente["id_utente"];
        $_SESSION["nome"] = $utente["nome"];
        $_SESSION["cognome"] = $utente["cognome"];
        $_SESSION["email"] = $utente["email"];

        header("Location: ../viewes/dashboard.php");
        exit();
    } else {
        // Password non corretta
        header("Location: ../viewes/login.php?error=invalid_password");
        exit();
    }
} else {
    // Email non trovata nel database
    header("Location: ../viewes/login.php?error=email_not_found");
    exit();
}

$stmt->close();
$conn->close();
?>