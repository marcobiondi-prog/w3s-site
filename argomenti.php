<?php

require_once __DIR__ . "/includes/models/conn.php";

// Controllo della sessione
if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

$title = "Gestione Argomenti";

include "header.php";

// Recupera tutti gli argomenti
$sql = "SELECT * FROM argomenti ORDER BY id_argomento DESC";

$result = $conn->query($sql);

?>

<div class="container mt-5">

    <h1 class="mb-4">Gestione Argomenti</h1>

    <?php if (isset($_GET["errore"]) && $_GET["errore"] === "duplicato") { ?>
        <div class="alert alert-warning" role="alert">
            Questo argomento esiste già.
        </div>
    <?php } ?>

    <div class="card shadow mb-5">

        <div class="card-header bg-primary text-white">

            <h4>Nuovo Argomento</h4>

        </div>

        <div class="card-body">

            <form action="handle_argomenti.php" method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Nome Argomento
                    </label>

                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        placeholder="Inserisci il nome dell'argomento"
                        required>

                </div>

                <button
                    type="submit"
                    class="btn btn-success">

                    Aggiungi Argomento

                </button>

            </form>

        </div>

    </div>

    <div class="card shadow">

        <div class="card-header bg-dark text-white">

            <h4>Elenco Argomenti</h4>

        </div>

        <div class="card-body">

            <?php if($result->num_rows > 0){ ?>

                <table class="table table-striped">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Nome</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while($row = $result->fetch_assoc()){ ?>

                            <tr>

                                <td>
                                    <?php echo $row["id_argomento"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row["nome"]); ?>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            <?php } else { ?>

                <div class="alert alert-warning">

                    Nessun argomento presente.

                </div>

            <?php } ?>

        </div>

    </div>

</div>

<?php

include "footer.php";

?>