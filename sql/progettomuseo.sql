CREATE DATABASE IF NOT EXISTS `project-Museum` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `project-Museum`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE TABLE `clienti` (
  `cli_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cli_nome` varchar(200) NOT NULL,
  `cli_email` varchar(150) NOT NULL,
  `cli_telefono` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`cli_id`),
  UNIQUE KEY `cli_email` (`cli_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `clienti` (`cli_id`, `cli_nome`, `cli_email`, `cli_telefono`) VALUES
(1, 'Mario Rossi', 'mario.rossi@email.com', '3331112222'),
(2, 'Giulia Verdi', 'giulia.verdi@email.com', '3342223333'),
(3, 'Luca Bianchi', 'luca.bianchi@email.com', '3353334444'),
(4, 'Anna Neri', 'anna.neri@email.com', '3364445555'),
(5, 'Tommaso Bagnolini', 'tommy.bagnolini@gmail.com', '3401584862');

CREATE TABLE `mostre` (
  `mos_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mos_nome` varchar(255) NOT NULL,
  `mos_data_inizio` date NOT NULL,
  `mos_data_fine` date NOT NULL,
  PRIMARY KEY (`mos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `mostre` (`mos_id`, `mos_nome`, `mos_data_inizio`, `mos_data_fine`) VALUES
(1, 'Rinascimento Italiano', '2024-06-01', '2024-07-15'),
(2, 'Impressionismo Francese', '2024-07-20', '2024-09-10'),
(3, 'Avanguardie del 900', '2024-09-15', '2024-10-30'),
(4, 'Arte Contemporanea', '2024-11-01', '2024-12-15'),
(5, 'Maestri Olandesi', '2025-01-10', '2025-02-20');

CREATE TABLE `opere` (
  `ope_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ope_titolo` varchar(255) NOT NULL,
  `ope_autore` varchar(255) NOT NULL,
  `ope_anno` year(4) DEFAULT NULL,
  `ope_descrizione` text DEFAULT NULL,
  PRIMARY KEY (`ope_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `opere` (`ope_id`, `ope_titolo`, `ope_autore`, `ope_anno`, `ope_descrizione`) VALUES
(1, 'Monna Lisa', 'Leonardo da Vinci', '0000', 'Ritratto iconico'),
(2, 'La Notte Stellata', 'Vincent van Gogh', '0000', 'Capolavoro post-impressionista'),
(3, 'L’Urlo', 'Edvard Munch', '0000', 'Espressione angosciata'),
(4, 'La Creazione di Adamo', 'Michelangelo', '0000', 'Affresco della Cappella Sistina'),
(5, 'Guernica', 'Pablo Picasso', '1937', 'Denuncia della guerra civile spagnola');

CREATE TABLE `mostre_opere` (
  `mos_id` int(11) UNSIGNED NOT NULL,
  `ope_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`mos_id`,`ope_id`),
  KEY `ope_id` (`ope_id`),
  CONSTRAINT `mostre_opere_ibfk_1` FOREIGN KEY (`mos_id`) REFERENCES `mostre` (`mos_id`) ON DELETE CASCADE,
  CONSTRAINT `mostre_opere_ibfk_2` FOREIGN KEY (`ope_id`) REFERENCES `opere` (`ope_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `mostre_opere` (`mos_id`, `ope_id`) VALUES
(1, 1),
(1, 4),
(2, 2),
(3, 3),
(3, 5),
(4, 5),
(5, 2),
(5, 3);

CREATE TABLE `utenti` (
  `ute_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ute_nome` varchar(256) NOT NULL,
  `ute_email` varchar(200) NOT NULL,
  `ute_password` varchar(255) NOT NULL,
  `ute_ruolo` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`ute_id`),
  UNIQUE KEY `ute_email` (`ute_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*
  ⚠️ Inserimento utenti da fare tramite script PHP:
  utils/populate_users.php
  Questo assicura che le password vengano salvate in forma hashata.
*/

COMMIT;
