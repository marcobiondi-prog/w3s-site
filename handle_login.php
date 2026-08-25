<?php

require_once __DIR__ . "/includes/models/conn.php";

// Controlla che il form sia stato inviato tramite POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: login.php");
    exit();

}

// Recupera i dati
$email = trim($_POST["email"]);
$password = $_POST["password"];

// Controllo campi vuoti
if (empty($email) || empty($password)) {

    die("Compila tutti i campi.");

}

// Cerca l'utente tramite email
$sql = "SELECT * FROM utenti WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

// Controlla se l'utente esiste
if ($result->num_rows == 1) {

    $utente = $result->fetch_assoc();

    // Verifica la password
    if (password_verify($password, $utente["password"])) {

        // Salva i dati dell'utente nella sessione
        session_start();
        $_SESSION["user_id"] = $utente["id_utente"];
        $_SESSION["nome"] = $utente["nome"];
        $_SESSION["cognome"] = $utente["cognome"];
        $_SESSION["email"] = $utente["email"];

        // Reindirizza alla dashboard
        header("Location: dashboard.php");
        exit();

    } else {

        die("Password non corretta.");

    }

} else {

    header("Location: login.php?error=email_not_found");
    exit();

}

$stmt->close();

$conn->close();

?>