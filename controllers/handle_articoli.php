<?php

require_once __DIR__ . "/conn.php";
require_once __DIR__ . "/../models/articolo.php";

session_start();

// Verifica autenticazione: solo utenti loggati possono creare/modificare articoli
if (!isset($_SESSION["user_id"])) {
    header("Location: ../viewes/login.php");
    exit();
}

// Accetta solo richieste POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../viewes/articoli.php");
    exit();
}

// Recupera e pulisce i dati dal form
$id_articolo = isset($_POST["id_articolo"]) ? (int)$_POST["id_articolo"] : 0;
$id_argomento = (int)$_POST["id_argomento"];
$titolo = trim($_POST["titolo"]);
$contenuto = trim($_POST["contenuto"]);
$pubblico = isset($_POST["pubblico"]) ? (int)$_POST["pubblico"] : 0;

// Valida che tutti i campi obbligatori siano compilati
if (empty($id_argomento) || empty($titolo) || empty($contenuto)) {
    die("Compila tutti i campi obbligatori.");
}

// Se id_articolo > 0, si tratta di una modifica
if ($id_articolo > 0) {
    // Modifica articolo
    $articolo = new Articolo($id_argomento, $titolo, $contenuto, $pubblico);
    $articolo->id_articolo = $id_articolo;
    
    if ($articolo->update()) {
        header("Location: ../viewes/articoli.php?successo=1");
        exit();
    } else {
        die("Errore durante l'aggiornamento dell'articolo.");
    }
} else {
    // Nuovo articolo
    $articolo = new Articolo($id_argomento, $titolo, $contenuto, $pubblico);
    
    if ($articolo->create()) {
        header("Location: ../viewes/articoli.php?successo=1");
        exit();
    } else {
        // L'errore è duplicato
        header("Location: ../viewes/articoli.php?errore=duplicato");
        exit();
    }
}

?>