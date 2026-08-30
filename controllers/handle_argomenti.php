<?php

require_once __DIR__ . "/conn.php";
session_start();
// Verifica che l'utente sia loggato (requisito di autorizzazione)
if (!isset($_SESSION["user_id"])) {
    header("Location: viewes/login.php");
    exit();
}

// Accetta solo richieste POST (form submission)
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: viewes/argomenti.php");
    exit();
}

// Recupera e pulisce il nome dell'argomento dal form
$nome = trim($_POST["nome"]);

// Valida che il campo non sia vuoto
if (empty($nome)) {
    die("Inserisci il nome dell'argomento.");
}

// Controlla se l'argomento esiste già (previene duplicati)
$sql = "SELECT id_argomento FROM argomenti WHERE nome = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nome);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    $conn->close();
    header("Location: viewes/argomenti.php?errore=duplicato");
    exit();
}

$stmt->close();

// Inserisce il nuovo argomento nel database
$sql = "INSERT INTO argomenti (nome) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nome);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: viewes/argomenti.php?successo=inserimento");
    exit();
} else {
    die("Errore durante il salvataggio dell'argomento.");
}

$conn->close();

?>