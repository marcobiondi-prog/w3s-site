<?php

require_once __DIR__ . "/../controllers/conn.php";

// Se l'utente è già autenticato lo reindirizza alla dashboard
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

$title = "Reimposta Password";

// Verifica il token
$token = isset($_GET["token"]) ? trim($_GET["token"]) : "";

if (empty($token)) {
    header("Location: login.php");
    exit();
}

// Può essere richiamato direttamente col link da email: l'URL corretto è in questa cartella.
if (isset($_GET["debug"])) {
    unset($_SESSION["reset_debug_link"]);
    $_SESSION["reset_debug_link"] = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

}

// Cerca l'utente con il token valido
$sql = "SELECT id_utente, email FROM utenti WHERE reset_token = ? AND reset_token_expiry > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    $token_error = true;
    $token_valid = false;
} else {
    $utente = $result->fetch_assoc();
    $token_valid = true;
    $token_error = false;
}

$stmt->close();

include __DIR__ . "/header.php";

?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="material-card">

                <div class="material-card-body">

                    <h2 class="material-title text-center">Reimposta Password</h2>

                    <?php if ($token_error) { ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-circle"></i> Link non valido o scaduto
                        </div>
                        <div class="text-center">
                            <p class="mb-0">
                                <a href="forgot_password.php" class="material-link">
                                    Richiedi un nuovo link
                                </a>
                            </p>
                        </div>
                    <?php } else { ?>

                        <?php if (isset($_GET["error"]) && $_GET["error"] == "passwords_not_match") { ?>
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> Le password non corrispondono
                            </div>
                        <?php } ?>

                        <?php if (isset($_GET["error"]) && $_GET["error"] == "empty_password") { ?>
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> Compila tutti i campi
                            </div>
                        <?php } ?>

                            <form action="../handle_reset_password.php" method="POST">

                            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                            <div class="form-floating mb-3">
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    placeholder="Nuova Password"
                                    required>
                                <label for="password">Nuova Password</label>
                            </div>

                            <div class="form-floating mb-4">
                                <input
                                    type="password"
                                    class="form-control"
                                    id="conferma_password"
                                    name="conferma_password"
                                    placeholder="Conferma Password"
                                    required>
                                <label for="conferma_password">Conferma Password</label>
                            </div>

                            <button
                                type="submit"
                                class="btn btn-material w-100">
                                Reimposta Password
                            </button>

                        </form>

                        <div class="text-center mt-4">
                            <p class="mb-0">
                                <a href="login.php" class="material-link">
                                    Torna al login
                                </a>
                            </p>
                        </div>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

include __DIR__ . "/footer.php";

?>
