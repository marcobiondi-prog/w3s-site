-- Schema del database "w3s"
-- Importa questo file in phpMyAdmin (o con `mysql -u root w3s < database.sql`)
-- dopo aver creato il database vuoto "w3s".

CREATE DATABASE IF NOT EXISTS w3s CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE w3s;

-- Utenti registrati
CREATE TABLE IF NOT EXISTS utenti (
    id_utente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    google_id VARCHAR(255) NULL UNIQUE,
    facebook_id VARCHAR(255) NULL UNIQUE,
    numero_di_telefono VARCHAR(30) NULL,
    password VARCHAR(255) NULL,
    reset_token VARCHAR(255) NULL,
    reset_token_expiry DATETIME NULL,
    creato_il TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Argomenti (categorie)
CREATE TABLE IF NOT EXISTS argomenti (
    id_argomento INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Articoli
CREATE TABLE IF NOT EXISTS articoli (
    id_articolo INT AUTO_INCREMENT PRIMARY KEY,
    id_argomento INT NOT NULL,
    titolo VARCHAR(255) NOT NULL,
    corpo TEXT NOT NULL,
    pubblico TINYINT(1) NOT NULL DEFAULT 0,
    creato_il TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_argomento) REFERENCES argomenti(id_argomento)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- Esercizi
CREATE TABLE IF NOT EXISTS esercizi (
    id_esercizio INT AUTO_INCREMENT PRIMARY KEY,
    id_argomento INT NOT NULL,
    domanda TEXT NOT NULL,
    link VARCHAR(500),
    creato_il TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_argomento) REFERENCES argomenti(id_argomento)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- Risposte (4 per esercizio)
CREATE TABLE IF NOT EXISTS risposte (
    id_risposta INT AUTO_INCREMENT PRIMARY KEY,
    id_esercizio INT NOT NULL,
    testo VARCHAR(500) NOT NULL,
    corretta TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (id_esercizio) REFERENCES esercizi(id_esercizio)
        ON DELETE CASCADE
) ENGINE=InnoDB;
