<?php

require_once __DIR__ . "/includes/models/conn.php";

// Controllo della sessione
if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

$title = "Nuovo Articolo";

include "header.php";

// Recupera gli argomenti
$sql = "SELECT * FROM argomenti ORDER BY nome ASC";

$result = $conn->query($sql);

?>

<div class="container mt-5">

    <h1 class="mb-4">Nuovo Articolo</h1>

    <?php if (isset($_GET["errore"]) && $_GET["errore"] === "duplicato") { ?>
        <div class="alert alert-warning" role="alert">
            Esiste già un articolo con questo titolo nell'argomento selezionato.
        </div>
    <?php } ?>

    <?php if (isset($_GET["successo"]) && $_GET["successo"] === "inserimento") { ?>
        <div class="alert alert-success" role="alert">
            Articolo inserito con successo.
        </div>
    <?php } ?>

    <div class="card shadow">

        <div class="card-body">

            <form action="handle_articoli.php" method="POST">

                <!-- Argomento -->

                <div class="mb-3">

                    <label class="form-label">

                        Argomento

                    </label>

                    <select
                        name="id_argomento"
                        class="form-select"
                        required>

                        <option value="">

                            Seleziona un argomento

                        </option>

                        <?php while($row = $result->fetch_assoc()){ ?>

                            <option value="<?php echo $row["id_argomento"]; ?>">

                                <?php echo htmlspecialchars($row["nome"]); ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- Titolo -->

                <div class="mb-3">

                    <label class="form-label">

                        Titolo

                    </label>

                    <input
                        type="text"
                        name="titolo"
                        class="form-control"
                        required>

                </div>

                <!-- Contenuto -->

                <div class="mb-4">

                    <label class="form-label">

                        Contenuto dell'articolo

                    </label>

                    <textarea
                        name="contenuto"
                        class="form-control"
                        rows="12"
                        required></textarea>

                </div>

                <!-- Visibilità -->

                <div class="mb-4 form-check">

                    <input
                        type="checkbox"
                        name="pubblico"
                        value="1"
                        class="form-check-input"
                        id="pubblico">

                    <label class="form-check-label" for="pubblico">
                        Rendi visibile pubblicamente
                    </label>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary">

                    Pubblica articolo

                </button>

            </form>

        </div>

    </div>

    <hr class="my-5">

    <h2>Articoli pubblicati</h2>

    <?php

    $sql = "SELECT a.id_articolo,
                   a.titolo,
                   a.pubblico,
                   ar.nome AS argomento
            FROM articoli a
            INNER JOIN argomenti ar
            ON a.id_argomento = ar.id_argomento
            ORDER BY a.id_articolo DESC";

    $articoli = $conn->query($sql);

    ?>

    <?php if($articoli->num_rows > 0){ ?>

        <table class="table table-striped">

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Titolo</th>

                    <th>Argomento</th>

                    <th>Visibilità</th>

                    <th>Visualizza</th>

                </tr>

            </thead>

            <tbody>

                <?php while($articolo = $articoli->fetch_assoc()){ ?>

                    <tr>

                        <td>

                            <?php echo $articolo["id_articolo"]; ?>

                        </td>

                        <td>

                            <?php echo htmlspecialchars($articolo["titolo"]); ?>

                        </td>

                        <td>

                            <?php echo htmlspecialchars($articolo["argomento"]); ?>

                        </td>

                        <td>

                            <span class="badge <?php echo $articolo["pubblico"] ? "bg-success" : "bg-secondary"; ?>">
                                <?php echo $articolo["pubblico"] ? "Pubblico" : "Privato"; ?>
                            </span>

                        </td>

                        <td>

                            <a
                                href="<?php echo $articolo["pubblico"] ? "articolo_pubblico.php" : "articolo.php"; ?>?id=<?php echo $articolo["id_articolo"]; ?>"
                                class="btn btn-success">

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