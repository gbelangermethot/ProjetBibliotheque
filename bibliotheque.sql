-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 10 avr. 2026 à 17:11
-- Version du serveur : 8.0.43
-- Version de PHP : 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bibliotheque`
CREATE DATABASE IF NOT EXISTS `bibliotheque` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bibliotheque`;
--

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--
 
CREATE TABLE `documents` (
  `ID` int NOT NULL,
  `titre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `auteur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `annee` year NOT NULL,
  `categorie` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `genre` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isbn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`ID`, `titre`, `auteur`, `annee`, `categorie`, `type`, `genre`, `description`, `isbn`) VALUES
(1, 'Harry Potter a l\'école des sorciers', 'J.K. Rowling', '1997', 'roman', 'ado', 'fantaisie', 'Jeune orphelin découvre qu\'il a des pouvoirs magiques et va a l\'école des sorciers', '2-07-051842-6'),
(2, 'Harry Potter a l\'école des sorciers', 'David Heyman', '2001', 'film', 'enfant', 'fantaisie', 'Jeune orphelin découvre qu\'il a des pouvoirs magiques et va a l\'école des sorciers', NULL),
(5, 'Star Wars', 'George Lucas', '1977', 'film', 'ado', 'science fiction', 'Jeune fermier orphelin se retrouve implique dans un conflit intergalactique', NULL),
(6, 'Mistborn, the final empire', 'Brandon Sanderson', '2006', 'roman', 'ado', 'fantaisie', 'Jeune orpheline decouvre qu\'elle est une mistborn et a les pouvoir quelle peux utiliser venant de metaux, elle joint une rebellion pour abattre l\'empire final.', '0-7653-1178-X'),
(7, 'Le Seigneur des anneaux', 'J.R.R Tolkien', '1954', 'roman', 'ado', 'fantaisie', 'Frodo le hobbit part en quete avec des amis et compagnons pour detruire un anneau contenant l\'essence du seigneur des tenebres sauron pour l\'eradiquer a jamais', ' 9780007129706');

-- --------------------------------------------------------

--
-- Structure de la table `prets`
--

CREATE TABLE `prets` (
  `ID` int NOT NULL,
  `documentID` int NOT NULL,
  `utilisateurID` int NOT NULL,
  `datePret` date NOT NULL,
  `dateRetour` date NOT NULL,
  `isRetourne` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prets`
--

INSERT INTO `prets` (`ID`, `documentID`, `utilisateurID`, `datePret`, `dateRetour`, `isRetourne`) VALUES
(1, 1, 18, '2025-09-01', '2025-09-15', 1),
(2, 2, 17, '2025-09-02', '2025-09-16', 1),
(3, 1, 18, '2025-09-16', '2025-09-30', 1),
(5, 5, 17, '2025-09-15', '2025-09-29', 1),
(8, 6, 18, '2025-10-05', '2025-10-19', 1),
(9, 2, 18, '2025-10-05', '2025-10-19', 1),
(10, 1, 18, '2025-10-05', '2025-10-19', 1),
(11, 2, 18, '2025-10-05', '2025-10-19', 1),
(15, 7, 17, '2025-10-05', '2025-10-19', 1),
(17, 1, 17, '2026-02-24', '2026-03-10', 1),
(19, 6, 18, '2026-02-24', '2026-03-10', 1),
(22, 7, 17, '2026-02-24', '2026-03-10', 1),
(23, 7, 17, '2026-02-24', '2026-03-10', 1),
(24, 5, 17, '2026-02-25', '2026-03-11', 1),
(25, 2, 17, '2026-02-25', '2026-03-11', 1),
(26, 1, 17, '2026-02-25', '2026-03-11', 1),
(28, 5, 18, '2026-02-25', '2026-03-11', 1),
(29, 1, 18, '2026-02-25', '2026-03-11', 1),
(30, 2, 18, '2026-02-25', '2026-03-11', 1),
(31, 1, 18, '2026-02-25', '2026-03-11', 1),
(32, 1, 18, '2026-02-25', '2026-03-11', 1),
(34, 2, 17, '2026-02-25', '2026-03-11', 1),
(38, 2, 17, '2026-02-26', '2026-03-12', 1),
(42, 1, 18, '2026-02-26', '2026-03-12', 1),
(43, 2, 18, '2026-02-26', '2026-03-12', 1),
(44, 2, 18, '2026-02-26', '2026-03-12', 1),
(45, 1, 18, '2026-02-26', '2026-03-12', 1),
(46, 1, 18, '2026-02-26', '2026-03-12', 1),
(47, 1, 18, '2026-02-26', '2026-03-12', 1),
(48, 5, 17, '2026-02-26', '2026-03-12', 1),
(49, 1, 17, '2026-03-02', '2026-03-16', 1),
(50, 2, 18, '2026-03-02', '2026-03-16', 1),
(51, 6, 18, '2026-03-02', '2026-03-16', 1),
(53, 7, 18, '2026-03-02', '2026-03-02', 1),
(55, 5, 24, '2026-03-03', '2026-03-02', 1),
(56, 1, 17, '2026-03-03', '2026-03-17', 1),
(57, 6, 17, '2026-03-03', '2026-03-17', 1),
(58, 5, 23, '2026-03-03', '2026-03-17', 1),
(59, 1, 26, '2026-03-03', '2026-03-17', 1),
(60, 1, 27, '2026-03-04', '2026-03-18', 1),
(61, 2, 27, '2026-03-04', '2026-03-18', 0),
(62, 6, 27, '2026-03-04', '2026-03-18', 0),
(63, 7, 27, '2026-03-04', '2026-03-18', 0),
(64, 5, 17, '2026-03-04', '2026-03-03', 1);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `ID` int NOT NULL,
  `documentID` int NOT NULL,
  `utilisateurID` int NOT NULL,
  `dateReservation` date NOT NULL,
  `isActive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`ID`, `documentID`, `utilisateurID`, `dateReservation`, `isActive`) VALUES
