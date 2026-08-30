<?php

// Costruisce un URL assoluto dell'applicazione a partire da un path relativo
function app_url(string $path = ''): string
{
    $base = rtrim(APP_BASE_URL, '/');
    $path = ltrim($path, '/');

    return $path === '' ? $base : $base . '/' . $path;
}

// Costruisce un URL per gli asset (CSS, JS, immagini)
function asset_url(string $path): string
{
    return app_url('assets/' . ltrim($path, '/'));
}

// Reindirizza a un percorso specifico dell'applicazione
function redirect_to(string $path): void
{
    header('Location: ' . app_url($path));
    exit;
}

// Verifica che l'utente sia loggato, altrimenti lo reindirizza a login
function require_login(): void
{
    if (!isset($_SESSION['user_id'])) {
        redirect_to('login.php');
    }
}

// Recupera il nome del file della pagina corrente (es: "articoli.php")
function current_page_name(): string
{
    return basename($_SERVER['PHP_SELF'] ?? 'index.php');
}

// Ritorna "active" se la pagina corrente corrisponde a quella passata (per navigazione)
function nav_active(string $page, string $current): string
{
    return $page === $current ? 'active' : '';
}

