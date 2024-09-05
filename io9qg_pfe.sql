-- phpMyAdmin SQL Dump
-- version 4.9.6
-- https://www.phpmyadmin.net/
--
-- Hôte : io9qg.myd.infomaniak.com
-- Généré le :  jeu. 05 sep. 2024 à 13:19
-- Version du serveur :  10.4.22-MariaDB-1:10.4.22+maria~stretch-log
-- Version de PHP :  7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `io9qg_pfe`
--

-- --------------------------------------------------------

--
-- Structure de la table `carte`
--

CREATE TABLE `carte` (
  `Id` int(11) NOT NULL COMMENT 'id de la carte',
  `Intitule` varchar(50) NOT NULL,
  `IdImage` int(11) DEFAULT NULL,
  `IdImageReel` int(11) DEFAULT NULL,
  `IdSon` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `proprietaire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `carte`
--

INSERT INTO `carte` (`Id`, `Intitule`, `IdImage`, `IdImageReel`, `IdSon`, `description`, `proprietaire`) VALUES
(2, 'chien', 1, 6, 1, 'carte de chien ', 5),
(3, 'chat', 2, 7, 23, 'carte de chat', 5),
(4, 'klaxon auto', 3, NULL, 12, 'bruit de klaxon d\'une voiture', 5),
(6, 'Souris', 5, 8, 13, 'carte de la souris', 8),
(18, 'voiture', 32, 33, 12, '', 27),
(19, 'bus', 30, 31, 25, 'carte du klaxon du bus', 28);

-- --------------------------------------------------------

--
-- Structure de la table `cartetoortho`
--

CREATE TABLE `cartetoortho` (
  `id` int(11) NOT NULL,
  `IdCarte` int(11) NOT NULL,
  `IdUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cartetoortho`
--

INSERT INTO `cartetoortho` (`id`, `IdCarte`, `IdUser`) VALUES
(19, 2, 5),
(20, 3, 5),
(21, 4, 5),
(22, 6, 8),
(23, 3, 8),
(24, 4, 8),
(26, 6, 5),
(30, 18, 8),
(31, 18, 27),
(32, 19, 8),
(33, 19, 28);

-- --------------------------------------------------------

--
-- Structure de la table `cartetoserie`
--

CREATE TABLE `cartetoserie` (
  `Id` int(11) NOT NULL,
  `IdCarte` int(11) NOT NULL,
  `IdSerie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `cartetoserie`
--

INSERT INTO `cartetoserie` (`Id`, `IdCarte`, `IdSerie`) VALUES
(3, 2, 1),
(5, 3, 1),
(24, 4, 7),
(25, 4, 3),
(27, 6, 2),
(30, 18, 3),
(31, 19, 10);

-- --------------------------------------------------------

--
-- Structure de la table `demandeajout`
--

CREATE TABLE `demandeajout` (
  `Id` int(11) NOT NULL,
  `TypeObjet` varchar(50) NOT NULL,
  `NomIdentifiant` varchar(50) NOT NULL,
  `ValeurIdentifiant` varchar(50) NOT NULL,
  `Utilisateur` int(11) NOT NULL,
  `DateDemande` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `demandeajout`
--

INSERT INTO `demandeajout` (`Id`, `TypeObjet`, `NomIdentifiant`, `ValeurIdentifiant`, `Utilisateur`, `DateDemande`) VALUES
(9, 'son', 'Id', '22', 5, '2024-07-08'),
(13, 'son', 'Id', '27', 28, '2024-09-05'),
(14, 'image', 'Id', '35', 28, '2024-09-05'),
(15, 'image', 'Id', '36', 28, '2024-09-05');

-- --------------------------------------------------------

--
-- Structure de la table `demandesuppression`
--

CREATE TABLE `demandesuppression` (
  `Id` int(11) NOT NULL,
  `TypeObjet` varchar(50) NOT NULL,
  `NomIdentifiant` varchar(50) NOT NULL,
  `ValeurIdentifiant` varchar(50) NOT NULL,
  `Utilisateur` int(11) NOT NULL,
  `DateDemande` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `demandesuppression`
--

INSERT INTO `demandesuppression` (`Id`, `TypeObjet`, `NomIdentifiant`, `ValeurIdentifiant`, `Utilisateur`, `DateDemande`) VALUES
(50, 'image', 'Id', '1', 5, '2024-07-15');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `Id` int(11) NOT NULL COMMENT 'id Image',
  `Intitule` varchar(50) NOT NULL COMMENT 'Intitule de l''image',
  `typeImage` enum('reel','fictive') NOT NULL,
  `statut` enum('attente','validé') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`Id`, `Intitule`, `typeImage`, `statut`) VALUES
(1, 'chien', 'fictive', 'validé'),
(2, 'chat', 'fictive', 'validé'),
(3, 'voiture klaxon', 'fictive', 'validé'),
(4, 'chien_2', 'fictive', 'validé'),
(5, 'souris', 'fictive', 'validé'),
(6, 'chien réel', 'reel', 'validé'),
(7, 'chat réel', 'reel', 'validé'),
(8, ' reel souris', 'reel', 'validé'),
(21, 'chien', 'fictive', 'validé'),
(23, 'chat 2', 'reel', 'validé'),
(28, 'avion', 'fictive', 'validé'),
(29, 'avion', 'fictive', 'validé'),
(30, 'bus', 'fictive', 'validé'),
(32, 'voiture', 'fictive', 'validé'),
(33, 'voiture', 'reel', 'validé'),
(35, 'bus_son', 'fictive', 'attente'),
(36, 'bus_reel', 'reel', 'attente');

-- --------------------------------------------------------

--
-- Structure de la table `p2o`
--

CREATE TABLE `p2o` (
  `IdOrtho` int(11) NOT NULL,
  `IdPatient` int(11) NOT NULL,
  `Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `p2o`
--

INSERT INTO `p2o` (`IdOrtho`, `IdPatient`, `Id`) VALUES
(8, 7, 3),
(5, 16, 4),
(5, 25, 13),
(8, 25, 14),
(5, 26, 15),
(28, 29, 17),
(8, 29, 18);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `Id` int(11) NOT NULL,
  `NomRole` varchar(20) NOT NULL COMMENT 'Le nom des différents rôle d''utilisateur de l''application'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`Id`, `NomRole`) VALUES
(1, 'Orthophoniste'),
(2, 'patient'),
(3, 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `serie`
--

CREATE TABLE `serie` (
  `Id` int(11) NOT NULL COMMENT 'id de la serie',
  `Theme` varchar(50) NOT NULL COMMENT 'thème de la série',
  `description` text DEFAULT NULL,
  `proprietaire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `serie`
--

INSERT INTO `serie` (`Id`, `Theme`, `description`, `proprietaire`) VALUES
(1, 'Animaux domestique', 'Tous les animaux que l\'on retrouve dans une maison', 5),
(2, 'Animaux foret', 'serie animaux de la foret', 5),
(3, 'bruit urbain', 'bruit que l\'on peut entendre dans la rue', 8),
(7, 'Buit urbain', 'series buit urbain', 5),
(9, 'Transport', 'moyen de transport', 5),
(10, 'bruit de vehicule', 'bruit parvenant de vehicule', 28);

-- --------------------------------------------------------

--
-- Structure de la table `serietoortho`
--

CREATE TABLE `serietoortho` (
  `id` int(11) NOT NULL,
  `IdSerie` int(11) NOT NULL,
  `IdUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `serietoortho`
--

INSERT INTO `serietoortho` (`id`, `IdSerie`, `IdUser`) VALUES
(38, 1, 5),
(39, 2, 5),
(40, 3, 8),
(42, 7, 5),
(43, 3, 5),
(46, 9, 8),
(47, 9, 5),
(48, 9, 27),
(49, 10, 8),
(50, 10, 28);

-- --------------------------------------------------------

--
-- Structure de la table `serietouser`
--

CREATE TABLE `serietouser` (
  `Id` int(11) NOT NULL COMMENT 'Id correspondant à une serie par user',
  `IdUser` int(11) NOT NULL COMMENT 'id utilisateur',
  `IdSerie` int(11) NOT NULL COMMENT 'id de la serie',
  `IdStatut` int(11) NOT NULL COMMENT 'id du statut de la série',
  `DateDebut` date DEFAULT NULL COMMENT 'date du début de la série',
  `DateFin` date DEFAULT NULL COMMENT 'date de fin de la série',
  `IdLastCard` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `serietouser`
--

INSERT INTO `serietouser` (`Id`, `IdUser`, `IdSerie`, `IdStatut`, `DateDebut`, `DateFin`, `IdLastCard`) VALUES
(70, 29, 10, 3, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `son`
--

CREATE TABLE `son` (
  `Id` int(11) NOT NULL,
  `Intitule` varchar(50) NOT NULL,
  `statut` enum('attente','validé') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `son`
--

INSERT INTO `son` (`Id`, `Intitule`, `statut`) VALUES
(1, 'aboiement', 'validé'),
(12, 'klaxon auto', 'validé'),
(13, 'couinement souris', 'validé'),
(22, 'son test', 'attente'),
(23, 'miaulement', 'validé'),
(24, 'test', 'validé'),
(25, 'bus klaxon', 'validé'),
(26, 'avion', 'validé'),
(27, 'bus_son', 'attente');

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

CREATE TABLE `statut` (
  `Id` int(11) NOT NULL COMMENT 'Id du statut',
  `NonStatut` varchar(50) NOT NULL COMMENT 'Nom des statut'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `statut`
--

INSERT INTO `statut` (`Id`, `NonStatut`) VALUES
(1, 'bloquer'),
(2, 'en cours'),
(3, 'fini');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `Id` int(11) NOT NULL COMMENT 'Id Utilisateur',
  `Prenom` varchar(50) NOT NULL COMMENT 'Prenom utilisateur',
  `Nom` varchar(50) NOT NULL COMMENT 'Nom Utilisateur',
  `Parent1` varchar(255) DEFAULT NULL,
  `Parent2` varchar(255) DEFAULT NULL,
  `Mail` varchar(255) NOT NULL COMMENT 'Mail utilisateur',
  `Telephone` varchar(10) NOT NULL COMMENT 'Telephone utilisateur',
  `Password` varchar(255) NOT NULL COMMENT 'Password utilisateur',
  `IdRole` int(11) NOT NULL COMMENT 'id du role utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`Id`, `Prenom`, `Nom`, `Parent1`, `Parent2`, `Mail`, `Telephone`, `Password`, `IdRole`) VALUES
(5, 'Paul', 'JEAN', NULL, NULL, 'jean.Paul@limayrac.fr', '0619658745', '$2y$10$JD9ahFtFeF2orcrSl2WuNOrhduZNQlJAeJBdK6TDal/WdEF45mlK6', 1),
(6, 'Tom', 'VICQUELIN', NULL, NULL, 'tom.vicquelin@limayrac.fr', '0641917606', '$2y$10$Dvi96c2KXIICAdH1YicjBOnSlI6RvPaePFiieXCW8fqOhgboipnYm', 3),
(7, 'Vincent', 'RIVIERE', '', '', 'vincent.riviere@limayrac.fr', '0619658745', '$2y$10$8u5epaK5qdTQs.JkBby3mOGoe/bp6fosMrhRALBq1rdjHLtBXUqLa', 2),
(8, 'stephane', 'BLUSSON', NULL, NULL, 'stephane.blusson@limayrac.fr', '0600000000', '$2y$10$DMh.axJWhH0Z38wZ8bJaD.59ZSdI1./quXL6u7xo86WQ7FquQeGzy', 1),
(16, 'Florian', 'POULAIN', '', '', 'florian.poulain@limayrac.fr', '0600000000', '$2y$10$Y7hl3FKk0GKW7Amw88mzV.EP5cf/Z8EQO.v4RMN4sCwwztbcxMMVC', 2),
(25, 'Benchaa', 'MESKINE', 'Jean', 'Jeanne', 'benchaa.meskine@limayrac.fr', '0600000000', '$2y$10$fce5ZbZL90Bj7TrLAWhIQOyrrD1JHkOg/GCh4U2cN/R9vKn5QARwy', 2),
(26, 'Thomas', 'BLESSON', 'none', 'none', 'thomas.blesson@limayrac.fr', '0600000000', '$2y$10$tAcEEiBxb1rSfd.jH1n.Bu..Y5HkbEl0qHcwafv7Qv1XPfif33CWu', 2),
(27, 'David', 'MACHADO', '', '', 'david.machado@limayrac.fr', '0600000000', '$2y$10$aAjEaT65jf3qWsRI2vM6QeSupm5eV8EycHer1WaTBnPOSF6il.4fK', 1),
(28, 'Mickael', 'Bonnet', '', '', 'mickael.bonnet@limayrac.fr', '0600000000', '$2y$10$wQrtF3uiQRTYAN4RjO/gyuE0ph4FLGwARE2y07pVTxXLJRzmyEWsa', 1),
(29, 'enzo', 'Claverie', 'papa', 'maman', 'enzo.claverie@limayrac.fr', '0600000000', '$2y$10$VPIt5CaHavJ8IIO9V90YyeCRVd3SFEuen26JZ/XFtZbHN3Q1A.x9O', 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `carte`
--
ALTER TABLE `carte`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdImageForeygnKey` (`IdImage`),
  ADD KEY `IdSonForeygnKey` (`IdSon`),
  ADD KEY `idProprietaireForeignKey` (`proprietaire`);

--
-- Index pour la table `cartetoortho`
--
ALTER TABLE `cartetoortho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IdCarteForeygnKey1` (`IdCarte`),
  ADD KEY `IdUserForeignKey3` (`IdUser`);

--
-- Index pour la table `cartetoserie`
--
ALTER TABLE `cartetoserie`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdCarteForeignKey` (`IdCarte`),
  ADD KEY `IdSerieForeignKey2` (`IdSerie`);

--
-- Index pour la table `demandeajout`
--
ALTER TABLE `demandeajout`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdUserForeignKey2` (`Utilisateur`);

--
-- Index pour la table `demandesuppression`
--
ALTER TABLE `demandesuppression`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `TypeObjet` (`TypeObjet`,`ValeurIdentifiant`,`Utilisateur`),
  ADD KEY `IdUserForeignKey` (`Utilisateur`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`Id`);

--
-- Index pour la table `p2o`
--
ALTER TABLE `p2o`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdOrthoForeignKey` (`IdOrtho`),
  ADD KEY `IdPatientForeignKey` (`IdPatient`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`Id`);

--
-- Index pour la table `serie`
--
ALTER TABLE `serie`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdProprietaireForeignKey2` (`proprietaire`);

--
-- Index pour la table `serietoortho`
--
ALTER TABLE `serietoortho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IdUserForeignKey4` (`IdSerie`),
  ADD KEY `IdUserForeignKey5` (`IdUser`);

--
-- Index pour la table `serietouser`
--
ALTER TABLE `serietouser`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdUserForeygnKey` (`IdUser`),
  ADD KEY `IdStatutForeygnKey` (`IdStatut`),
  ADD KEY `IdSerieForeygnKey` (`IdSerie`);

--
-- Index pour la table `son`
--
ALTER TABLE `son`
  ADD PRIMARY KEY (`Id`);

--
-- Index pour la table `statut`
--
ALTER TABLE `statut`
  ADD PRIMARY KEY (`Id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdRoleForeygnKey` (`IdRole`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `carte`
--
ALTER TABLE `carte`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la carte', AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `cartetoortho`
--
ALTER TABLE `cartetoortho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `cartetoserie`
--
ALTER TABLE `cartetoserie`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `demandeajout`
--
ALTER TABLE `demandeajout`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `demandesuppression`
--
ALTER TABLE `demandesuppression`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id Image', AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `p2o`
--
ALTER TABLE `p2o`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `serie`
--
ALTER TABLE `serie`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la serie', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `serietoortho`
--
ALTER TABLE `serietoortho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `serietouser`
--
ALTER TABLE `serietouser`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id correspondant à une serie par user', AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pour la table `son`
--
ALTER TABLE `son`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `statut`
--
ALTER TABLE `statut`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id du statut', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id Utilisateur', AUTO_INCREMENT=30;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `carte`
--
ALTER TABLE `carte`
  ADD CONSTRAINT `IdImageForeygnKey` FOREIGN KEY (`IdImage`) REFERENCES `image` (`Id`),
  ADD CONSTRAINT `IdSonForeygnKey` FOREIGN KEY (`IdSon`) REFERENCES `son` (`Id`),
  ADD CONSTRAINT `idProprietaireForeignKey` FOREIGN KEY (`proprietaire`) REFERENCES `user` (`Id`);

--
-- Contraintes pour la table `cartetoortho`
--
ALTER TABLE `cartetoortho`
  ADD CONSTRAINT `IdCarteForeygnKey` FOREIGN KEY (`IdCarte`) REFERENCES `carte` (`Id`),
  ADD CONSTRAINT `IdUserForeignKey3` FOREIGN KEY (`IdUser`) REFERENCES `user` (`Id`);

--
-- Contraintes pour la table `cartetoserie`
--
ALTER TABLE `cartetoserie`
  ADD CONSTRAINT `IdCarteForeignKey` FOREIGN KEY (`IdCarte`) REFERENCES `carte` (`Id`),
  ADD CONSTRAINT `IdSerieForeignKey2` FOREIGN KEY (`IdSerie`) REFERENCES `serie` (`Id`);

--
-- Contraintes pour la table `demandeajout`
--
ALTER TABLE `demandeajout`
  ADD CONSTRAINT `IdUserForeignKey2` FOREIGN KEY (`Utilisateur`) REFERENCES `user` (`Id`);

--
-- Contraintes pour la table `demandesuppression`
--
ALTER TABLE `demandesuppression`
  ADD CONSTRAINT `IdUserForeignKey` FOREIGN KEY (`Utilisateur`) REFERENCES `user` (`Id`);

--
-- Contraintes pour la table `p2o`
--
ALTER TABLE `p2o`
  ADD CONSTRAINT `IdOrthoForeignKey` FOREIGN KEY (`IdOrtho`) REFERENCES `user` (`Id`),
  ADD CONSTRAINT `IdPatientForeignKey` FOREIGN KEY (`IdPatient`) REFERENCES `user` (`Id`);

--
-- Contraintes pour la table `serie`
--
ALTER TABLE `serie`
  ADD CONSTRAINT `IdProprietaireForeignKey2` FOREIGN KEY (`proprietaire`) REFERENCES `user` (`Id`);

--
-- Contraintes pour la table `serietoortho`
--
ALTER TABLE `serietoortho`
  ADD CONSTRAINT `IdSerieForeignKey3` FOREIGN KEY (`IdSerie`) REFERENCES `serie` (`Id`),
  ADD CONSTRAINT `IdUserForeignKey5` FOREIGN KEY (`IdUser`) REFERENCES `user` (`Id`);

--
-- Contraintes pour la table `serietouser`
--
ALTER TABLE `serietouser`
  ADD CONSTRAINT `IdSerieForeygnKey` FOREIGN KEY (`IdSerie`) REFERENCES `serie` (`Id`),
  ADD CONSTRAINT `IdStatutForeygnKey` FOREIGN KEY (`IdStatut`) REFERENCES `statut` (`Id`),
  ADD CONSTRAINT `IdUserForeygnKey` FOREIGN KEY (`IdUser`) REFERENCES `user` (`Id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `IdRoleForeygnKey` FOREIGN KEY (`IdRole`) REFERENCES `role` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
