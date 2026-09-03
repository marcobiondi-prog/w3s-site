<?php
require_once __DIR__ . "/../controllers/conn.php";

$current_page = basename($_SERVER["PHP_SELF"]);
$view_base = str_ends_with(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"] ?? "")), "/viewes") ? "" : "viewes/";
$home_base = $view_base === "" ? "../" : "";
$asset_base = $view_base === "" ? "../asset/" : "asset/";
$controller_base = $view_base === "" ? "../controllers/" : "controllers/";

function nav_active($page, $current) {
    return $page === $current ? "active" : "";
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? "W3S" ?></title>

    <!-- Bootstrap 5 (necessario per le classi container/row/card/btn/form-control/table usate nel sito) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons (usate nel menu utente) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Stili personalizzati del sito (va DOPO Bootstrap per poterlo sovrascrivere) -->
    <link rel="stylesheet" href="<?= $asset_base ?>style.css?v=<?= filemtime(__DIR__ . "/../asset/style.css") ?>">
</head>

<body>

<header class="header">

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">

            <!-- Logo -->
            <a class="navbar-brand logo" href="<?= $home_base ?>index.php">W3S</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-controls="mainNav" aria-expanded="false" aria-label="Apri menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu di navigazione -->
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-main-link <?= nav_active("index.php", $current_page) ?>" href="<?= $home_base ?>index.php">
                            <span class="nav-link-icon"><i class="bi bi-house"></i></span>
                            <span class="nav-link-label">Home</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-main-link <?= nav_active("argomenti.php", $current_page) ?>" href="<?= $view_base ?>argomenti.php">
                            <span class="nav-link-icon"><i class="bi bi-bookmarks"></i></span>
                            <span class="nav-link-label">Argomenti</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-main-link <?= nav_active("articoli.php", $current_page) ?>" href="<?= $view_base ?>articoli.php">
                            <span class="nav-link-icon"><i class="bi bi-newspaper"></i></span>
                            <span class="nav-link-label">Articoli</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-main-link <?= nav_active("esercizi.php", $current_page) ?>" href="<?= $view_base ?>esercizi.php">
                            <span class="nav-link-icon"><i class="bi bi-pencil-square"></i></span>
                            <span class="nav-link-label">Esercizi</span>
                        </a>
                    </li>

                </ul>
            </div>

            <!-- Pulsanti / area utente -->
            <div class="buttons">

                <?php if (isset($_SESSION["user_id"])) { ?>

                    <!-- Utente loggato: versione desktop (dropdown Bootstrap) -->
                    <div class="dropdown user-menu-desktop d-none d-lg-block">
                        <a class="btn user-chip dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="user-chip-avatar">
                                <?= strtoupper(substr($_SESSION["nome"] ?? "U", 0, 1)) ?>
                            </span>
                            <?= htmlspecialchars($_SESSION["nome"] ?? "Utente") ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end user-menu-panel">
                            <li>
                                <a class="user-menu-item" href="<?= $view_base ?>dashboard.php">
                                    <span class="user-menu-item-icon"><i class="bi bi-speedometer2"></i></span>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="user-menu-item" href="<?= $view_base ?>user.php">
                                    <span class="user-menu-item-icon"><i class="bi bi-person"></i></span>
                                    Profilo
                                </a>
                            </li>
                            <li><hr class="user-menu-divider"></li>
                            <li>
                                <a class="user-menu-item user-menu-item-logout" href="<?= $controller_base ?>handle_logout.php">
                                    <span class="user-menu-item-icon"><i class="bi bi-box-arrow-right"></i></span>
                                    Esci
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Utente loggato: versione mobile (menu a comparsa) -->
                    <details class="mobile-user-menu d-lg-none">
                        <summary class="btn nav-btn-login mobile-user-trigger"></summary>
                        <div class="mobile-user-panel">
                            <div class="mobile-user-panel-header">Account</div>
                            <div class="mobile-user-panel-list">
                                <a class="mobile-user-link" href="<?= $view_base ?>dashboard.php">
                                    <span class="mobile-user-link-icon"><i class="bi bi-speedometer2"></i></span>
                                    Dashboard
                                </a>
                                <a class="mobile-user-link" href="<?= $view_base ?>user.php">
                                    <span class="mobile-user-link-icon"><i class="bi bi-person"></i></span>
                                    Profilo
                                </a>
                            </div>
                            <div class="mobile-user-panel-footer">
                                <span class="mobile-user-panel-account">
                                    <span class="mobile-user-panel-avatar"></span>
                                    <?= htmlspecialchars($_SESSION["nome"] ?? "Utente") ?>
                                </span>
                                <a class="mobile-user-panel-power" href="<?= $controller_base ?>handle_logout.php" title="Esci">
                                    <i class="bi bi-box-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </details>

                <?php } else { ?>

                    <a href="<?= $view_base ?>login.php" class="btn nav-btn-login">Login</a>
                    <a href="<?= $view_base ?>register.php" class="btn btn-primary nav-btn-register">Registrati</a>

                <?php } ?>

            </div>

        </div>
    </nav>


  

</header>
