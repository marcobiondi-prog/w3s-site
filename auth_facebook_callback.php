<?php

require_once __DIR__ . "/includes/models/conn.php";
require_once "oauth_config.php";
require_once "oauth_helpers.php";

if (!isset($_GET["code"]) || !isset($_GET["state"]) || $_GET["state"] !== ($_SESSION["oauth_state"] ?? "")) {
    die("Autenticazione Facebook non valida.");
}

unset($_SESSION["oauth_state"]);

// Scambia il codice ricevuto con un access token
$ch = curl_init("https://graph.facebook.com/v19.0/oauth/access_token?" . http_build_query([
    "client_id" => FACEBOOK_APP_ID,
    "client_secret" => FACEBOOK_APP_SECRET,
    "redirect_uri" => FACEBOOK_REDIRECT_URI,
    "code" => $_GET["code"],
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$token_data = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!isset($token_data["access_token"])) {
    die("Errore durante l'autenticazione con Facebook.");
}

// Recupera i dati del profilo utente
$ch = curl_init("https://graph.facebook.com/me?" . http_build_query([
    "fields" => "id,first_name,last_name,email",
    "access_token" => $token_data["access_token"],
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$profile = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!isset($profile["id"])) {
    die("Impossibile recuperare i dati dell'account Facebook.");
}

// Facebook può non restituire l'email se l'utente non l'ha condivisa
$email = $profile["email"] ?? ($profile["id"] . "@facebook.local");

login_or_register_oauth(
    $conn,
    "facebook_id",
    $profile["id"],
    $email,
    $profile["first_name"] ?? "Utente",
    $profile["last_name"] ?? ""
);
