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

// Crea l'hash della password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$user_model = new User($nome, $cognome, $email, $telefono, $password_hash);

// Controllo se l'email esiste già
if ($user_model->checkRegister()) {
    header("Location: ../viewes/register.php?exists=1");
    exit();
}

if ($user_model->register()) {
    header("Location: ../viewes/login.php?registered=1");
    exit();
} else {
    header("Location: ../viewes/register.php?error=1");
    exit();
}