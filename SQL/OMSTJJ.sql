-- ============================================================================
--  SCRIPT DE CREATION DE LA BASE DE DONNEES
--  Généré d'après le schéma (MCD) fourni : toutes les tables et toutes les
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
-- ============================================================================

CREATE DATABASE IF NOT EXISTS gestion_stages
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gestion_stages;

-- ----------------------------------------------------------------------------
-- Suppression préalable (permet de ré-exécuter le script sans erreur,
-- et de remplacer d'anciennes tables créées sans les jonctions)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS participer;
DROP TABLE IF EXISTS creer;
DROP TABLE IF EXISTS poster;
DROP TABLE IF EXISTS consulter;
DROP TABLE IF EXISTS alimenter;
DROP TABLE IF EXISTS Gallerie;
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
-- ----------------------------------------------------------------------------
CREATE TABLE Admin (
    id_utilisateur INTEGER     NOT NULL,
    nom            VARCHAR(50),
    prenom         VARCHAR(50),
    PRIMARY KEY (id_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Utilisateur  (entité encadrée en rouge sur le schéma)
-- ----------------------------------------------------------------------------
CREATE TABLE Utilisateur (
    id_participant        INTEGER     NOT NULL,
    age                   SMALLINT,
    niveau_d_etude        VARCHAR(50),
    sexe                  VARCHAR(50),
    nom_enfant            VARCHAR(50),
    prenom_enfant         VARCHAR(50),
    nom_parent            VARCHAR(50),
    prenom_parent         VARCHAR(50),
    lien_parente          VARCHAR(50),
    num_tel               INTEGER,
    num_tel_secours       INTEGER,
    mail                  VARCHAR(50),
    mail_secours          VARCHAR(50),
    justificatif_domicile TEXT,
    PRIMARY KEY (id_participant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Image
-- ----------------------------------------------------------------------------
CREATE TABLE Image (
    id_image     INTEGER     NOT NULL,
    chemin_image VARCHAR(50),
    PRIMARY KEY (id_image)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Gallerie
-- JONCTION « contenir » : Gallerie (0,n) ---- (1,1) Image
-- La clé étrangère fk_gallerie_image relie Gallerie à Image.
-- ----------------------------------------------------------------------------
CREATE TABLE Gallerie (
    id_dossier    INTEGER     NOT NULL,
    id_image      INTEGER,
    dossier_stage VARCHAR(50),
    date          DATE,
    heure         TIME,
    PRIMARY KEY (id_dossier),
    CONSTRAINT fk_gallerie_image
        FOREIGN KEY (id_image) REFERENCES Image (id_image)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Actualites  (« Actualités » sur le schéma)
-- ----------------------------------------------------------------------------
CREATE TABLE Actualites (
    id_actu      INTEGER     NOT NULL,
    chemin_image VARCHAR(50),
    intitule     VARCHAR(50),
    description  VARCHAR(50),
    PRIMARY KEY (id_actu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Stage
-- ----------------------------------------------------------------------------
CREATE TABLE Stage (
    id_stage      INTEGER     NOT NULL,
    intitule      VARCHAR(50),
    type_activite VARCHAR(50),
    niveau_etude  VARCHAR(50),
    description   VARCHAR(50),
    nb_inscrits   INTEGER,
    nb_places     INTEGER,
    PRIMARY KEY (id_stage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- 2) LIAISONS (ASSOCIATIONS) DU SCHEMA : TABLES DE JOINTURE AVEC CLES
--    ETRANGERES NOMMEES (les jonctions entre les tables)
-- ============================================================================

-- ----------------------------------------------------------------------------
-- Liaison « alimenter » : Gallerie (0,n) ---- (0,n) Admin
-- ----------------------------------------------------------------------------
CREATE TABLE alimenter (
    id_dossier     INTEGER NOT NULL,
    id_utilisateur INTEGER NOT NULL,
    PRIMARY KEY (id_dossier, id_utilisateur),
    CONSTRAINT fk_alimenter_gallerie
        FOREIGN KEY (id_dossier) REFERENCES Gallerie (id_dossier),
    CONSTRAINT fk_alimenter_admin
        FOREIGN KEY (id_utilisateur) REFERENCES Admin (id_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Liaison « consulter » : Gallerie (0,n) ---- (0,n) Utilisateur
-- ----------------------------------------------------------------------------
CREATE TABLE consulter (
    id_dossier     INTEGER NOT NULL,
    id_participant INTEGER NOT NULL,
    PRIMARY KEY (id_dossier, id_participant),
    CONSTRAINT fk_consulter_gallerie
        FOREIGN KEY (id_dossier) REFERENCES Gallerie (id_dossier),
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
-- Fin du script : 11 tables, 11 clés étrangères nommées = les 6 liaisons
-- du schéma (contenir, alimenter, consulter, poster, creer, participer)
-- ============================================================================
