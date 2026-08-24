<?php

require_once __DIR__ . "/includes/models/conn.php";

// Controllo della sessione
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$title = "Nuovo Esercizio";

include "header.php";

// Recupera gli argomenti dal database
$sql = "SELECT * FROM argomenti ORDER BY nome ASC";
$result = $conn->query($sql);

?>

<div class="container mt-5">

    <h1 class="mb-4">Nuovo Esercizio</h1>

    <div class="card shadow">

        <div class="card-body">

            <form action="handle_esercizi.php" method="POST">

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

                <!-- Domanda -->

                <div class="mb-3">

                    <label class="form-label">

                        Domanda

                    </label>

                    <textarea
                        name="domanda"
                        class="form-control"
                        rows="4"
                        required></textarea>

                </div>

                <!-- Link -->

                <div class="mb-3">

                    <label class="form-label">

                        Link di approfondimento (facoltativo)

                    </label>

                    <input
                        type="url"
                        name="link"
                        class="form-control">

                </div>

                <hr>

                <h4>Risposte</h4>

                <div class="mb-3">

                    <label>Risposta 1</label>

                    <input
                        type="text"
                        name="risposta1"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label>Risposta 2</label>

                    <input
                        type="text"
                        name="risposta2"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label>Risposta 3</label>

                    <input
                        type="text"
                        name="risposta3"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label>Risposta 4</label>

                    <input
                        type="text"
                        name="risposta4"
                        class="form-control"
                        required>

                </div>

                <div class="mb-4">

                    <label class="form-label">

                        Risposta corretta

                    </label>

                    <select
                        name="corretta"
                        class="form-select"
                        required>

                        <option value="1">Risposta 1</option>

                        <option value="2">Risposta 2</option>

                        <option value="3">Risposta 3</option>

                        <option value="4">Risposta 4</option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="btn btn-success">

                    Salva esercizio

                </button>

            </form>

        </div>

    </div>

</div>

<?php

include "footer.php";

?>