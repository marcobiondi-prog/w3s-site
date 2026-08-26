<?php

require_once __DIR__ . "/../controllers/conn.php";

// Se l'utente è già autenticato lo reindirizza alla dashboard
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

$title = "Recupera Password";

include __DIR__ . "/header.php";

?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="material-card">

                <div class="material-card-body">

                    <h2 class="material-title text-center">Recupera Password</h2>

                    <p class="text-center text-muted mb-4">
                        Inserisci la tua email per ricevere le istruzioni di ripristino
                    </p>

                    <?php if (isset($_GET["error"]) && $_GET["error"] == "email_not_found") { ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> Email non trovata nel database
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET["success"]) && $_GET["success"] == "1") { ?>
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle"></i> Email inviata con successo! Controlla la tua casella email
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET["debug"]) && $_GET["debug"] == "1" && !empty($_SESSION["reset_debug_link"] ?? '')) { ?>
                        <div class="alert alert-info" role="alert">
                            <strong>Modalità locale:</strong><br>
                            <a href="<?= htmlspecialchars($_SESSION["reset_debug_link"]) ?>" target="_blank" rel="noopener noreferrer">
                                <?= htmlspecialchars($_SESSION["reset_debug_link"]) ?>
                            </a>
                        </div>
                    <?php } ?>

                    <form action="../handle_forgot_password.php" method="POST">

                        <div class="form-floating mb-4">
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Email"
                                required>
                            <label for="email">Email</label>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-material w-100">
                            Invia Link di Recupero
                        </button>

                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">
                            Ti ricordi la password?
                            <a href="login.php" class="material-link">
                                Torna al login
                            </a>
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

include __DIR__ . "/footer.php";

?>
