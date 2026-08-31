<?php

require_once __DIR__ . "/conn.php";
session_start();
// Verifica che l'utente sia loggato (requisito di autorizzazione)
if (!isset($_SESSION["user_id"])) {
    header("Location: ../viewes/login.php");
    exit();
}

// Accetta solo richieste POST (form submission)
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
    // Verifica che l'argomento esista
    $sql = "SELECT id_argomento FROM argomenti WHERE id_argomento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_argomento);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        $conn->close();
        header("Location: ../viewes/argomenti.php?errore=non_trovato");
        exit();
    }
    
    $stmt->close();
    
    // Controlla se il nuovo nome esiste già (escludendo l'argomento corrente)
    $sql = "SELECT id_argomento FROM argomenti WHERE nome = ? AND id_argomento != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nome, $id_argomento);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header("Location: ../viewes/argomenti.php?errore=duplicato");
        exit();
    }
    
    $stmt->close();
    
    // Aggiorna il nome dell'argomento
    $sql = "UPDATE argomenti SET nome = ? WHERE id_argomento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nome, $id_argomento);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../viewes/argomenti.php?successo=modifica");
        exit();
    } else {
        die("Errore durante l'aggiornamento dell'argomento.");
    }
} else {
    // Nuovo argomento
    // Controlla se l'argomento esiste già (previene duplicati)
    $sql = "SELECT id_argomento FROM argomenti WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header("Location: ../viewes/argomenti.php?errore=duplicato");
        exit();
    }

    $stmt->close();

    // Inserisce il nuovo argomento nel database
    $sql = "INSERT INTO argomenti (nome) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../viewes/argomenti.php?successo=inserimento");
        exit();
    } else {
        die("Errore durante il salvataggio dell'argomento.");
    }
}

?>