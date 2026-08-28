<?php

require_once __DIR__ . "/../controllers/conn.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"]) || !ctype_digit((string) $_GET["id"])) {
    die("Articolo non trovato.");
}

$id = (int) $_GET["id"];
$title = "Visualizza Articolo";

$stmt = $conn->prepare(
    "SELECT a.id_articolo, a.titolo, a.corpo AS contenuto, ar.nome AS argomento
     FROM articoli a
     INNER JOIN argomenti ar ON a.id_argomento = ar.id_argomento
     WHERE a.id_articolo = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$articolo = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$articolo) {
    $conn->close();
    http_response_code(404);
    die("Articolo non trovato.");
}

include __DIR__ . "/header.php";
?>

<main class="container mt-5 mb-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h1 class="h2 mb-0"><?= htmlspecialchars($articolo["titolo"]) ?></h1>
        </div>
        <div class="card-body">
            <p><strong>Argomento:</strong> <?= htmlspecialchars($articolo["argomento"]) ?></p>
            <hr>
            <p style="text-align: justify; white-space: pre-line;"><?= htmlspecialchars($articolo["contenuto"]) ?></p>
        </div>
        <div class="card-footer d-flex gap-2">
            <a href="articoli.php" class="btn btn-secondary">Torna agli articoli</a>
            <a href="articolo_modifica.php?id=<?= $articolo["id_articolo"] ?>" class="btn btn-primary">Modifica</a>
        </div>
    </div>
</main>

<?php
$conn->close();
include __DIR__ . "/footer.php";
?>