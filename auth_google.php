<?php

require_once "conn.php";
require_once "oauth_config.php";

if (empty(GOOGLE_CLIENT_ID)) {
    // Nessuna credenziale reale configurata: usa la simulazione locale
    header("Location: auth_simulate.php?provider=google");
    exit();
}

$state = bin2hex(random_bytes(16));
$_SESSION["oauth_state"] = $state;

$params = [
    "client_id" => GOOGLE_CLIENT_ID,
    "redirect_uri" => GOOGLE_REDIRECT_URI,
    "response_type" => "code",
    "scope" => "openid email profile",
    "state" => $state,
    "prompt" => "select_account",
];

header("Location: https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query($params));
exit();
