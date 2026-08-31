<?php
// Carica e inizializza la connessione globale al database.
require_once __DIR__ . '/../controllers/conn.php';

// Modello che gestisce registrazione, accesso e profilo degli utenti.
class User {

    // Dati principali dell'utente.
    public $id;
    public $nome;
    public $cognome;
    public $email;
    public $telefono;
    public $password;

    // Inizializza un utente; supporta sia parametri singoli che array dal database.
    public function __construct($nome = "", $cognome = "", $email = "", $telefono = "", $password = "") {
        // Se il primo parametro è un array (da database), carica i dati da esso
        if (is_array($nome)) {
            $data = $nome;
            $this->id = $data['id_utente'] ?? null;
            $this->nome = $data['nome'] ?? "";
            $this->cognome = $data['cognome'] ?? "";
            $this->email = $data['email'] ?? "";
            $this->telefono = $data['numero_di_telefono'] ?? "";
            $this->password = $data['password'] ?? "";
        } else {
            // Parametri singoli (modo tradizionale)
            $this->nome = $nome;
            $this->cognome = $cognome;
            $this->email = $email;
            $this->telefono = $telefono;
            $this->password = $password;
        }
    }

    // Inserisce nel database l'utente rappresentato dall'oggetto.
    public function register() {
        global $conn;
        $sql = "INSERT INTO utenti (nome, cognome, email, numero_di_telefono, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $this->nome, $this->cognome, $this->email, $this->telefono, $this->password);
        $stmt->execute();
        return $stmt->affected_rows > 0;

    }

    // Verifica le credenziali e restituisce i dati dell'utente, oppure false.
    public static function authenticate($email, $password) {
        global $conn;
        $sql = "SELECT * FROM utenti WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && password_verify($password, $user["password"])) {
            return $user;
        }
        return false;
    }

    // Controlla se l'email dell'oggetto e' gia registrata.
    public function checkRegister() {
        global $conn;
        $sql = "SELECT id_utente FROM utenti WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Email gia registrata.
           return true;
        }else{
            // Email disponibile.
            return false;
        }

    }

    // Recupera i dati del profilo tramite ID, senza includere la password.
    public function findById(int $userId): ?array {
        global $conn;
        $stmt = $conn->prepare("SELECT id_utente, nome, cognome, email, numero_di_telefono FROM utenti WHERE id_utente = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $utente = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        return $utente;
    }

    // Verifica che l'email non sia usata da un altro utente.
    public function emailExists(string $email, int $userId): bool {
        global $conn;
        $stmt = $conn->prepare("SELECT id_utente FROM utenti WHERE email = ? AND id_utente != ?");
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        $esiste = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        return $esiste;
    }

    // Aggiorna i dati modificabili del profilo dell'utente.
    public function updateProfile(int $userId, string $nome, string $cognome, string $email, string $telefono): bool {
        global $conn;
        $stmt = $conn->prepare("UPDATE utenti SET nome = ?, cognome = ?, email = ?, numero_di_telefono = ? WHERE id_utente = ?");
        $stmt->bind_param("ssssi", $nome, $cognome, $email, $telefono, $userId);
        $stmt->execute();
        $aggiornato = $stmt->affected_rows >= 0;
        $stmt->close();

        return $aggiornato;
    }

}

