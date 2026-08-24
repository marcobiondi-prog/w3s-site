<?php

function app_url(string $path = ''): string
{
    $base = rtrim(APP_BASE_URL, '/');
    $path = ltrim($path, '/');

    return $path === '' ? $base : $base . '/' . $path;
}

function asset_url(string $path): string
{
    return app_url('assets/' . ltrim($path, '/'));
}

function redirect_to(string $path): void
{
    header('Location: ' . app_url($path));
    exit;
}

function require_login(): void
{
    if (!isset($_SESSION['user_id'])) {
        redirect_to('login.php');
    }
}

function current_page_name(): string
{
    return basename($_SERVER['PHP_SELF'] ?? 'index.php');
}

function nav_active(string $page, string $current): string
{
    return $page === $current ? 'active' : '';
}
