<?php

require_once __DIR__ . "/includes/models/conn.php";

// Controllo della sessione
if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

// Controlla che il form sia stato inviato tramite POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: argomenti.php");
    exit();

}

// Recupera il nome dell'argomento
$nome = trim($_POST["nome"]);

// Controlla che il campo non sia vuoto
if (empty($nome)) {

    die("Inserisci il nome dell'argomento.");

}

// Controlla se l'argomento esiste già
$sql = "SELECT id_argomento FROM argomenti WHERE nome = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $nome);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $stmt->close();
    $conn->close();
    header("Location: argomenti.php?errore=duplicato");
    exit();

}

$stmt->close();

// Inserimento del nuovo argomento
$sql = "INSERT INTO argomenti (nome) VALUES (?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $nome);

if ($stmt->execute()) {

    $stmt->close();

    header("Location: argomenti.php?successo=inserimento");
    exit();

} else {

    die("Errore durante il salvataggio dell'argomento.");

}

$conn->close();

?>