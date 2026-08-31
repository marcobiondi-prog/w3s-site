<?php
// Carica e inizializza la connessione globale al database.
require_once __DIR__ . '/../controllers/conn.php';

// Modello che gestisce gli argomenti.
class Argomenti {

    // Dati dell'argomento
    public $id_argomento;
    public $nome;

    // Inizializza un argomento; supporta sia parametri singoli che array dal database.
    public function __construct($nome = "") {
        // Se il primo parametro è un array (da database), carica i dati da esso
        if (is_array($nome)) {
            $data = $nome;
            $this->id_argomento = $data['id_argomento'] ?? null;
            $this->nome = $data['nome'] ?? "";
        } else {
            // Parametro singolo (modo tradizionale)
            $this->nome = $nome;
        }
    }

    // Inserisce nel database l'argomento rappresentato dall'oggetto.
    public function create() {
        global $conn;
        
        if (empty($this->nome)) {
            return false;
        }
        
        // Controlla se l'argomento esiste già (previene duplicati)
        if ($this->exists()) {
            return false;
        }
        
        $sql = "INSERT INTO argomenti (nome) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $this->nome);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    // Aggiorna il nome dell'argomento nel database.
    public function update() {
        global $conn;
        
        if (empty($this->id_argomento) || empty($this->nome)) {
            return false;
        }
        
        // Verifica che l'argomento esista
        if (!$this->findById($this->id_argomento)) {
            return false;
        }
        
        // Controlla se il nuovo nome esiste già (escludendo l'argomento corrente)
        $sql = "SELECT id_argomento FROM argomenti WHERE nome = ? AND id_argomento != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $this->nome, $this->id_argomento);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        if ($result->num_rows > 0) {
            return false;
        }
        
        // Aggiorna il nome
        $sql = "UPDATE argomenti SET nome = ? WHERE id_argomento = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $this->nome, $this->id_argomento);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    // Elimina l'argomento dal database.
    public function delete() {
        global $conn;
        
        if (empty($this->id_argomento)) {
            return false;
        }
        
        // Verifica che l'argomento esista
        if (!$this->findById($this->id_argomento)) {
            return false;
        }
        
        $sql = "DELETE FROM argomenti WHERE id_argomento = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $this->id_argomento);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    // Verifica se un argomento con questo nome esiste già.
    public function exists() {
        global $conn;
        $sql = "SELECT id_argomento FROM argomenti WHERE nome = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $this->nome);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        return $result->num_rows > 0;
    }

    // Recupera un argomento tramite ID.
    public static function findById($id) {
        global $conn;
        $sql = "SELECT * FROM argomenti WHERE id_argomento = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $argomento = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        return $argomento;
    }

    // Recupera tutti gli argomenti ordinati.
    public static function getAll($orderBy = "nome ASC") {
        global $conn;
        $sql = "SELECT * FROM argomenti ORDER BY " . $orderBy;
        $result = $conn->query($sql);
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
