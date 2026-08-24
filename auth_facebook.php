<?php

require_once __DIR__ . "/includes/models/conn.php";
require_once "oauth_config.php";

if (empty(FACEBOOK_APP_ID)) {
    // Nessuna credenziale reale configurata: usa la simulazione locale
    header("Location: auth_simulate.php?provider=facebook");
    exit();
}

$state = bin2hex(random_bytes(16));
$_SESSION["oauth_state"] = $state;

$params = [
    "client_id" => FACEBOOK_APP_ID,
    "redirect_uri" => FACEBOOK_REDIRECT_URI,
    "state" => $state,
    "scope" => "email,public_profile",
];

header("Location: https://www.facebook.com/v19.0/dialog/oauth?" . http_build_query($params));
exit();
