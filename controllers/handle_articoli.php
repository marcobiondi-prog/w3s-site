<?php

require_once "conn.php";
session_start();
// Controllo della sessione
if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

// Controllo del metodo POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: articoli.php");
    exit();

}

// Recupero dei dati
$id_argomento = $_POST["id_argomento"];
$titolo = trim($_POST["titolo"]);
$contenuto = trim($_POST["contenuto"]);
$link = trim($_POST["link"]);
$private = trim($POST["private"]);

// Controllo dei campi obbligatori
if (
    empty($id_argomento) ||
    empty($titolo) ||
    empty($contenuto) ||
    empty($link) ||
    !is_numeric($private) ||
) {

    die("Compila tutti i campi.");

}

// Inserimento dell'articolo
$sql = "INSERT INTO articoli
        (titolo, corpo, id_argomento, link, private)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssisi",
    $titolo,
    $contenuto,
    $id_argomento,
    $link,
    $private
);

if ($stmt->execute()) {

    $stmt->close();

    header("Location: articoli.php");
    exit();

} else {

    die("Errore durante il salvataggio dell'articolo.");

}

$conn->close();

?>