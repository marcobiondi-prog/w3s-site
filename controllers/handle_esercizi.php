<?php

require_once __DIR__ . "/conn.php";

// Verifica autenticazione: solo utenti loggati possono creare esercizi
if (!isset($_SESSION["user_id"])) {
    header("Location: viewes/login.php");
    exit();
}

// Accetta solo richieste POST (form submission)
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: viewes/esercizi.php");
    exit();
}

// Recupera e pulisce i dati dal form
$id_argomento = $_POST["id_argomento"];
$domanda = trim($_POST["domanda"]);
$link = trim($_POST["link"]);

// Array con le 4 risposte possibili
$risposte = [
    trim($_POST["risposta1"]),
    trim($_POST["risposta2"]),
    trim($_POST["risposta3"]),
    trim($_POST["risposta4"])
];

// Indica quale risposta è corretta (1-4)
$corretta = $_POST["corretta"];

// Valida campi obbligatori
if (
    empty($id_argomento) ||
    empty($domanda)
) {
    die("Compila tutti i campi obbligatori.");
}

// Avvia una transazione per garantire coerenza dei dati tra esercizi e risposte
$conn->begin_transaction();

try {
    // Inserisce l'esercizio principale
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

    // Recupera l'ID dell'esercizio appena creato (auto-incrementato)
    $id_esercizio = $conn->insert_id;
    $stmt->close();

    // Inserisce le 4 risposte per questo esercizio
    $sql = "INSERT INTO risposte
            (id_esercizio, testo, corretta)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    for ($i = 0; $i < 4; $i++) {
        // Se l'indice corrisponde alla risposta corretta, marca come 1, altrimenti 0
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

    // Conferma tutte le operazioni se tutto è andato a buon fine
    $conn->commit();

    header("Location: viewes/esercizi.php");
    exit();

} catch (Exception $e) {
    // Se c'è un errore, annulla tutto (rollback) per mantenere l'integrità dei dati
    $conn->rollback();
    die("Errore durante il salvataggio dell'esercizio.");
}

$conn->close();

?>