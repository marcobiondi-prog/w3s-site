<?php

require_once "conn.php";

// Controllo della sessione
if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

$title = "Dashboard";

include "header.php";

// Recupera tutti gli articoli
$sql = "SELECT * FROM articoli ORDER BY id_articolo DESC";

$result = $conn->query($sql);

?>

<div class="container mt-5">

    <h1 class="mb-3">
        Benvenuto,
        <?php echo htmlspecialchars($_SESSION["nome"]); ?>!
    </h1>

    <p class="lead">
        Questa è la tua dashboard personale.
    </p>

    <hr>

    <div class="row mb-5">

        <div class="col-md-4">

            <div class="card text-center shadow">

                <div class="card-body">

                    <h4>Argomenti</h4>

                    <p>Crea e gestisci gli argomenti.</p>

                    <a href="argomenti.php" class="btn btn-primary">
                        Vai
                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card text-center shadow">

                <div class="card-body">

                    <h4>Esercizi</h4>

                    <p>Crea nuovi esercizi.</p>

                    <a href="esercizi.php" class="btn btn-success">
                        Vai
                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card text-center shadow">

                <div class="card-body">

                    <h4>Articoli</h4>

                    <p>Scrivi un nuovo articolo.</p>

                    <a href="articoli.php" class="btn btn-warning">
                        Vai
                    </a>

                </div>

            </div>

        </div>

    </div>

    <h2 class="mb-4">Articoli pubblicati</h2>

    <?php if ($result->num_rows > 0) { ?>

        <table class="table table-bordered table-hover">

            <thead class="table-dark">

                <tr>

                    <th>ID</th>

                    <th>Titolo</th>

                    <th>Visibilità</th>

                    <th>Visualizza</th>

                </tr>

            </thead>

            <tbody>

                <?php while($articolo = $result->fetch_assoc()) { ?>

                    <tr>

                        <td>
                            <?php echo $articolo["id_articolo"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($articolo["titolo"]); ?>
                        </td>

                        <td>
                            <span class="badge <?php echo $articolo["pubblico"] ? "bg-success" : "bg-secondary"; ?>">
                                <?php echo $articolo["pubblico"] ? "Pubblico" : "Privato"; ?>
                            </span>
                        </td>

                        <td>

                            <a
                                href="<?php echo $articolo["pubblico"] ? "articolo_pubblico.php" : "articolo.php"; ?>?id=<?php echo $articolo["id_articolo"]; ?>"
                                class="btn btn-info">

                                Leggi

                            </a>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    <?php } else { ?>

        <div class="alert alert-warning">

            Nessun articolo presente.

        </div>

    <?php } ?>

</div>

<?php

include "footer.php";

?>