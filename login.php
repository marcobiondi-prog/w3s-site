<?php

require_once __DIR__ . "/includes/models/conn.php";

// Se l'utente è già autenticato lo reindirizza alla dashboard
if (isset($_SESSION["user_id"])) {

    header("Location: dashboard.php");
    exit();

}

$title = "Login";

include "header.php";

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
                            <i class="bi bi-exclamation-triangle"></i> Email non trovata nel database
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET["reset"]) && $_GET["reset"] == "success") { ?>
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle"></i> Password reimpostata con successo! Accedi con la tua nuova password
                        </div>
                    <?php } ?>

                    <form action="handle_login.php" method="POST">

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

                        <a href="auth_google.php" class="btn-material-outline">
                            <i class="bi bi-google"></i> Accedi con Google
                        </a>

                        <a href="auth_facebook.php" class="btn-material-outline">
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

include "footer.php";

?>