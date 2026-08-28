<?php

require_once __DIR__ . "/../models/user.php";
require_once __DIR__ . "/../models/validator.php";

// Registra un nuovo utente e reindirizza con errore o successo
function registerUser($conn, array $data): void
{
    // Estrae e pulisce i dati
    $nome = trim($data['nome'] ?? '');
    $cognome = trim($data['cognome'] ?? '');
    $email = trim($data['email'] ?? '');
    $telefono = trim($data['telefono'] ?? '');
    $password = $data['password'] ?? '';
    $confirm = $data['conferma_password'] ?? '';

    // Valida nome e cognome
    if (empty($nome) || empty($cognome)) {
        redirect("register.php", "error", "missing_name");
    }

    // Valida email
    $emailValidation = Validator::validateEmail($email);
    if (!$emailValidation['valid']) {
        redirect("register.php", "error", $emailValidation['code']);
    }

    // Valida password
    $passwordValidation = Validator::validatePassword($password);
    if (!$passwordValidation['valid']) {
        redirect("register.php", "error", $passwordValidation['code']);
    }

    // Verifica corrispondenza password
    if ($password !== $confirm) {
        redirect("register.php", "error", "password_mismatch");
    }

    // Controlla se email esiste
    $user = new User($conn);
    if ($user->emailExists($email, 0)) {
        redirect("register.php", "error", "email_exists");
    }

    // Inserisce l'utente
    if ($user->register($nome, $cognome, $email, $telefono, $passwordValidation['password_hash'])) {
        redirect("login.php", "registered", "1");
    } else {
        redirect("register.php", "error", "registration_failed");
    }
}

// Reindirizza a una pagina con parametri di query
function redirect(string $page, string $param = "", string $value = ""): void
{
    $url = "../viewes/" . $page;
    if ($param && $value) {
        $url .= "?" . $param . "=" . urlencode($value);
    }
    header("Location: " . $url);
    exit();
}

// Ottiene il messaggio d'errore da visualizzare
function getErrorMessage(string $code): string
{
    $messages = [
        'email_required' => 'Email obbligatoria',
        'email_invalid' => 'Formato email non valido',
        'email_too_long' => 'Email troppo lunga',
        'email_exists' => 'Email già registrata',
        'password_required' => 'Password obbligatoria',
        'password_too_short' => 'Password troppo corta (minimo 8 caratteri)',
        'password_too_long' => 'Password troppo lunga',
        'password_no_uppercase' => 'Password deve contenere una maiuscola',
        'password_no_lowercase' => 'Password deve contenere una minuscola',
        'password_no_number' => 'Password deve contenere un numero',
        'password_no_special_char' => 'Password deve contenere un carattere speciale',
        'password_mismatch' => 'Le password non coincidono',
        'missing_name' => 'Nome e cognome obbligatori',
        'registration_failed' => 'Errore durante la registrazione',
        'method_not_allowed' => 'Metodo non consentito'
    ];

    return $messages[$code] ?? 'Errore sconosciuto';
}
