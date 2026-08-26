<?php

require_once __DIR__ . "/includes/models/conn.php";
require_once "oauth_config.php";
require_once "oauth_helpers.php";

if (!isset($_GET["code"]) || !isset($_GET["state"]) || $_GET["state"] !== ($_SESSION["oauth_state"] ?? "")) {
    die("Autenticazione Google non valida.");
}

unset($_SESSION["oauth_state"]);

// Scambia il codice ricevuto con un access token
$ch = curl_init("https://oauth2.googleapis.com/token");
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => http_build_query([
        "code" => $_GET["code"],
        "client_id" => GOOGLE_CLIENT_ID,
        "client_secret" => GOOGLE_CLIENT_SECRET,
        "redirect_uri" => GOOGLE_REDIRECT_URI,
        "grant_type" => "authorization_code",
    ]),
]);
$token_response = curl_exec($ch);
$token_error = curl_error($ch);
$token_data = json_decode($token_response ?: "", true);
curl_close($ch);

if (!isset($token_data["access_token"])) {
    die("Errore durante l'autenticazione con Google." . ($token_error ? " Dettaglio: " . htmlspecialchars($token_error) : ""));
}

// Recupera i dati del profilo utente
$ch = curl_init("https://www.googleapis.com/oauth2/v3/userinfo");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Authorization: Bearer " . $token_data["access_token"]],
]);
$profile_response = curl_exec($ch);
$profile_error = curl_error($ch);
$profile = json_decode($profile_response ?: "", true);
curl_close($ch);

if (!isset($profile["sub"], $profile["email"])) {
    die("Impossibile recuperare i dati dell'account Google." . ($profile_error ? " Dettaglio: " . htmlspecialchars($profile_error) : ""));
}

login_or_register_oauth(
    $conn,
    "google_id",
    $profile["sub"],
    $profile["email"],
    $profile["given_name"] ?? ($profile["name"] ?? "Utente"),
    $profile["family_name"] ?? ""
);
