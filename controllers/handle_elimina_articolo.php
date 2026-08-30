<?php

require_once __DIR__ . "/conn.php";

function eliminaArticolo(int $idArticolo): bool
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM articoli WHERE id_articolo = ?");
    $stmt->bind_param("i", $idArticolo);
    $stmt->execute();
    $eliminato = $stmt->affected_rows > 0;
    $stmt->close();
    return $eliminato;
}

if (!isset($_SESSION["user_id"]) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit();
}

$idArticolo = $_POST["id_articolo"] ?? "";

if (!ctype_digit($idArticolo)) {
    header("Location: ../index.php?errore=articolo");
    exit();
}

$eliminato = eliminaArticolo((int) $idArticolo);
$conn->close();
header("Location: ../index.php?" . ($eliminato ? "successo=articolo_eliminato" : "errore=articolo"));
exit();