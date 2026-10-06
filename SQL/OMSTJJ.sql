-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 29 sep. 2026 à 14:16
-- Version du serveur : 9.1.0
-- Version de PHP : 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `omstjj`
--

-- --------------------------------------------------------

--
-- Structure de la table `actualites`
--

DROP TABLE IF EXISTS `actualites`;
CREATE TABLE IF NOT EXISTS `actualites` (
  `id_actu` int NOT NULL,
  `chemin_image` varchar(50) DEFAULT NULL,
  `intitule` varchar(50) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id_actu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `actualites`
--

INSERT INTO `actualites` (`id_actu`, `chemin_image`, `intitule`, `description`) VALUES
(1, '../Annexes/Images/enfants_football.png', 'SPORT POUR TOUS', 'L\'Office Municipal des Sports accompagne les habitants dans leur pratique sportive et contribue au développement du sport dans la commune. Que vous soyez débutant, sportif régulier ou simplement à');

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id_utilisateur` int NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `mdp` char(200) DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `alimenter`
--

DROP TABLE IF EXISTS `alimenter`;
CREATE TABLE IF NOT EXISTS `alimenter` (
  `id_dossier` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_dossier`,`id_utilisateur`),
  KEY `fk_alimenter_admin` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `consulter`
--

DROP TABLE IF EXISTS `consulter`;
CREATE TABLE IF NOT EXISTS `consulter` (
  `id_dossier` int NOT NULL,
  `id_participant` int NOT NULL,
  PRIMARY KEY (`id_dossier`,`id_participant`),
  KEY `fk_consulter_utilisateur` (`id_participant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `creer`
--

DROP TABLE IF EXISTS `creer`;
CREATE TABLE IF NOT EXISTS `creer` (
  `id_utilisateur` int NOT NULL,
  `id_stage` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_stage`),
  KEY `fk_creer_stage` (`id_stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `galerie`
--

DROP TABLE IF EXISTS `galerie`;
CREATE TABLE IF NOT EXISTS `galerie` (
  `id_dossier` int NOT NULL,
  `id_image` int DEFAULT NULL,
  `dossier_stage` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id_dossier`),
  KEY `fk_gallerie_image` (`id_image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id_image` int NOT NULL,
  `chemin_image` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id_image`, `chemin_image`) VALUES
(1, '../Annexes/Images/image_accueil.png'),
(2, '../Annexes/Images/logo_ball_oms.svg'),
(3, '../Annexes/Images/logo_OMS.svg');

-- --------------------------------------------------------

--
-- Structure de la table `participer`
--

DROP TABLE IF EXISTS `participer`;
CREATE TABLE IF NOT EXISTS `participer` (
  `id_participant` int NOT NULL,
  `id_stage` int NOT NULL,
  PRIMARY KEY (`id_participant`,`id_stage`),
  KEY `fk_participer_stage` (`id_stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `poster`
--

DROP TABLE IF EXISTS `poster`;
CREATE TABLE IF NOT EXISTS `poster` (
  `id_utilisateur` int NOT NULL,
  `id_actu` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_actu`),
  KEY `fk_poster_actu` (`id_actu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stage`
--

DROP TABLE IF EXISTS `stage`;
CREATE TABLE IF NOT EXISTS `stage` (
  `id_stage` int NOT NULL,
  `intitule` varchar(50) DEFAULT NULL,
  `type_activite` varchar(50) DEFAULT NULL,
  `niveau_etude` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `nb_inscrits` int DEFAULT NULL,
  `nb_places` int DEFAULT NULL,
  `image_path` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

INSERT INTO `stage` (`id_stage`, `intitule`, `type_activite`, `niveau_etude`, `description`, `nb_inscrits`, `nb_places`, `image_path`) VALUES
(1, 'padel', 'jeu de ballon', 'college', 'Le padel est un sport de raquette ludique, accessi', 13, 20, '../Annexes/Images/padel.jpg'),
(2, 'Foot', 'jeu de ballon', 'college', 'Le football est un sport collectif stratégique, in', 20, 20, '../Annexes/Images/football.jpg'),
(3, 'surf', 'jeu en mer', 'college', 'Le surf est un sport de glisse exigeant qui se pra', 15, 20, '../Annexes/Images/surf.jpg'),
(4, 'Basket', 'jeu de ballon', 'college', 'Le basket-ball est un sport collectif rythmé qui s', 6, 20, '../Annexes/Images/basket.jpg'),
(5, 'Volley', 'jeu de ballon', 'college', 'Le volley-ball se joue à six sur un terrain séparé', 17, 20, '../Annexes/Images/volley.jpg');

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_participant` int NOT NULL,
  `age` smallint DEFAULT NULL,
  `niveau_d_etude` varchar(50) DEFAULT NULL,
  `sexe` varchar(50) DEFAULT NULL,
  `nom_enfant` varchar(50) DEFAULT NULL,
  `prenom_enfant` varchar(50) DEFAULT NULL,
  `nom_parent` varchar(50) DEFAULT NULL,
  `prenom_parent` varchar(50) DEFAULT NULL,
  `lien_parente` varchar(50) DEFAULT NULL,
  `num_tel` int DEFAULT NULL,
  `num_tel_secours` int DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `mail_secours` varchar(50) DEFAULT NULL,
  `justificatif_domicile` text,
  PRIMARY KEY (`id_participant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `alimenter`
--
ALTER TABLE `alimenter`
  ADD CONSTRAINT `fk_alimenter_admin` FOREIGN KEY (`id_utilisateur`) REFERENCES `admin` (`id_utilisateur`),
  ADD CONSTRAINT `fk_alimenter_gallerie` FOREIGN KEY (`id_dossier`) REFERENCES `galerie` (`id_dossier`);

--
-- Contraintes pour la table `consulter`
--
ALTER TABLE `consulter`
  ADD CONSTRAINT `fk_consulter_gallerie` FOREIGN KEY (`id_dossier`) REFERENCES `galerie` (`id_dossier`),
  ADD CONSTRAINT `fk_consulter_utilisateur` FOREIGN KEY (`id_participant`) REFERENCES `utilisateur` (`id_participant`);

--
-- Contraintes pour la table `creer`
--
ALTER TABLE `creer`
  ADD CONSTRAINT `fk_creer_admin` FOREIGN KEY (`id_utilisateur`) REFERENCES `admin` (`id_utilisateur`),
  ADD CONSTRAINT `fk_creer_stage` FOREIGN KEY (`id_stage`) REFERENCES `stage` (`id_stage`);

--
-- Contraintes pour la table `galerie`
--
ALTER TABLE `galerie`
  ADD CONSTRAINT `fk_gallerie_image` FOREIGN KEY (`id_image`) REFERENCES `image` (`id_image`);

--
-- Contraintes pour la table `participer`
--
ALTER TABLE `participer`
  ADD CONSTRAINT `fk_participer_stage` FOREIGN KEY (`id_stage`) REFERENCES `stage` (`id_stage`),
  ADD CONSTRAINT `fk_participer_utilisateur` FOREIGN KEY (`id_participant`) REFERENCES `utilisateur` (`id_participant`);

--
-- Contraintes pour la table `poster`
--
ALTER TABLE `poster`
  ADD CONSTRAINT `fk_poster_actu` FOREIGN KEY (`id_actu`) REFERENCES `actualites` (`id_actu`),
  ADD CONSTRAINT `fk_poster_admin` FOREIGN KEY (`id_utilisateur`) REFERENCES `admin` (`id_utilisateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
