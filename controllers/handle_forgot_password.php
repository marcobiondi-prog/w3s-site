<?php

require_once __DIR__ . "/conn.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../vendor/autoload.php";

// Controlla che il form sia stato inviato tramite POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../viewes/forgot_password.php");
    exit();
}

// Recupera i dati
$email = trim($_POST["email"]);

// Controllo campi vuoti
if (empty($email)) {
    header("Location: ../viewes/forgot_password.php?error=empty_email");
    exit();
}

// Cerca l'utente tramite email
$sql = "SELECT id_utente, nome, email FROM utenti WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Controlla se l'utente esiste
if ($result->num_rows == 1) {
    $utente = $result->fetch_assoc();

    // Genera un token casuale
    $token = bin2hex(random_bytes(32));

    // Token scade tra 1 ora
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Salva il token nel database
    $sql_update = "UPDATE utenti SET reset_token = ?, reset_token_expiry = ? WHERE id_utente = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssi", $token, $expiry, $utente["id_utente"]);
    $stmt_update->execute();
    $stmt_update->close();

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000';
    $script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $base_path = rtrim($script_dir, '/');
    $base_url = $scheme . '://' . $host . ($base_path === '' || $base_path === '/' ? '' : $base_path);
    $reset_link = rtrim($base_url, '/') . '/viewes/reset_password.php?token=' . urlencode($token);

    $smtpConfig = require __DIR__ . "/../config/smtp.php";

    if (empty($smtpConfig['enabled']) || empty($smtpConfig['username']) || empty($smtpConfig['password'])) {
        $_SESSION['reset_debug_link'] = $reset_link;
        header("Location: ../viewes/forgot_password.php?success=1&debug=1");
        exit();
    }

    try {
        $mail = new PHPMailer(true);

        if (!empty($smtpConfig['host']) && !empty($smtpConfig['username']) && !empty($smtpConfig['password']) && $smtpConfig['enabled']) {
            $mail->isSMTP();
            $mail->Host = $smtpConfig['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $smtpConfig['username'];
            $mail->Password = $smtpConfig['password'];

            if (strtolower((string) $smtpConfig['encryption']) === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif (strtolower((string) $smtpConfig['encryption']) === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Port = $smtpConfig['port'];
        }

        $mail->CharSet = 'UTF-8';
        $mail->setFrom($smtpConfig['from_email'], $smtpConfig['from_name']);
        $mail->addAddress($email, $utente['nome']);
        $mail->isHTML(true);
        $mail->Subject = 'W3S - Recupero Password';
        $mail->Body = '<p>Ciao ' . htmlspecialchars($utente['nome']) . ',</p>'
            . '<p>Hai richiesto il recupero della password per il tuo account W3S.</p>'
            . '<p><a href="' . htmlspecialchars($reset_link) . '">Clicca qui per reimpostare la tua password</a></p>'
            . '<p>Questo link scade tra 1 ora.</p>'
            . '<p>Se non hai richiesto il recupero della password, ignora questa email.</p>';

        $mail->send();
        header("Location: ../viewes/forgot_password.php?success=1");
        exit();
    } catch (Exception $e) {
        $_SESSION['reset_debug_link'] = $reset_link;
        header("Location: ../viewes/forgot_password.php?success=1&debug=1");
        exit();
    }
} else {
    // Email non trovata
    header("Location: ../viewes/forgot_password.php?error=email_not_found");
    exit();
}

$stmt->close();
$conn->close();

?>
