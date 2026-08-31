<?php
// Carica e inizializza la connessione globale al database.
require_once __DIR__ . '/../controllers/conn.php';

// Modello che gestisce gli articoli.
class Articolo {

    // Dati dell'articolo
    public $id_articolo;
    public $id_argomento;
    public $titolo;
    public $corpo;
    public $pubblico;

    // Inizializza un articolo; supporta sia parametri singoli che array dal database.
    public function __construct($id_argomento = "", $titolo = "", $corpo = "", $pubblico = 0) {
        // Se il primo parametro è un array (da database), carica i dati da esso
        if (is_array($id_argomento)) {
            $data = $id_argomento;
            $this->id_articolo = $data['id_articolo'] ?? null;
            $this->id_argomento = $data['id_argomento'] ?? "";
            $this->titolo = $data['titolo'] ?? "";
            $this->corpo = $data['corpo'] ?? "";
            $this->pubblico = $data['pubblico'] ?? 0;
        } else {
            // Parametri singoli (modo tradizionale)
            $this->id_argomento = $id_argomento;
            $this->titolo = $titolo;
            $this->corpo = $corpo;
            $this->pubblico = $pubblico;
        }
    }

    // Inserisce nel database l'articolo rappresentato dall'oggetto.
    public function create() {
        global $conn;
        
        if (empty($this->id_argomento) || empty($this->titolo) || empty($this->corpo)) {
            return false;
        }
        
        // Controlla se esiste già un articolo con lo stesso titolo nello stesso argomento
        if ($this->exists()) {
            return false;
        }
        
        $sql = "INSERT INTO articoli (id_argomento, titolo, corpo, pubblico) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $this->id_argomento, $this->titolo, $this->corpo, $this->pubblico);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    // Aggiorna l'articolo nel database.
    public function update() {
        global $conn;
        
        if (empty($this->id_articolo) || empty($this->id_argomento) || empty($this->titolo) || empty($this->corpo)) {
            return false;
        }
        
        // Verifica che l'articolo esista
        if (!$this->findById($this->id_articolo)) {
            return false;
        }
        
        $sql = "UPDATE articoli SET id_argomento = ?, titolo = ?, corpo = ?, pubblico = ? WHERE id_articolo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issii", $this->id_argomento, $this->titolo, $this->corpo, $this->pubblico, $this->id_articolo);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    // Elimina l'articolo dal database.
    public function delete() {
        global $conn;
        
        if (empty($this->id_articolo)) {
            return false;
        }
        
        // Verifica che l'articolo esista
        if (!$this->findById($this->id_articolo)) {
            return false;
        }
        
        $sql = "DELETE FROM articoli WHERE id_articolo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $this->id_articolo);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    // Verifica se un articolo con questo titolo esiste già nello stesso argomento.
    public function exists() {
        global $conn;
        $sql = "SELECT id_articolo FROM articoli WHERE id_argomento = ? AND titolo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $this->id_argomento, $this->titolo);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        return $result->num_rows > 0;
    }

    // Recupera un articolo tramite ID.
    public static function findById($id) {
        global $conn;
        $sql = "SELECT * FROM articoli WHERE id_articolo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $articolo = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        return $articolo;
    }

    // Recupera tutti gli articoli di un argomento.
    public static function getByArgomento($id_argomento, $orderBy = "id_articolo DESC") {
        global $conn;
        $sql = "SELECT * FROM articoli WHERE id_argomento = ? ORDER BY " . $orderBy;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_argomento);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Recupera tutti gli articoli.
    public static function getAll($orderBy = "id_articolo DESC") {
        global $conn;
        $sql = "SELECT * FROM articoli ORDER BY " . $orderBy;
        $result = $conn->query($sql);
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Recupera tutti gli articoli pubblici.
    public static function getPublic($orderBy = "id_articolo DESC") {
        global $conn;
        $sql = "SELECT * FROM articoli WHERE pubblico = 1 ORDER BY " . $orderBy;
        $result = $conn->query($sql);
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
