<?php

require_once __DIR__ . "/conn.php";

function eliminaArgomento(int $idArgomento): bool
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM argomenti WHERE id_argomento = ?");
    $stmt->bind_param("i", $idArgomento);
    $stmt->execute();
    $eliminato = $stmt->affected_rows > 0;
    $stmt->close();
    return $eliminato;
}

if (!isset($_SESSION["user_id"]) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../viewes/argomenti.php");
    exit();
}

$idArgomento = $_POST["id_argomento"] ?? "";

if (!ctype_digit($idArgomento)) {
    header("Location: ../viewes/argomenti.php?errore=eliminazione");
    exit();
}

$eliminato = eliminaArgomento((int) $idArgomento);
$conn->close();
header("Location: ../viewes/argomenti.php?" . ($eliminato ? "successo=eliminazione" : "errore=eliminazione"));
exit();