<?php

class Validator
{
    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 128;
    const MIN_USERNAME_LENGTH = 3;
    const MAX_USERNAME_LENGTH = 50;

    // Valida il formato email e la lunghezza
    public static function validateEmail(string $email): array
    {
        $email = trim($email);

        if (empty($email)) {
            return [
                'valid' => false,
                'error' => 'Email is required',
                'code' => 'email_required'
            ];
        }

        // Utilizza FILTER_VALIDATE_EMAIL per controllare il formato RFC
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'error' => 'Invalid email format',
                'code' => 'email_invalid'
            ];
        }

        if (strlen($email) > 255) {
            return [
                'valid' => false,
                'error' => 'Email is too long',
                'code' => 'email_too_long'
            ];
        }

        return [
            'valid' => true,
            'email' => $email
        ];
    }

    // Valida la password con requisiti di sicurezza forte
    public static function validatePassword(string $password): array
    {
        if (empty($password)) {
            return [
                'valid' => false,
                'error' => 'Password is required',
                'code' => 'password_required'
            ];
        }

        $length = strlen($password);

        if ($length < self::MIN_PASSWORD_LENGTH) {
            return [
                'valid' => false,
                'error' => 'Password must be at least ' . self::MIN_PASSWORD_LENGTH . ' characters',
                'code' => 'password_too_short'
            ];
        }

        if ($length > self::MAX_PASSWORD_LENGTH) {
            return [
                'valid' => false,
                'error' => 'Password must not exceed ' . self::MAX_PASSWORD_LENGTH . ' characters',
                'code' => 'password_too_long'
            ];
        }

        // Controlla che la password contenga caratteri complessi (maiuscole, minuscole, numeri, simboli)
        $hasUppercase = preg_match('/[A-Z]/', $password);
        $hasLowercase = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSpecialChar = preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $password);

        if (!$hasUppercase) {
            return [
                'valid' => false,
                'error' => 'Password must contain at least one uppercase letter',
                'code' => 'password_no_uppercase'
            ];
        }

        if (!$hasLowercase) {
            return [
                'valid' => false,
                'error' => 'Password must contain at least one lowercase letter',
                'code' => 'password_no_lowercase'
            ];
        }

        if (!$hasNumber) {
            return [
                'valid' => false,
                'error' => 'Password must contain at least one number',
                'code' => 'password_no_number'
            ];
        }

        if (!$hasSpecialChar) {
            return [
                'valid' => false,
                'error' => 'Password must contain at least one special character',
                'code' => 'password_no_special_char'
            ];
        }

        // Ritorna l'hash della password già calcolato per evitare di farlo dopo
        return [
            'valid' => true,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT)
        ];
    }

    // Valida il nome utente con vincoli di formato e lunghezza
    public static function validateUsername(string $username): array
    {
        $username = trim($username);

        if (empty($username)) {
            return [
                'valid' => false,
                'error' => 'Username is required',
                'code' => 'username_required'
            ];
        }

        $length = strlen($username);

        if ($length < self::MIN_USERNAME_LENGTH) {
            return [
                'valid' => false,
                'error' => 'Username must be at least ' . self::MIN_USERNAME_LENGTH . ' characters',
                'code' => 'username_too_short'
            ];
        }

        if ($length > self::MAX_USERNAME_LENGTH) {
            return [
                'valid' => false,
                'error' => 'Username must not exceed ' . self::MAX_USERNAME_LENGTH . ' characters',
                'code' => 'username_too_long'
            ];
        }

        // Permette solo caratteri alfanumerici, punti, trattini e underscore
        if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) {
            return [
                'valid' => false,
                'error' => 'Username can only contain letters, numbers, dots, hyphens, and underscores',
                'code' => 'username_invalid_characters'
            ];
        }

        // Previene usernames che iniziano con numeri (ad es: "123user")
        if (preg_match('/^[0-9]/', $username)) {
            return [
                'valid' => false,
                'error' => 'Username cannot start with a number',
                'code' => 'username_starts_with_number'
            ];
        }

        return [
            'valid' => true,
            'username' => $username
        ];
    }

    // Controlla che le due password siano identiche
    public static function validatePasswordMatch(string $password, string $confirm_password): array
    {
        if ($password !== $confirm_password) {
            return [
                'valid' => false,
                'error' => 'Passwords do not match',
                'code' => 'password_mismatch'
            ];
        }

        return [
            'valid' => true
        ];
    }

    // Valuta la forza della password con uno score da 0 a 10
    public static function validatePasswordStrength(string $password): array
    {
        $score = 0;
        $feedback = [];

        // Lunghezza (massimo 2 punti)
        if (strlen($password) >= 12) {
            $score += 2;
        } elseif (strlen($password) >= 8) {
            $score += 1;
        }

        // Presenza di maiuscole, minuscole, numeri (1 punto ciascuno)
        if (preg_match('/[A-Z]/', $password)) {
            $score += 1;
        }

        if (preg_match('/[a-z]/', $password)) {
            $score += 1;
        }

        if (preg_match('/[0-9]/', $password)) {
            $score += 1;
        }

        // Caratteri speciali (2 punti)
        if (preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $password)) {
            $score += 2;
        }

        // Penalità: caratteri ripetuti consecutivi (es: "aaa")
        if (preg_match('/(.)\1{2,}/', $password)) {
            $score -= 2;
            $feedback[] = 'Avoid repeating characters';
        }

        // Penalità: solo numeri
        if (preg_match('/^\d+$/', $password)) {
            $score -= 3;
            $feedback[] = 'Avoid using only numbers';
        }

        $strength = $score <= 2 ? 'weak' : ($score <= 4 ? 'fair' : ($score <= 6 ? 'good' : 'strong'));

        return [
            'score' => max(0, $score),
            'strength' => $strength,
            'feedback' => $feedback
        ];
    }
}
