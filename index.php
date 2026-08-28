<?php
$title = "W3S - Home";
include __DIR__ . '/viewes/header.php';

// Controlla se l'utente è loggato (per decidere quali articoli mostrare)
$logged_in = isset($_SESSION["user_id"]);

// Recupera gli ultimi 6 articoli (visibili in base al login)
if ($logged_in) {
    // Utenti loggati vedono tutti gli articoli (pubblici e privati)
    $sql = "SELECT a.id_articolo, a.titolo, a.pubblico, ar.nome AS argomento
            FROM articoli a
            INNER JOIN argomenti ar ON a.id_argomento = ar.id_argomento
            ORDER BY a.id_articolo DESC
            LIMIT 6";
} else {
    // Utenti non loggati vedono solo articoli pubblici (pubblico = 1)
    $sql = "SELECT a.id_articolo, a.titolo, a.pubblico, ar.nome AS argomento
            FROM articoli a
            INNER JOIN argomenti ar ON a.id_argomento = ar.id_argomento
            WHERE a.pubblico = 1
            ORDER BY a.id_articolo DESC
            LIMIT 6";
}

$articoli = $conn->query($sql);
?>

<main class="container mt-5">

    <h1 class="mb-4">Ultimi articoli</h1>

    <?php if ($articoli && $articoli->num_rows > 0) { ?>

        <div class="row g-4">

            <?php while ($articolo = $articoli->fetch_assoc()) { ?>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">

                            <span class="badge <?php echo $articolo["pubblico"] ? "bg-success" : "bg-secondary"; ?> mb-2 align-self-start">
                                <?php echo $articolo["pubblico"] ? "Pubblico" : "Privato"; ?>
                            </span>

                            <h5 class="card-title"><?php echo htmlspecialchars($articolo["titolo"]); ?></h5>
                            <p class="card-text text-muted mb-3"><?php echo htmlspecialchars($articolo["argomento"]); ?></p>

                            <a
                                href="viewes/<?php echo $articolo["pubblico"] ? "articolo_pubblico.php" : "articolo.php"; ?>?id=<?php echo $articolo["id_articolo"]; ?>"
                                class="btn btn-primary btn-sm mt-auto">
                                Leggi
                            </a>

                        </div>
                    </div>
                </div>

            <?php } ?>

        </div>

    <?php } else { ?>

        <div class="alert alert-warning">
            Nessun articolo disponibile al momento.
        </div>

    <?php } ?>

</main>

<?php
include __DIR__ . '/viewes/footer.php';
?>
