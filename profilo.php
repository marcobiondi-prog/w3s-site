<?php

require_once "conn.php";

// Controllo della sessione
if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

$title = "Profilo";

include "header.php";

// Recupera i dati dell'utente
$sql = "SELECT nome, cognome, email, numero_di_telefono FROM utenti WHERE id_utente = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $_SESSION["user_id"]);

$stmt->execute();

$utente = $stmt->get_result()->fetch_assoc();

$stmt->close();

?>

<div class="container mt-5">

    <h1 class="mb-4">Il mio profilo</h1>

    <div class="card shadow">

        <div class="card-body">

            <dl class="row mb-0">

                <dt class="col-sm-3">Nome</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($utente["nome"]); ?></dd>

                <dt class="col-sm-3">Cognome</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($utente["cognome"]); ?></dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($utente["email"]); ?></dd>

                <dt class="col-sm-3">Telefono</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($utente["numero_di_telefono"]); ?></dd>

            </dl>

        </div>

    </div>

</div>

<?php

$conn->close();

include "footer.php";

?>
