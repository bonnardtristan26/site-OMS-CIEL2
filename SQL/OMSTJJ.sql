-- ============================================================================
-- Script de creation de la base gestion_stages
-- Compatible MySQL / MariaDB (InnoDB)
-- Les identifiants des entites sont auto-incrementes.
-- ============================================================================

CREATE DATABASE IF NOT EXISTS gestion_stages
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gestion_stages;

-- Supprimer d'abord les tables dependantes, puis les tables parentes.
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS participer;
DROP TABLE IF EXISTS creer;
DROP TABLE IF EXISTS poster;
DROP TABLE IF EXISTS consulter;
DROP TABLE IF EXISTS alimenter;
DROP TABLE IF EXISTS Image;
DROP TABLE IF EXISTS Galerie;
DROP TABLE IF EXISTS Actualites;
DROP TABLE IF EXISTS Stage;
DROP TABLE IF EXISTS Utilisateur;
DROP TABLE IF EXISTS Admin;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE Admin (
    id_utilisateur INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    PRIMARY KEY (id_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Utilisateur (
    id_participant INT NOT NULL AUTO_INCREMENT,
    age SMALLINT,
    niveau_d_etude VARCHAR(50),
    sexe VARCHAR(50),
    nom_enfant VARCHAR(50),
    prenom_enfant VARCHAR(50),
    nom_parent VARCHAR(50),
    prenom_parent VARCHAR(50),
    lien_parente VARCHAR(50),
    num_tel VARCHAR(20),
    num_tel_secours VARCHAR(20),
    mail VARCHAR(100),
    mail_secours VARCHAR(100),
    justificatif_domicile TEXT,
    PRIMARY KEY (id_participant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Stage (
    id_stage INT NOT NULL AUTO_INCREMENT,
    intitule VARCHAR(50),
    type_activite VARCHAR(50),
    niveau_etude VARCHAR(50),
    description TEXT,
    nb_inscrits INT DEFAULT 0,
    nb_places INT,
    PRIMARY KEY (id_stage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Galerie (
    id_dossier INT NOT NULL AUTO_INCREMENT,
    id_stage INT NOT NULL,
    dossier_stage VARCHAR(255),
    date DATE,
    heure TIME,
    PRIMARY KEY (id_dossier),
    CONSTRAINT fk_galerie_stage
        FOREIGN KEY (id_stage) REFERENCES Stage (id_stage)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Une image appartient a un dossier de galerie.
CREATE TABLE Image (
    id_image INT NOT NULL AUTO_INCREMENT,
    id_dossier INT NOT NULL,
    chemin_image VARCHAR(255),
    PRIMARY KEY (id_image),
    CONSTRAINT fk_image_galerie
        FOREIGN KEY (id_dossier) REFERENCES Galerie (id_dossier)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Actualites (
    id_actu INT NOT NULL AUTO_INCREMENT,
    chemin_image VARCHAR(255),
    intitule VARCHAR(100),
    description TEXT,
    PRIMARY KEY (id_actu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Liaison : Galerie <-> Admin
CREATE TABLE alimenter (
    id_dossier INT NOT NULL,
    id_utilisateur INT NOT NULL,
    PRIMARY KEY (id_dossier, id_utilisateur),
    CONSTRAINT fk_alimenter_galerie
        FOREIGN KEY (id_dossier) REFERENCES Galerie (id_dossier)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_alimenter_admin
        FOREIGN KEY (id_utilisateur) REFERENCES Admin (id_utilisateur)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Liaison : Galerie <-> Utilisateur
CREATE TABLE consulter (
    id_dossier INT NOT NULL,
    id_participant INT NOT NULL,
    PRIMARY KEY (id_dossier, id_participant),
    CONSTRAINT fk_consulter_galerie
        FOREIGN KEY (id_dossier) REFERENCES Galerie (id_dossier)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_consulter_utilisateur
        FOREIGN KEY (id_participant) REFERENCES Utilisateur (id_participant)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Liaison : Admin <-> Actualites
CREATE TABLE poster (
    id_utilisateur INT NOT NULL,
    id_actu INT NOT NULL,
    PRIMARY KEY (id_utilisateur, id_actu),
    CONSTRAINT fk_poster_admin
        FOREIGN KEY (id_utilisateur) REFERENCES Admin (id_utilisateur)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_poster_actu
        FOREIGN KEY (id_actu) REFERENCES Actualites (id_actu)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Liaison : Admin <-> Stage
CREATE TABLE creer (
    id_utilisateur INT NOT NULL,
    id_stage INT NOT NULL,
    PRIMARY KEY (id_utilisateur, id_stage),
    CONSTRAINT fk_creer_admin
        FOREIGN KEY (id_utilisateur) REFERENCES Admin (id_utilisateur)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_creer_stage
        FOREIGN KEY (id_stage) REFERENCES Stage (id_stage)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Liaison : Utilisateur <-> Stage
CREATE TABLE participer (
    id_participant INT NOT NULL,
    id_stage INT NOT NULL,
    PRIMARY KEY (id_participant, id_stage),
    CONSTRAINT fk_participer_utilisateur
        FOREIGN KEY (id_participant) REFERENCES Utilisateur (id_participant)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_participer_stage
        FOREIGN KEY (id_stage) REFERENCES Stage (id_stage)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Fin du script : 11 tables.
