<?php

require_once __DIR__ . "/../controllers/conn.php";

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

include __DIR__ . "/header.php";

// Recupera l'articolo da modificare
$sql = "SELECT id_articolo, id_argomento, titolo, corpo, pubblico
        FROM articoli
        WHERE id_articolo = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {

    echo "<div class='container mt-5'>";
    echo "<div class='alert alert-danger'>";
    echo "Articolo non trovato.";
    echo "</div>";
    echo "</div>";

    include __DIR__ . "/footer.php";
    exit();

}

$articolo = $result->fetch_assoc();

$stmt->close();

// Recupera gli argomenti
$argomenti = $conn->query("SELECT * FROM argomenti ORDER BY nome ASC");

?>

<div class="container mt-5">

    <h1 class="mb-4">Modifica Articolo</h1>

    <div class="card shadow">

        <div class="card-body">

            <form action="../handle_articoli.php" method="POST">

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
                    href="<?php echo $articolo["pubblico"] ? "articolo_pubblico.php" : "articolo.php"; ?>?id=<?php echo $articolo["id_articolo"]; ?>"
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
