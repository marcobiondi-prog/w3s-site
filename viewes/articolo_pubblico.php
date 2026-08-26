<?php

require_once __DIR__ . "/../controllers/conn.php";

// Controllo dell'ID
if (!isset($_GET["id"]) || empty($_GET["id"])) {

    die("Articolo non trovato.");

}

$id = intval($_GET["id"]);



$title = "Visualizza Articolo";

include __DIR__ . "/header.php";

// Recupera l'articolo con il relativo argomento
$sql = "SELECT
            a.id_articolo,
            a.titolo,
            a.corpo AS contenuto,
            ar.nome AS argomento
        FROM articoli a
        INNER JOIN argomenti ar
            ON a.id_argomento = ar.id_argomento
        WHERE a.id_articolo = ? AND a.pubblico = 1";

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

?>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h2>

                <?php echo htmlspecialchars($articolo["titolo"]); ?>

            </h2>

        </div>

        <div class="card-body">

            <p>

                <strong>Argomento:</strong>

                <?php echo htmlspecialchars($articolo["argomento"]); ?>

            </p>

            <hr>

            <p style="text-align: justify; white-space: pre-line;">

                <?php echo htmlspecialchars($articolo["contenuto"]); ?>

            </p>

        </div>

        <div class="card-footer d-flex gap-2">

            <a
                href="articoli.php"
                class="btn btn-secondary">

                Torna agli articoli

            </a>

            <?php if (isset($_SESSION["user_id"])) { ?>

                <a
                    href="articolo_modifica.php?id=<?php echo $articolo["id_articolo"]; ?>"
                    class="btn btn-primary">

                    Modifica

                </a>

            <?php } ?>

        </div>

    </div>

</div>

<?php

$stmt->close();

$conn->close();

include __DIR__ . "/footer.php";

?>