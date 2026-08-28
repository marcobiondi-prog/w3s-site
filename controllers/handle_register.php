<?php

require_once __DIR__ . "/conn.php";
require_once __DIR__ . "/../models/user.php";
require_once __DIR__ . "/../models/validator.php";

// Accetta solo richieste POST dal form di registrazione
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../viewes/register.php?error=method_not_allowed");
    exit();
}

// Recupera e pulisce i dati dal form
$nome = trim($_POST["nome"] ?? '');
$cognome = trim($_POST["cognome"] ?? '');
$telefono = trim($_POST["telefono"] ?? '');

// Verifica che nome e cognome siano presenti
if (empty($nome) || empty($cognome)) {
    header("Location: ../viewes/register.php?error=missing_name");
    exit();
}

// Valida il formato email
$email_result = Validator::validateEmail($_POST["email"] ?? '');
if (!$email_result['valid']) {
    header("Location: ../viewes/register.php?error=" . $email_result['code']);
    exit();
}

// Valida la password con requisiti di sicurezza forte
$password_result = Validator::validatePassword($_POST["password"] ?? '');
if (!$password_result['valid']) {
    header("Location: ../viewes/register.php?error=" . $password_result['code']);
    exit();
}

// Verifica che le password siano uguali
$password_match = Validator::validatePasswordMatch(
    $_POST["password"],
    $_POST["conferma_password"] ?? ''
);
if (!$password_match['valid']) {
    header("Location: ../viewes/register.php?error=" . $password_match['code']);
    exit();
}

// Estrae i dati validati e l'hash della password
$email = $email_result['email'];
$password_hash = $password_result['password_hash'];

// Controlla che l'email non sia già registrata
$user_model = new User($conn);
if ($user_model->checkregister($email)) {
    header("Location: ../viewes/register.php?error=email_exists");
    exit();
}

// Inserisce il nuovo utente nel database
$sql = "INSERT INTO utenti (nome, cognome, email, numero_di_telefono, password)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nome, $cognome, $email, $telefono, $password_hash);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: ../viewes/login.php?registered=1");
    exit();
} else {
    header("Location: ../viewes/register.php?error=registration_failed");
    exit();
}