-- sql/init_db.sql
CREATE DATABASE IF NOT EXISTS gestion_cours;
USE gestion_cours;

CREATE TABLE emploi_du_temps (
  date DATE,
  heure VARCHAR(20),
  matiere VARCHAR(100),
  enseignant VARCHAR(100),
  PRIMARY KEY (date, heure)
);

CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  auteur VARCHAR(100),
  message TEXT,
  date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS documents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilisateur VARCHAR(100),
  nom_fichier VARCHAR(255)
);
CREATE TABLE IF NOT EXISTS utilisateurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL UNIQUE,
  mot_de_passe VARCHAR(255) NOT NULL,
  role ENUM('admin', 'prof', 'eleve') NOT NULL
);