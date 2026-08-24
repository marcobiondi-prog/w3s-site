<?php

// Credenziali per il login con Google e Facebook.
// Inseriscile qui dopo aver creato le rispettive app:
// - Google:   https://console.cloud.google.com/apis/credentials
// - Facebook: https://developers.facebook.com/apps

define('GOOGLE_CLIENT_ID', '');
define('GOOGLE_CLIENT_SECRET', '');
define('GOOGLE_REDIRECT_URI', APP_BASE_URL . '/auth_google_callback.php');

define('FACEBOOK_APP_ID', '');
define('FACEBOOK_APP_SECRET', '');
define('FACEBOOK_REDIRECT_URI', APP_BASE_URL . '/auth_facebook_callback.php');
