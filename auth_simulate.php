<?php

require_once "conn.php";

$provider = $_GET["provider"] ?? "";

$providers = [
    "google" => ["nome" => "Google", "icona" => "bi-google", "colore" => "#ea4335"],
    "facebook" => ["nome" => "Facebook", "icona" => "bi-facebook", "colore" => "#1877f2"],
];

if (!isset($providers[$provider])) {
    die("Provider non valido.");
}

$info = $providers[$provider];
$title = "Simulazione login " . $info["nome"];

include "header.php";
?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="material-card">

                <div class="material-card-body">

                    <div class="text-center mb-3">
                        <i class="bi <?= $info["icona"] ?>" style="font-size: 2.5rem; color: <?= $info["colore"] ?>;"></i>
                    </div>

                    <h2 class="material-title text-center">Accedi con <?= htmlspecialchars($info["nome"]) ?></h2>

                    <div class="alert alert-warning small">
                        <strong>Modalità simulazione locale.</strong>
                        Non sono ancora state impostate le credenziali reali di <?= htmlspecialchars($info["nome"]) ?>
                        in <code>oauth_config.php</code>. Compila questi campi per simulare l'account con cui
                        accedere: verrà creato (o ritrovato) un utente collegato a questa email.
                    </div>

                    <form action="auth_simulate_handle.php" method="POST">

                        <input type="hidden" name="provider" value="<?= htmlspecialchars($provider) ?>">

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
                            <label for="nome">Nome</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="cognome" name="cognome" placeholder="Cognome" required>
                            <label for="cognome">Cognome</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                            <label for="email">Email</label>
                        </div>

                        <button type="submit" class="btn btn-material w-100">
                            Continua
                        </button>

                    </form>

                    <p class="text-center mt-4 mb-0">
                        <a href="login.php" class="material-link">Annulla</a>
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>
