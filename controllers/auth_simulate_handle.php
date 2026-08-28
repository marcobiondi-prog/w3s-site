<?php

require_once __DIR__ . "/conn.php";
require_once __DIR__ . "/oauth_helpers.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../viewes/login.php");
    exit();
}

$provider = $_POST["provider"] ?? "";

if (!in_array($provider, ["google", "facebook"], true)) {
    die("Provider non valido.");
}

$nome = trim($_POST["nome"] ?? "");
$cognome = trim($_POST["cognome"] ?? "");
$email = trim($_POST["email"] ?? "");

if (empty($nome) || empty($cognome) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Compila correttamente tutti i campi.");
}

// ID simulato ma stabile: la stessa email genera sempre lo stesso account collegato
$simulated_id = "sim_" . substr(sha1(strtolower($email) . $provider), 0, 32);

login_or_register_oauth($conn, $provider . "_id", $simulated_id, $email, $nome, $cognome);
