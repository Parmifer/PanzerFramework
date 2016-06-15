DROP DATABASE IF EXISTS `panzer_veterino_test`;

CREATE DATABASE IF NOT EXISTS `panzer_veterino_test` 
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE `panzer_veterino_test`;

CREATE TABLE `role` (
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `label` VARCHAR(255),
    `level` int NOT NULL UNIQUE,
    `code` VARCHAR(1) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE `user` (
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `pseudo` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) UNIQUE,
    `creation_date` TIMESTAMP DEFAULT NOW(),
    role_id int,
    FOREIGN KEY (role_id) REFERENCES role(id)
) ENGINE=InnoDB;

CREATE TABLE `ressources_humaines` (
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255),
    `prenom` VARCHAR(255),
    `adresse` VARCHAR(255),
    `salaire` FLOAT(7,2),
    `user_id` int,
    FOREIGN KEY (user_id) REFERENCES user(id)
) ENGINE=InnoDB;

CREATE TABLE `veterinaire` (
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255),
    `prenom` VARCHAR(255),
    `adresse` VARCHAR(255),
    `salaire` FLOAT(7,2),
    `user_id` int,
    FOREIGN KEY (user_id) REFERENCES user(id)
) ENGINE=InnoDB;

CREATE TABLE `infirmier` (
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255),
    `prenom` VARCHAR(255),
    `adresse` VARCHAR(255),
    `salaire` FLOAT(7,2),
    `user_id` int,
    FOREIGN KEY (user_id) REFERENCES user(id)
) ENGINE=InnoDB;

CREATE TABLE `salle` (
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `etage` int,
    `numero` VARCHAR(255) NOT NULL,
    `nombre_places` int
) ENGINE=InnoDB;

CREATE TABLE `chat` (
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255),
    `adresse` VARCHAR(255),
    `reference` VARCHAR(255) UNIQUE NOT NULL,
    `etat` ENUM('entré', 'pris en charge', 'opéré', 'rétablissement', 'ok', 'sorti'),
    `salle_id` int,
    FOREIGN KEY (salle_id) REFERENCES salle(id)
) ENGINE=InnoDB;

CREATE TABLE `prendre_en_charge` (
    `chat_id` int,
    `infirmier_id` int,
    PRIMARY KEY (chat_id, infirmier_id),
    FOREIGN KEY (chat_id) REFERENCES chat(id),
    FOREIGN KEY (infirmier_id) REFERENCES infirmier(id)
) ENGINE=InnoDB;

CREATE TABLE `diagnostiquer` (
    `chat_id` int,
    `veterinaire_id` int,
    `date` TIMESTAMP,
    `diagnostic` TEXT,
    PRIMARY KEY (chat_id, veterinaire_id),
    FOREIGN KEY (chat_id) REFERENCES chat(id),
    FOREIGN KEY (veterinaire_id) REFERENCES veterinaire(id)
) ENGINE=InnoDB;

CREATE TABLE `operer` (
    `chat_id` int,
    `veterinaire_id` int,
    `date` TIMESTAMP,
    PRIMARY KEY (chat_id, veterinaire_id),
    FOREIGN KEY (chat_id) REFERENCES chat(id),
    FOREIGN KEY (veterinaire_id) REFERENCES veterinaire(id)
) ENGINE=InnoDB;
