<?php

// Trova (o crea) l'utente collegato a un account Google/Facebook e apre la sessione.
function login_or_register_oauth($conn, $provider_column, $provider_id, $email, $nome, $cognome) {

    $sql = "SELECT * FROM utenti WHERE $provider_column = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $provider_id);
    $stmt->execute();
    $utente = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$utente) {

        // Nessun account ancora collegato a questo provider: controlla se l'email esiste già
        $sql = "SELECT * FROM utenti WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $utente = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($utente) {

            // Collega il provider all'account esistente
            $sql = "UPDATE utenti SET $provider_column = ? WHERE id_utente = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $provider_id, $utente["id_utente"]);
            $stmt->execute();
            $stmt->close();

        } else {

            // Crea un nuovo utente collegato al provider
            $sql = "INSERT INTO utenti (nome, cognome, email, $provider_column)
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nome, $cognome, $email, $provider_id);
            $stmt->execute();

            $utente = [
                "id_utente" => $conn->insert_id,
                "nome" => $nome,
                "cognome" => $cognome,
                "email" => $email,
            ];

            $stmt->close();

        }
    }

    $_SESSION["user_id"] = $utente["id_utente"];
    $_SESSION["nome"] = $utente["nome"];
    $_SESSION["cognome"] = $utente["cognome"];
    $_SESSION["email"] = $utente["email"];

    header("Location: dashboard.php");
    exit();
}
