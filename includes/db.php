<?php
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";      // À modifier selon votre configuration
$password = "root";          // À modifier selon votre configuration
$dbname = "nutrivie_db"; // Nom de la base de données

// Création de la connexion
$conn = new mysqli($servername, $username, $password);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Création de la base de données si elle n'existe pas
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === FALSE) {
    die("Erreur lors de la création de la base de données : " . $conn->error);
}

// Sélection de la base de données
$conn->select_db($dbname);

// Création des tables si elles n'existent pas
$tables = [
    "CREATE TABLE IF NOT EXISTS utilisateurs (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(50) NOT NULL,
        prenom VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(255) NOT NULL,
        date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
        role ENUM('utilisateur', 'admin') DEFAULT 'utilisateur'
    )",
    "CREATE TABLE IF NOT EXISTS articles (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        titre VARCHAR(255) NOT NULL,
        contenu TEXT NOT NULL,
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
        id_auteur INT(11),
        FOREIGN KEY (id_auteur) REFERENCES utilisateurs(id) ON DELETE SET NULL
    )",
    "CREATE TABLE IF NOT EXISTS suivi_sante (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        id_utilisateur INT(11) NOT NULL,
        date_enregistrement DATE NOT NULL,
        poids FLOAT,
        taille FLOAT,
        activite_physique INT, -- en minutes par jour
        calories_consommees INT,
        notes TEXT,
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS contact (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        sujet VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
        lu BOOLEAN DEFAULT FALSE
    )"
];

foreach ($tables as $sql) {
    if ($conn->query($sql) === FALSE) {
        die("Erreur lors de la création des tables : " . $conn->error);
    }
}
?>
