-- ============================================================================
--  SCRIPT DE CREATION DE LA BASE DE DONNEES — VERSION CORRIGEE
--  Généré d'après le schéma (MCD) fourni : toutes les tables et toutes
--  liaisons (associations) du schéma sont reprises ci-dessous.
--
--  SGBD cible : MySQL / MariaDB
--  IMPORTANT : ENGINE=InnoDB est imposé sur chaque table, car c'est le seul
--  moteur qui crée réellement les clés étrangères (les jonctions). Avec le
--  moteur MyISAM, les FOREIGN KEY seraient ignorées silencieusement et les
--  tables resteraient indépendantes.
--
--  Remarque : les accents et apostrophes ont été retirés des identifiants
--  pour garantir la compatibilité (prénom -> prenom, intitulé -> intitule,
--  niveau_d'étude -> niveau_d_etude, lien_parenté -> lien_parente, ...).
--
--  CORRECTIONS APPORTEES PAR RAPPORT AU SCRIPT ORIGINAL :
--   1. Table Admin : ajout de la colonne `mdp` VARCHAR(64) NOT NULL pour
--      stocker le hash SHA-256 du mot de passe (toujours 64 caractères
--      hexadécimaux).
--   2. Table Stage : ajout de la colonne `image_path` VARCHAR(255) —
--      colonne simple, PAS une clé étrangère vers la table Image.
--   3. Table Image : suppression de la contrainte FOREIGN KEY cassée
--      (virgule manquante après la clé primaire, colonne `id_dossier`
--      inexistante, et table Galerie référencée avant d'être créée).
--   4. Table Galerie : la contrainte vers Stage référençait une colonne
--      `id_stage` inexistante -> colonne ajoutée. La liaison « contenir »
--      avec Image est désormais portée par la colonne `id_image` de Galerie
--      (FK Galerie.id_image -> Image.id_image).
--   5. Ordre de création réorganisé : les tables référencées (Admin,
--      Utilisateur, Image, Actualites, Stage) sont créées AVANT Galerie et
--      les tables de jonction, sinon MySQL refuse les FOREIGN KEY.
--   6. Faute de frappe `Gallerie` corrigée en `Galerie` partout (DROP et
--      clés étrangères des tables alimenter / consulter).
--   7. Ajout des données : INSERT des 5 stages + 1 compte admin exemple.
--   8. Ajout de `SET NAMES utf8mb4;` en en-tête : sans cette ligne, un client
--      configuré en latin1 interprète mal les accents à l'import (le `é`
--      compte double -> erreur « Data too long for column 'description' »).
-- ============================================================================

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS omstjj
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE omstjj;

-- ----------------------------------------------------------------------------
-- Suppression préalable (permet de ré-exécuter le script sans erreur,
-- dans l'ordre inverse des dépendances)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS participer;
DROP TABLE IF EXISTS creer;
DROP TABLE IF EXISTS poster;
DROP TABLE IF EXISTS consulter;
DROP TABLE IF EXISTS alimenter;
DROP TABLE IF EXISTS Galerie;
DROP TABLE IF EXISTS Actualites;
DROP TABLE IF EXISTS Stage;
DROP TABLE IF EXISTS Utilisateur;
DROP TABLE IF EXISTS Image;
DROP TABLE IF EXISTS Admin;

-- ============================================================================
-- 1) TABLES DES ENTITES
-- ============================================================================

-- ----------------------------------------------------------------------------
-- Table Admin
-- `mdp` stocke le hash SHA-256 du mot de passe : toujours 64 caractères
-- hexadécimaux. Génération : SHA2('motdepasse', 256) en MySQL/MariaDB,
-- ou hash('sha256', $motDePasse) côté PHP.
-- ----------------------------------------------------------------------------
CREATE TABLE Admin (
    id_utilisateur INTEGER     NOT NULL AUTO_INCREMENT,
    nom            VARCHAR(50),
    prenom         VARCHAR(50),
    mdp            VARCHAR(64) NOT NULL COMMENT 'Hash SHA-256 du mot de passe (64 cars hex)',
    PRIMARY KEY (id_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Utilisateur  (entité encadrée en rouge sur le schéma)
-- ----------------------------------------------------------------------------
CREATE TABLE Utilisateur (
    id_participant        INTEGER     NOT NULL,
    age                   SMALLINT,
    niveau_d_etude        VARCHAR(100),
    sexe                  VARCHAR(100),
    nom_enfant            VARCHAR(100),
    prenom_enfant         VARCHAR(100),
    nom_parent            VARCHAR(100),
    prenom_parent         VARCHAR(100),
    lien_parente          VARCHAR(100),
    num_tel               INTEGER,
    num_tel_secours       INTEGER,
    mail                  VARCHAR(200),
    mail_secours          VARCHAR(200),
    justificatif_domicile TEXT,
    PRIMARY KEY (id_participant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Image
-- (corrigée : la FOREIGN KEY cassée vers Galerie a été retirée ; la liaison
-- « contenir » est portée par Galerie.id_image, voir table Galerie)
-- ----------------------------------------------------------------------------
CREATE TABLE Image (
    id_image     INTEGER     NOT NULL,
    chemin_image VARCHAR(255),
    PRIMARY KEY (id_image)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Actualites  (« Actualités » sur le schéma)
-- ----------------------------------------------------------------------------
CREATE TABLE Actualites (
    id_actu      INTEGER     NOT NULL,
    chemin_image VARCHAR(255),
    intitule     VARCHAR(100),
    description  VARCHAR(255),
    PRIMARY KEY (id_actu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Stage
-- `image_path` est une colonne simple (chemin de l'image du stage),
-- PAS une clé étrangère vers la table Image.
-- ----------------------------------------------------------------------------
CREATE TABLE Stage (
    id_stage      INTEGER     NOT NULL,
    intitule      VARCHAR(100),
    type_activite VARCHAR(100),
    niveau_etude  VARCHAR(100),
    description   VARCHAR(255),
    date_stage    DATE,
    heure_stage   TIME,
    nb_inscrits   INTEGER,
    nb_places     INTEGER,
    image_path    VARCHAR(255) COMMENT 'Chemin de l''image du stage (colonne simple, pas de FK)',
    PRIMARY KEY (id_stage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Galerie
-- JONCTION « contenir » : Galerie (0,n) ---- (1,1) Image
-- (corrigée : colonne `id_stage` ajoutée pour la FK vers Stage, et la FK
-- vers Image porte sur la colonne `id_image` existante)
-- ----------------------------------------------------------------------------
CREATE TABLE Galerie (
    id_dossier    INTEGER     NOT NULL,
    id_image      INTEGER,
    id_stage      INTEGER,
    dossier_stage VARCHAR(100),
    date          DATE,
    heure         TIME,
    PRIMARY KEY (id_dossier),
    CONSTRAINT fk_galerie_image
        FOREIGN KEY (id_image) REFERENCES Image (id_image),
    CONSTRAINT fk_galerie_stage
        FOREIGN KEY (id_stage) REFERENCES Stage (id_stage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- 2) LIAISONS (ASSOCIATIONS) DU SCHEMA : TABLES DE JOINTURE AVEC CLES
--    ETRANGERES NOMMEES (les jonctions entre les tables)
-- ============================================================================

-- ----------------------------------------------------------------------------
-- Liaison « alimenter » : Galerie (0,n) ---- (0,n) Admin
-- ----------------------------------------------------------------------------
CREATE TABLE alimenter (
    id_dossier     INTEGER NOT NULL,
    id_utilisateur INTEGER NOT NULL,
    PRIMARY KEY (id_dossier, id_utilisateur),
    CONSTRAINT fk_alimenter_galerie
        FOREIGN KEY (id_dossier) REFERENCES Galerie (id_dossier),
    CONSTRAINT fk_alimenter_admin
        FOREIGN KEY (id_utilisateur) REFERENCES Admin (id_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Liaison « consulter » : Galerie (0,n) ---- (0,n) Utilisateur
-- ----------------------------------------------------------------------------
CREATE TABLE consulter (
    id_dossier     INTEGER NOT NULL,
    id_participant INTEGER NOT NULL,
    PRIMARY KEY (id_dossier, id_participant),
    CONSTRAINT fk_consulter_galerie
        FOREIGN KEY (id_dossier) REFERENCES Galerie (id_dossier),
    CONSTRAINT fk_consulter_utilisateur
        FOREIGN KEY (id_participant) REFERENCES Utilisateur (id_participant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Liaison « poster » : Admin (0,n) ---- (0,n) Actualites
-- ----------------------------------------------------------------------------
CREATE TABLE poster (
    id_utilisateur INTEGER NOT NULL,
    id_actu        INTEGER NOT NULL,
    PRIMARY KEY (id_utilisateur, id_actu),
    CONSTRAINT fk_poster_admin
        FOREIGN KEY (id_utilisateur) REFERENCES Admin (id_utilisateur),
    CONSTRAINT fk_poster_actu
        FOREIGN KEY (id_actu) REFERENCES Actualites (id_actu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Liaison « creer » : Admin (0,n) ---- (0,n) Stage
-- ----------------------------------------------------------------------------
CREATE TABLE creer (
    id_utilisateur INTEGER NOT NULL,
    id_stage       INTEGER NOT NULL,
    PRIMARY KEY (id_utilisateur, id_stage),
    CONSTRAINT fk_creer_admin
        FOREIGN KEY (id_utilisateur) REFERENCES Admin (id_utilisateur),
    CONSTRAINT fk_creer_stage
        FOREIGN KEY (id_stage) REFERENCES Stage (id_stage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Liaison « participer » : Utilisateur (0,n) ---- (0,n) Stage
-- ----------------------------------------------------------------------------
CREATE TABLE participer (
    id_participant INTEGER NOT NULL,
    id_stage       INTEGER NOT NULL,
    PRIMARY KEY (id_participant, id_stage),
    CONSTRAINT fk_participer_utilisateur
        FOREIGN KEY (id_participant) REFERENCES Utilisateur (id_participant),
    CONSTRAINT fk_participer_stage
        FOREIGN KEY (id_stage) REFERENCES Stage (id_stage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- 3) DONNEES
-- ============================================================================

-- ----------------------------------------------------------------------------
-- Stages : `image_path` est renseigné directement dans la table Stage
-- (colonne simple, aucune jointure avec la table Image)
-- ----------------------------------------------------------------------------
INSERT INTO `stage` (`id_stage`, `intitule`, `type_activite`, `niveau_etude`, `description`, `date_debut`, `heure_debut`, `nb_inscrits`, `nb_places`, `image_path`) VALUES
(1, 'padel', 'jeu de ballon', 'college', 'Le padel est un sport de raquette ludique, accessible et ultra-dynamique qui se joue en double sur un court réduit entouré de vitres.', '2026-05-13', '15:00:00', 13, 20, '../Annexes/Images/padel.jpg'),
(2, 'Foot', 'jeu de ballon', 'college', 'Le football est un sport collectif stratégique, intense et populaire qui se joue à onze sur un grand terrain herbé encadré de cages.', '2026-05-22', '15:00:00', 20, 20, '../Annexes/Images/foot.jpg'),
(3, 'surf', 'jeu en mer', 'college', 'Le surf est un sport de glisse exigeant qui se pratique sur les vagues, alliant équilibre, lecture de l\'océan et sensations fortes.', '2026-06-17', '15:00:00', 15, 20, '../Annexes/Images/surf.jpg'),
(4, 'Basket', 'jeu de ballon','college', 'Le basket-ball est un sport collectif rythmé qui se joue à cinq contre cinq, mêlant vitesse, précision et esprit d\'équipe.', '2026-06-19', '15:00:00', 6, 20, '../Annexes/Images/basket.jpg'),
(5, 'Volley', 'jeu de ballon', 'college', 'Le volley-ball se joue à six sur un terrain séparé par un filet, avec pour objectif de faire tomber le ballon dans le camp adverse.', '2026-07-14', '15:00:00', 17, 20, '../Annexes/Images/volley.jpg');
-- ----------------------------------------------------------------------------
-- Compte administrateur exemple
--   Mot de passe en clair : admin123
--   Hash SHA-256           : 240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9
-- Le hash est calculé directement par MariaDB/MySQL avec SHA2('admin123', 256).
-- Côté application (PHP), à la connexion : comparer
--   hash('sha256', $_POST['mdp'])  avec la colonne `mdp`.
-- ----------------------------------------------------------------------------
INSERT INTO Admin (id_utilisateur, nom, prenom, mdp) VALUES
(1, 'Dupont', 'Jean', SHA2('admin123', 256));

-- ============================================================================
-- Fin du script corrigé : 11 tables, 12 clés étrangères nommées
-- (10 dans les 5 tables de jonction + 2 dans Galerie : image et stage)
-- ============================================================================
