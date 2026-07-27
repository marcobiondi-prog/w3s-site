<?php

require_once "conn.php";

// Controllo della sessione
if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

// Controllo del metodo POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: esercizi.php");
    exit();

}

// Recupero dei dati
$id_argomento = $_POST["id_argomento"];
$domanda = trim($_POST["domanda"]);
$link = trim($_POST["link"]);

$risposte = [
    trim($_POST["risposta1"]),
    trim($_POST["risposta2"]),
    trim($_POST["risposta3"]),
    trim($_POST["risposta4"])
];

$corretta = $_POST["corretta"];

// Controllo campi obbligatori
if (
    empty($id_argomento) ||
    empty($domanda)
) {
    die("Compila tutti i campi obbligatori.");
}

// Avvio della transazione
$conn->begin_transaction();

try {

    // Inserimento dell'esercizio
    $sql = "INSERT INTO esercizi (id_argomento, domanda, link)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iss",
        $id_argomento,
        $domanda,
        $link
    );

    $stmt->execute();

    // Recupera l'ID dell'esercizio appena creato
    $id_esercizio = $conn->insert_id;

    $stmt->close();

    // Inserimento delle quattro risposte
    $sql = "INSERT INTO risposte
            (id_esercizio, testo, corretta)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    for ($i = 0; $i < 4; $i++) {

        $isCorretta = ($corretta == ($i + 1)) ? 1 : 0;

        $stmt->bind_param(
            "isi",
            $id_esercizio,
            $risposte[$i],
            $isCorretta
        );

        $stmt->execute();

    }

    $stmt->close();

    // Conferma tutte le operazioni
    $conn->commit();

    header("Location: esercizi.php");
    exit();

} catch (Exception $e) {

    // Annulla tutto in caso di errore
    $conn->rollback();

    die("Errore durante il salvataggio dell'esercizio.");

}

$conn->close();

?>