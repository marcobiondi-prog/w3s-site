<?php

require_once __DIR__ . "/../controllers/conn.php";

// Se l'utente è già autenticato lo reindirizza alla dashboard
if (isset($_SESSION["user_id"])) {

    header("Location: dashboard.php");
    exit();

}

$title = "Login";

include __DIR__ . "/header.php";

?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="material-card">

                <div class="material-card-body">

                    <h2 class="material-title text-center">Bentornato</h2>

                    <?php if (isset($_GET["registered"]) && $_GET["registered"] == "1") { ?>
                        <div class="alert alert-success" role="alert">
                            Registrazione avvenuta con successo!!
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET["error"]) && $_GET["error"] == "email_not_found") { ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> Utente non registrato. Crea un account per poter accedere.
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET["error"]) && $_GET["error"] == "invalid_password") { ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> Password errata
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET["error"]) && $_GET["error"] == "missing_fields") { ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> Inserisci email e password
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET["error"]) && in_array($_GET["error"], ["email_required", "email_invalid", "email_too_long"], true)) { ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> Inserisci un indirizzo email valido.
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET["reset"]) && $_GET["reset"] == "success") { ?>
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle"></i> Password reimpostata con successo! Accedi con la tua nuova password
                        </div>
                    <?php } ?>

                    <form action="../controllers/handle_login.php" method="POST" id="loginForm">

                        <div class="form-floating mb-3">
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Email"
                                required>
                            <label for="email">Email</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Password"
                                required>
                            <label for="password">Password</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button
                                type="submit"
                                class="btn btn-material flex-grow-1">

                                Accedi

                            </button>
                        </div>

                        <div class="text-end">
                            <a href="forgot_password.php" class="material-link small">
                                Password dimenticata?
                            </a>
                        </div>

                    </form>

                    <div class="material-divider">oppure</div>

                    <div class="d-grid gap-2">

                        <a href="../controllers/auth_google.php" class="btn-material-outline">
                            <i class="bi bi-google"></i> Accedi con Google
                        </a>

                        <a href="../controllers/auth_facebook.php" class="btn-material-outline">
                            <i class="bi bi-facebook"></i> Accedi con Facebook
                        </a>

                    </div>

                    <p class="text-center mt-4 mb-0">

                        Non hai ancora un account?

                        <a href="register.php" class="material-link">

                            Registrati

                        </a>

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<?php
include __DIR__ . "/footer.php";
?>

<!-- Validazione email lato client -->
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Se l'email è vuota o non valida, mostra alert e previeni l'invio
    if (!email || !emailRegex.test(email)) {
        e.preventDefault();
        alert('⚠️ Email non valida. Per favore, inserisci un\'email valida.');
        document.getElementById('email').focus();
        return false;
    }
});
</script>