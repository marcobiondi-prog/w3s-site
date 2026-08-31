<?php

require_once __DIR__ . "/../controllers/conn.php";

// Controllo dell'ID
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Articolo non trovato.");
}

$id = intval($_GET["id"]);
$title = "Visualizza Articolo";
$isLoggedIn = isset($_SESSION["user_id"]);

// Recupera l'articolo con il relativo argomento
// Se non loggato, filtra solo articoli pubblici
$sql = "SELECT a.id_articolo, a.titolo, a.corpo AS contenuto, ar.nome AS argomento
        FROM articoli a
        INNER JOIN argomenti ar ON a.id_argomento = ar.id_argomento
        WHERE a.id_articolo = ?";

if (!$isLoggedIn) {
    $sql .= " AND a.pubblico = 1";
}

$stmt = $conn->prepare($sql);
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
            <?php if ($isLoggedIn) { ?>
                <a href="articolo_modifica.php?id=<?= $articolo["id_articolo"] ?>" class="btn btn-primary">Modifica</a>
                <form action="../controllers/handle_elimina_articolo.php" method="POST" onsubmit="return confirm('Vuoi eliminare questo articolo?');">
                    <input type="hidden" name="id_articolo" value="<?= $articolo["id_articolo"] ?>">
                    <button type="submit" class="btn btn-danger">Elimina</button>
                </form>
            <?php } ?>
        </div>
    </div>
</main>

<?php
$conn->close();
include __DIR__ . "/footer.php";
?>