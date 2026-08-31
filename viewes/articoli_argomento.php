<?php
/**
 * Pagina di visualizzazione articoli per un argomento specifico
 * 
 * Questa pagina visualizza tutti gli articoli associati a un argomento particolare.
 * Include controlli di autenticazione e validazione dell'ID argomento.
 */

require_once __DIR__ . "/../controllers/conn.php";

// Controllo autenticazione: reindirizza a login se l'utente non è autenticato
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Recupera l'ID dell'argomento dal parametro GET, usa stringa vuota se non presente
$idArgomento = $_GET["id"] ?? "";

// Valida che l'ID sia un numero intero positivo (ctype_digit verifica solo cifre)
if (!ctype_digit($idArgomento)) {
    header("Location: argomenti.php");
    exit();
}

// Converte l'ID da stringa a intero
$idArgomento = (int) $idArgomento;

// Query preparata per recuperare il nome dell'argomento
// Utilizza query preparata per prevenire SQL injection
$stmt = $conn->prepare("SELECT nome FROM argomenti WHERE id_argomento = ?");
$stmt->bind_param("i", $idArgomento);
$stmt->execute();
$argomento = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Se l'argomento non esiste, reindirizza alla pagina argomenti
if (!$argomento) {
    header("Location: argomenti.php");
    exit();
}

// Query preparata per recuperare tutti gli articoli dell'argomento
// Ordinati per ID decrescente (articoli più recenti prima)
$stmt = $conn->prepare("SELECT id_articolo, titolo, pubblico FROM articoli WHERE id_argomento = ? ORDER BY id_articolo DESC");
$stmt->bind_param("i", $idArgomento);
$stmt->execute();
$articoli = $stmt->get_result();

// Imposta il titolo della pagina e include l'header
$title = "Articoli - " . $argomento["nome"];
include __DIR__ . "/header.php";
?>

<main class="container mt-5 mb-5">
    <!-- Intestazione della pagina con titolo e pulsante per tornare agli argomenti -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Articoli: <?= htmlspecialchars($argomento["nome"]) ?></h1>
        <a href="argomenti.php" class="btn btn-secondary">Torna agli argomenti</a>
    </div>

    <!-- Verifica se ci sono articoli da visualizzare -->
    <?php if ($articoli->num_rows > 0) { ?>
        <!-- Lista di articoli -->
        <div class="list-group">
            <!-- Ciclo attraverso ogni articolo -->
            <?php while ($articolo = $articoli->fetch_assoc()) { ?>
                <!-- Link all'articolo con titolo e badge di visibilità -->
                <a href="articolo.php?id=<?= $articolo["id_articolo"] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <!-- Titolo articolo (htmlspecialchars per prevenire XSS) -->
                    <?= htmlspecialchars($articolo["titolo"]) ?>
                    <!-- Badge che indica se l'articolo è pubblico o privato -->
                    <span class="badge <?= $articolo["pubblico"] ? "bg-success" : "bg-secondary" ?>">
                        <?= $articolo["pubblico"] ? "Pubblico" : "Privato" ?>
                    </span>
                </a>
            <?php } ?>
        </div>
    <?php } else { ?>
        <!-- Messaggio quando non ci sono articoli per l'argomento -->
        <div class="alert alert-warning">Nessun articolo per questo argomento.</div>
    <?php } ?>
</main>

<?php
// Chiude lo statement della query degli articoli
$stmt->close();

// Chiude la connessione al database
$conn->close();

// Include il footer della pagina
include __DIR__ . "/footer.php";
?>