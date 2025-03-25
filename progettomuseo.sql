-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 24, 2025 alle 12:43
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `progettomuseo`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `clienti`
--

CREATE TABLE `clienti` (
  `cli_id` int(11) UNSIGNED NOT NULL,
  `cli_nome` varchar(200) NOT NULL,
  `cli_email` varchar(150) NOT NULL,
  `cli_telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `clienti`
--

INSERT INTO `clienti` (`cli_id`, `cli_nome`, `cli_email`, `cli_telefono`) VALUES
(1, 'Mario Rossi', 'mario.rossi@email.com', '3331112222'),
(2, 'Giulia Verdi', 'giulia.verdi@email.com', '3342223333'),
(3, 'Luca Bianchi', 'luca.bianchi@email.com', '3353334444'),
(4, 'Anna Neri', 'anna.neri@email.com', '3364445555'),
(5, 'Paolo Gialli', 'paolo.gialli@email.com', '3375556666'),
(6, 'Tommaso Bagnolini', 'tommy.bagnolini@gmail.com', '3401584862');

-- --------------------------------------------------------

--
-- Struttura della tabella `mostre`
--

CREATE TABLE `mostre` (
  `mos_id` int(11) UNSIGNED NOT NULL,
  `mos_nome` varchar(255) NOT NULL,
  `mos_data_inizio` date NOT NULL,
  `mos_data_fine` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `mostre`
--

INSERT INTO `mostre` (`mos_id`, `mos_nome`, `mos_data_inizio`, `mos_data_fine`) VALUES
(1, 'Rinascimento Italiano', '2024-06-01', '2024-07-15'),
(2, 'Impressionismo Francese', '2024-07-20', '2024-09-10'),
(3, 'Avanguardie del 900', '2024-09-15', '2024-10-30'),
(4, 'Arte Contemporanea', '2024-11-01', '2024-12-15'),
(5, 'Maestri Olandesi', '2025-01-10', '2025-02-20');

-- --------------------------------------------------------

--
-- Struttura della tabella `mostre_opere`
--

CREATE TABLE `mostre_opere` (
  `mos_id` int(11) UNSIGNED NOT NULL,
  `ope_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `mostre_opere`
--

INSERT INTO `mostre_opere` (`mos_id`, `ope_id`) VALUES
(1, 1),
(1, 4),
(2, 2),
(3, 3),
(3, 5),
(4, 5),
(5, 2),
(5, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `opere`
--

CREATE TABLE `opere` (
  `ope_id` int(11) UNSIGNED NOT NULL,
  `ope_titolo` varchar(255) NOT NULL,
  `ope_autore` varchar(255) NOT NULL,
  `ope_anno` year(4) DEFAULT NULL,
  `ope_descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `opere`
--

INSERT INTO `opere` (`ope_id`, `ope_titolo`, `ope_autore`, `ope_anno`, `ope_descrizione`) VALUES
(1, 'Monna Lisa', 'Leonardo da Vinci', '0000', 'Ritratto iconico'),
(2, 'La Notte Stellata', 'Vincent van Gogh', '0000', 'Capolavoro post-impressionista'),
(3, 'L’Urlo', 'Edvard Munch', '0000', 'Espressione angosciata'),
(4, 'La Creazione di Adamo', 'Michelangelo', '0000', 'Affresco della Cappella Sistina'),
(5, 'Guernica', 'Pablo Picasso', '1937', 'Denuncia della guerra civile spagnola');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `ute_id` int(11) UNSIGNED NOT NULL,
  `ute_nome` varchar(256) NOT NULL,
  `ute_email` varchar(200) NOT NULL,
  `ute_password` varchar(255) NOT NULL,
  `ute_ruolo` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`ute_id`, `ute_nome`, `ute_email`, `ute_password`, `ute_ruolo`) VALUES
(1, 'Admin', 'admin@museo.com', '$2y$10$Fk3wNmcd/QdIWoMpj28jleMvhusDD/KIODVR7hq3dFx9Vo.GkTO0u', 'admin'),
(4, 'Curatore1', 'curatore1@museo.com', '$2y$10$InUlKv.ENacDLMm6MR/sdOdtGf5rq3AFB6HP6Uz68B9x.M6Avy/cS', 'user'),
(5, 'Curatore2', 'curatore2@museo.com', '$2y$10$iW7VbQSpZPpDD/8I8HZdrOkirDo3jrz9hjAv3HhWFhWrc8ZMS9qIy', 'user'),
(6, 'Responsabile Mostre', 'responsabile@museo.com', '$2y$10$aFkMwPuHn7K2y3wAgZOAGeqX2QAL.h0U7kbPIGGTniItDHnlx2aZy', 'user'),
(7, 'Assistente', 'assistente@museo.com', '$2y$10$82M/cCoburpBP02i4tUnYe05Adt.IQmnSyUxpQoPl7KL57EXajuYK', 'user');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `clienti`
--
ALTER TABLE `clienti`
  ADD PRIMARY KEY (`cli_id`),
  ADD UNIQUE KEY `cli_email` (`cli_email`);

--
-- Indici per le tabelle `mostre`
--
ALTER TABLE `mostre`
  ADD PRIMARY KEY (`mos_id`);

--
-- Indici per le tabelle `mostre_opere`
--
ALTER TABLE `mostre_opere`
  ADD PRIMARY KEY (`mos_id`,`ope_id`),
  ADD KEY `ope_id` (`ope_id`);

--
-- Indici per le tabelle `opere`
--
ALTER TABLE `opere`
  ADD PRIMARY KEY (`ope_id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`ute_id`),
  ADD UNIQUE KEY `ute_email` (`ute_email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `clienti`
--
ALTER TABLE `clienti`
  MODIFY `cli_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `mostre`
--
ALTER TABLE `mostre`
  MODIFY `mos_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `opere`
--
ALTER TABLE `opere`
  MODIFY `ope_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `ute_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `mostre_opere`
--
ALTER TABLE `mostre_opere`
  ADD CONSTRAINT `mostre_opere_ibfk_1` FOREIGN KEY (`mos_id`) REFERENCES `mostre` (`mos_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mostre_opere_ibfk_2` FOREIGN KEY (`ope_id`) REFERENCES `opere` (`ope_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
