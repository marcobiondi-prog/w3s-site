<?php

require_once __DIR__ . "/conn.php";
require_once __DIR__ . "/../models/argomenti.php";

session_start();

// Verifica che l'utente sia loggato
if (!isset($_SESSION["user_id"])) {
    header("Location: ../viewes/login.php");
    exit();
}

// Accetta solo richieste POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../viewes/argomenti.php");
    exit();
}

// Recupera e pulisce il nome dell'argomento dal form
$nome = trim($_POST["nome"]);
$id_argomento = isset($_POST["id_argomento"]) ? (int)$_POST["id_argomento"] : null;

// Valida che il campo non sia vuoto
if (empty($nome)) {
    die("Inserisci il nome dell'argomento.");
}

// Se id_argomento è fornito, è una modifica
if ($id_argomento) {
    // Modifica argomento
    $argomento = new Argomenti($nome);
    $argomento->id_argomento = $id_argomento;
    
    if ($argomento->update()) {
        header("Location: ../viewes/argomenti.php?successo=modifica");
        exit();
    } else {
        // Controlla il motivo dell'errore
        if (Argomenti::findById($id_argomento) === null) {
            header("Location: ../viewes/argomenti.php?errore=non_trovato");
        } else {
            header("Location: ../viewes/argomenti.php?errore=duplicato");
        }
        exit();
    }
} else {
    // Nuovo argomento
    $argomento = new Argomenti($nome);
    
    if ($argomento->create()) {
        header("Location: ../viewes/argomenti.php?successo=inserimento");
        exit();
    } else {
        // L'errore è duplicato
        header("Location: ../viewes/argomenti.php?errore=duplicato");
        exit();
    }
}

?>