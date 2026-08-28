<?php

require_once __DIR__ . "/../controllers/conn.php";
require_once __DIR__ . "/../models/user.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION["user_id"];
$user_model = new User($conn);
$utente = $user_model->findById($user_id);

if (!$utente) {
    header("Location: ../controllers/handle_logout.php");
    exit();
}

$errors = [];
$updated = isset($_GET["updated"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $cognome = trim($_POST["cognome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");

    if ($nome === "" || $cognome === "") {
        $errors[] = "Nome e cognome sono obbligatori.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Inserisci un indirizzo email valido.";
    }

    if (!$errors && $user_model->emailExists($email, $user_id)) {
        $errors[] = "Questa email è già associata a un altro account.";
    }

    if (!$errors) {
        if ($user_model->updateProfile($user_id, $nome, $cognome, $email, $telefono)) {
            $_SESSION["nome"] = $nome;
            $_SESSION["cognome"] = $cognome;
            $_SESSION["email"] = $email;
            header("Location: user.php?updated=1");
            exit();
        }

        $errors[] = "Impossibile salvare le modifiche. Riprova.";
    }
} else {
    $nome = $utente["nome"];
    $cognome = $utente["cognome"];
    $email = $utente["email"];
    $telefono = $utente["numero_di_telefono"] ?? "";
}

$title = "Il mio account";
include __DIR__ . "/header.php";
?>

<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="material-card">
                <div class="material-card-body">
                    <h1 class="material-title text-center">Il mio account</h1>
                    <p class="text-center text-muted mb-4">Gestisci i tuoi dati personali.</p>

                    <?php if ($updated) { ?>
                        <div class="alert alert-success" role="alert">
                            Dati aggiornati con successo.
                        </div>
                    <?php } ?>

                    <?php if ($errors) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars(implode(" ", $errors)) ?>
                        </div>
                    <?php } ?>

                    <form method="POST" action="user.php">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nome" name="nome"
                                           value="<?= htmlspecialchars($nome) ?>" placeholder="Nome" required>
                                    <label for="nome">Nome *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="cognome" name="cognome"
                                           value="<?= htmlspecialchars($cognome) ?>" placeholder="Cognome" required>
                                    <label for="cognome">Cognome *</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= htmlspecialchars($email) ?>" placeholder="Email" required>
                            <label for="email">Email *</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="telefono" name="telefono"
                                   value="<?= htmlspecialchars($telefono) ?>" placeholder="Telefono">
                            <label for="telefono">Telefono</label>
                        </div>

                        <button type="submit" class="btn btn-material w-100">Salva modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
$conn->close();
include __DIR__ . "/footer.php";
?>