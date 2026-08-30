<?php

require_once __DIR__ . "/../controllers/conn.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$idArgomento = $_GET["id"] ?? "";

if (!ctype_digit($idArgomento)) {
    header("Location: argomenti.php");
    exit();
}

$idArgomento = (int) $idArgomento;
$stmt = $conn->prepare("SELECT nome FROM argomenti WHERE id_argomento = ?");
$stmt->bind_param("i", $idArgomento);
$stmt->execute();
$argomento = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$argomento) {
    header("Location: argomenti.php");
    exit();
}

$stmt = $conn->prepare("SELECT id_articolo, titolo, pubblico FROM articoli WHERE id_argomento = ? ORDER BY id_articolo DESC");
$stmt->bind_param("i", $idArgomento);
$stmt->execute();
$articoli = $stmt->get_result();

$title = "Articoli - " . $argomento["nome"];
include __DIR__ . "/header.php";
?>

<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Articoli: <?= htmlspecialchars($argomento["nome"]) ?></h1>
        <a href="argomenti.php" class="btn btn-secondary">Torna agli argomenti</a>
    </div>

    <?php if ($articoli->num_rows > 0) { ?>
        <div class="list-group">
            <?php while ($articolo = $articoli->fetch_assoc()) { ?>
                <a href="articolo.php?id=<?= $articolo["id_articolo"] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($articolo["titolo"]) ?>
                    <span class="badge <?= $articolo["pubblico"] ? "bg-success" : "bg-secondary" ?>">
                        <?= $articolo["pubblico"] ? "Pubblico" : "Privato" ?>
                    </span>
                </a>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning">Nessun articolo per questo argomento.</div>
    <?php } ?>
</main>

<?php
$stmt->close();
$conn->close();
include __DIR__ . "/footer.php";
?>