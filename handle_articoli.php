<?php

require_once __DIR__ . "/includes/models/conn.php";

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
$id_articolo = isset($_POST["id_articolo"]) ? intval($_POST["id_articolo"]) : 0;
$pubblico = isset($_POST["pubblico"]) ? intval($_POST["pubblico"]) : 0;

// Controllo dei campi obbligatori
if (
    empty($id_argomento) ||
    empty($titolo) ||
    empty($contenuto)
) {

    die("Compila tutti i campi.");

}

if ($id_articolo > 0) {

    // Modifica di un articolo esistente
    $sql = "UPDATE articoli
            SET id_argomento = ?, titolo = ?, corpo = ?, pubblico = ?
            WHERE id_articolo = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "issii",
        $id_argomento,
        $titolo,
        $contenuto,
        $pubblico,
        $id_articolo
    );

} else {

    $check_sql = "SELECT id_articolo
                  FROM articoli
                  WHERE id_argomento = ? AND titolo = ?
                  LIMIT 1";

    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $id_argomento, $titolo);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $check_stmt->close();
        $conn->close();
        header("Location: articoli.php?errore=duplicato");
        exit();
    }

    $check_stmt->close();

    // Inserimento di un nuovo articolo
    $sql = "INSERT INTO articoli
            (id_argomento, titolo, corpo, pubblico)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "issi",
        $id_argomento,
        $titolo,
        $contenuto,
        $pubblico
    );

}

if ($stmt->execute()) {

    $stmt->close();

    header("Location: articoli.php");
    exit();

} else {

    die("Errore durante il salvataggio dell'articolo.");

}

$conn->close();

?>