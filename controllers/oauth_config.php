<?php
require_once __DIR__ . "/../config/app.php";

// Credenziali per il login con Google e Facebook.
// Inseriscile qui dopo aver creato le rispettive app:
// - Google:   https://console.cloud.google.com/apis/credentials
// - Facebook: https://developers.facebook.com/apps

define("GOOGLE_CLIENT_ID", getenv("GOOGLE_CLIENT_ID") ?: "");
define("GOOGLE_CLIENT_SECRET", getenv("GOOGLE_CLIENT_SECRET") ?: "");
define("GOOGLE_REDIRECT_URI", getenv("GOOGLE_REDIRECT_URI") ?: APP_BASE_URL . "/auth_google_callback.php");

define("FACEBOOK_APP_ID", getenv("FACEBOOK_APP_ID") ?: "");
define("FACEBOOK_APP_SECRET", getenv("FACEBOOK_APP_SECRET") ?: "");
define("FACEBOOK_REDIRECT_URI", getenv("FACEBOOK_REDIRECT_URI") ?: APP_BASE_URL . "/auth_facebook_callback.php");
