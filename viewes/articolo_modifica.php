<?php

require_once __DIR__ . "/../controllers/conn.php";
require_once __DIR__ . "/../models/articolo.php";

// Controllo della sessione
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Controllo dell'ID
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Articolo non trovato.");
}

$id = intval($_GET["id"]);
$title = "Modifica Articolo";

// Recupera l'articolo usando il modello
$articolo = Articolo::findById($id);

if (!$articolo) {
    header("Location: articoli.php");
    exit();
}

include __DIR__ . "/header.php";

// Recupera gli argomenti
$argomenti = $conn->query("SELECT * FROM argomenti ORDER BY nome ASC");

?>

<div class="container mt-5">

    <h1 class="mb-4">Modifica Articolo</h1>

    <div class="card shadow">

        <div class="card-body">

            <form action="../controllers/handle_articoli.php" method="POST">

                <input type="hidden" name="id_articolo" value="<?php echo $articolo["id_articolo"]; ?>">

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

                        <?php while ($row = $argomenti->fetch_assoc()) { ?>

                            <option
                                value="<?php echo $row["id_argomento"]; ?>"
                                <?php echo $row["id_argomento"] == $articolo["id_argomento"] ? "selected" : ""; ?>>

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
                        value="<?php echo htmlspecialchars($articolo["titolo"]); ?>"
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
                        required><?php echo htmlspecialchars($articolo["corpo"]); ?></textarea>

                </div>

                <!-- Visibilità -->

                <div class="mb-4">

                    <label class="form-label">

                        Visibilità

                    </label>

                    <select
                        name="pubblico"
                        class="form-select"
                        required>

                        <option value="1" <?php echo $articolo["pubblico"] ? "selected" : ""; ?>>Pubblico</option>

                        <option value="0" <?php echo !$articolo["pubblico"] ? "selected" : ""; ?>>Privato</option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary">

                    Salva modifiche

                </button>

                <a
                    href="articolo.php?id=<?php echo $articolo["id_articolo"]; ?>"
                    class="btn btn-secondary">

                    Annulla

                </a>

            </form>

        </div>

    </div>

</div>

<?php

$conn->close();

include __DIR__ . "/footer.php";

?>