(1, 1, 18, '2025-09-16', 0),
(5, 2, 17, '2025-09-16', 0),
(8, 6, 17, '2025-10-04', 0),
(9, 7, 18, '2025-10-04', 0),
(10, 2, 18, '2025-10-05', 0),
(11, 6, 18, '2025-10-05', 0),
(14, 6, 17, '2025-10-05', 0),
(15, 1, 17, '2026-02-24', 0),
(16, 1, 17, '2026-02-24', 0),
(17, 1, 22, '2026-02-25', 0),
(18, 2, 17, '2026-02-25', 0),
(19, 2, 18, '2026-02-26', 0),
(20, 1, 18, '2026-02-26', 0),
(21, 2, 18, '2026-02-26', 0),
(22, 1, 17, '2026-02-26', 0),
(23, 1, 18, '2026-03-02', 0),
(24, 2, 18, '2026-03-02', 0),
(28, 6, 18, '2026-03-02', 0),
(29, 1, 17, '2026-03-02', 0),
(30, 2, 24, '2026-03-03', 0),
(32, 1, 23, '2026-03-03', 0),
(33, 2, 23, '2026-03-03', 0),
(34, 5, 23, '2026-03-03', 0),
(35, 6, 23, '2026-03-03', 0),
(36, 1, 23, '2026-03-03', 0),
(37, 2, 23, '2026-03-03', 0),
(38, 5, 23, '2026-03-03', 0),
(39, 7, 24, '2026-03-03', 0),
(40, 1, 27, '2026-03-04', 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `ID` int NOT NULL,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `adresse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ville` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `province` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `codePostal` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isEmploye` tinyint(1) NOT NULL DEFAULT (0),
  `isAdmin` tinyint(1) NOT NULL DEFAULT (0),
  `courriel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`ID`, `nom`, `prenom`, `adresse`, `ville`, `province`, `codePostal`, `telephone`, `password`, `isEmploye`, `isAdmin`, `courriel`) VALUES
(17, 'Skywalker', 'Luke', '123 moisture farm', 'Mos Eisley', 'Tatooine', 'A1A1A3', '514-222-2222', '$2y$10$mN0s0gnxmFy8I6qT4p3TV.iZHgZytywNpbTTp2zP2tTfpPBNF4ps6', 1, 0, 'Luke.Skywalker@gmail.com'),
(18, 'Organa', 'Leia', '123 Palais', 'Capitale`', 'Alderaan', 'A1A1A4', '514-333-3333', '$2y$10$S4O2Sq3G231r.ltPdRDYbuXEgrVKJ.2D2NrwK8vt70bE5ZDJ5SbuS', 1, 1, 'leia.organa@gmail.com'),
(22, 'Calrissian', 'Lando', '123 Cloud City', 'Bespin', 'Outter rim', 'A1A1A4', '1234567890', '$2y$10$pY3G7UaY35xMPMsF.iTwTuy0SW7OmVZjX5J48pBads3ap95ijBdWm', 0, 0, 'lando.calrissian@gmail.com'),
(24, 'Han', 'Solo', '123 slums street', 'Cor Cap', 'Correlia', 'A1A2A2', '111 111 1113', '$2y$10$g/tvu92DK/rE4Qiz/qf.L.fcKhhfD6Z8lnVfEb6PhgtHj64DE5RTG', 0, 0, 'Han.Solo@gmail.com'),
(26, 'Solo', 'Han', '123 slums street', 'Cor Cap', 'Correlia', 'A1A2A2', '111 111 1113', '$2y$10$2iknF1bhAcXoYPX8QkoR7u8xm/Vg0tcsTZ359OnZD.LMfbhV3rvzO', 0, 0, 'Han.Solo2@gmail.com'),
(27, 'Kirk', 'James', '123 street', 'Ohio city', 'Ohio', 'A1A2A2', '1234567890', '$2y$10$ua0woM/43hkxwSiyfdDMHOvAoZ4tN/hx8OzRt/gcC2IFLQTlbfpTS', 1, 0, 'james.kirk@gmail.com');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `prets`
--
ALTER TABLE `prets`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pretsDocumentsFK` (`documentID`),
  ADD KEY `pretsUtilisateursFK` (`utilisateurID`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `reservationsDocumentsFK` (`documentID`),
  ADD KEY `reservationsUtilisateursFK` (`utilisateurID`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `courriel` (`courriel`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `prets`
--
ALTER TABLE `prets`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `prets`
--
ALTER TABLE `prets`
  ADD CONSTRAINT `pretsDocumentsFK` FOREIGN KEY (`documentID`) REFERENCES `documents` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pretsUtilisateursFK` FOREIGN KEY (`utilisateurID`) REFERENCES `utilisateurs` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservationsDocumentsFK` FOREIGN KEY (`documentID`) REFERENCES `documents` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `reservationsUtilisateursFK` FOREIGN KEY (`utilisateurID`) REFERENCES `utilisateurs` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
