<?php

class User
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // Recupera un utente dal database tramite ID
    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT id_utente, nome, cognome, email, numero_di_telefono
             FROM utenti WHERE id_utente = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        return $user;
    }

    // Controlla se un'email è già registrata nel database
    public function checkregister(string $email): bool
    {
        $stmt = $this->conn->prepare("SELECT id_utente FROM utenti WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    // Controlla se un'email esiste escludendo un utente specifico (utile per profilo update)
    public function emailExists(string $email, int $excludeId): bool
    {
        $stmt = $this->conn->prepare(
            "SELECT id_utente FROM utenti WHERE email = ? AND id_utente <> ?"
        );
        $stmt->bind_param("si", $email, $excludeId);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    // Aggiorna il profilo dell'utente
    public function updateProfile(
        int $id,
        string $nome,
        string $cognome,
        string $email,
        string $telefono
    ): bool {
        $stmt = $this->conn->prepare(
            "UPDATE utenti
             SET nome = ?, cognome = ?, email = ?, numero_di_telefono = NULLIF(?, '')
             WHERE id_utente = ?"
        );
        $stmt->bind_param("ssssi", $nome, $cognome, $email, $telefono, $id);
        $updated = $stmt->execute();
        $stmt->close();

        return $updated;
    }
}