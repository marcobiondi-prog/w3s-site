<?php

require_once "conn.php";

// Controlla che il form sia stato inviato tramite POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: register.php?error=1");
    exit();
}

// Recupera i dati del form
$nome = trim($_POST["nome"]);
$cognome = trim($_POST["cognome"]);
$email = trim($_POST["email"]);
$telefono = trim($_POST["telefono"]);
$password = $_POST["password"];
$conferma_password = $_POST["conferma_password"];

// Controllo campi vuoti
if (
    empty($nome) ||
    empty($cognome) ||
    empty($email) ||
    empty($password) ||
    empty($conferma_password)
) {
    header("Location: register.php?error=1");
    exit();
}

// Controllo password
if ($password !== $conferma_password) {
    header("Location: register.php?error=1");
    exit();
}

// Controllo se l'email esiste già
$sql = "SELECT id_utente FROM utenti WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: register.php?exists=1");
    exit();
}

$stmt->close();

// Crea l'hash della password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Inserimento utente
$sql = "INSERT INTO utenti
(nome, cognome, email, numero_di_telefono, password)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssss",
    $nome,
    $cognome,
    $email,
    $telefono,
    $password_hash
);

if ($stmt->execute()) {

    $stmt->close();

    header("Location: login.php?registered=1");

    exit();

} else {

    header("Location: register.php?error=1");
    exit();

}