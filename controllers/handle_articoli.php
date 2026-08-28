<?php

require_once __DIR__ . "/conn.php";

// Verifica autenticazione: solo utenti loggati possono creare/modificare articoli
if (!isset($_SESSION["user_id"])) {
    header("Location: viewes/login.php");
    exit();
}

// Accetta solo richieste POST (form submission)
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: viewes/articoli.php");
    exit();
}

// Recupera e pulisce i dati dal form
$id_argomento = $_POST["id_argomento"];
$titolo = trim($_POST["titolo"]);
$contenuto = trim($_POST["contenuto"]);
$id_articolo = isset($_POST["id_articolo"]) ? intval($_POST["id_articolo"]) : 0;
$pubblico = isset($_POST["pubblico"]) ? intval($_POST["pubblico"]) : 0;

// Valida che tutti i campi obbligatori siano compilati
if (
    empty($id_argomento) ||
    empty($titolo) ||
    empty($contenuto)
) {
    die("Compila tutti i campi.");
}

// Se id_articolo > 0, si tratta di una modifica di un articolo esistente
if ($id_articolo > 0) {
    // UPDATE per modifica articolo esistente
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
    // Se id_articolo = 0, si tratta di una nuova creazione: controlla se esiste già un articolo con lo stesso titolo nello stesso argomento
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
        header("Location: viewes/articoli.php?errore=duplicato");
        exit();
    }

    $check_stmt->close();

    // INSERT per nuovo articolo
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

// Esegue la query e reindirizza con messaggio di successo
if ($stmt->execute()) {
    $stmt->close();

    $successo = $id_articolo > 0 ? "modifica" : "inserimento";
    header("Location: viewes/articoli.php?successo=" . $successo);
    exit();
} else {
    die("Errore durante il salvataggio dell'articolo.");
}

$conn->close();

?>