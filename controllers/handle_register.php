<?php

require_once __DIR__ . "/conn.php";
require_once __DIR__ . "/../models/user.php";

// Controlla che il form sia stato inviato tramite POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../viewes/register.php?error=1");
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
    header("Location: ../viewes/register.php?error=1");
    exit();
}

// Controllo password
if ($password !== $conferma_password) {
    header("Location: ../viewes/register.php?error=1");
    exit();
}

// Controllo se l'email esiste già
$user_model = new User($conn);

if ($user_model->checkregister($email)) {
    header("Location: ../viewes/register.php?exists=1");
    exit();
}

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

    header("Location: ../viewes/login.php?registered=1");

    exit();

} else {

    header("Location: ../viewes/register.php?error=1");
    exit();

}