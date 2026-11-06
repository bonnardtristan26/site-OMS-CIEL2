-- ============================================================================
--  SCRIPT DE CREATION DE LA BASE DE DONNEES — VERSION CORRIGEE ET FUSIONNEE
--  (corrections du script original + modifications du client conservées)
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
--   4. Tables Galerie et Image : ajout de `id_stage` dans Galerie et de
--      `Image.id_dossier` pour rattacher plusieurs photos à une galerie.
--      `Galerie.id_image` reste une image de couverture facultative.
--   5. Ordre de création réorganisé : les tables référencées (Admin,
--      Image, Actualites, Stage, Utilisateur) sont créées AVANT Galerie et
--      les tables de jonction, sinon MySQL refuse les FOREIGN KEY.
--   6. Faute de frappe `Gallerie` corrigée en `Galerie` partout (DROP et
--      clés étrangères des tables alimenter / consulter).
--   7. Ajout des données : INSERT des 5 stages + 1 compte admin exemple.
--   8. Ajout de `SET NAMES utf8mb4;` en en-tête : sans cette ligne, un client
--      configuré en latin1 interprète mal les accents à l'import (le `é`
--      compte double -> erreur « Data too long for column 'description' »).
--   9. Table Stage : ajout des colonnes `date_stage` (DATE) et
--      `heure_stage` (TIME), utilisées par l'affichage PHP
--      (« Le {date_stage} A {heure_stage} »), avec des valeurs dans les INSERT.
--
--  MODIFICATIONS DU CLIENT (conservées telles quelles) :
--   10. Élargissement des colonnes VARCHAR pour stocker les textes complets :
--       - Utilisateur : niveau_d_etude, sexe, nom_enfant, prenom_enfant,
--         nom_parent, prenom_parent, lien_parente -> VARCHAR(100),
--         mail, mail_secours -> VARCHAR(200)
--       - Actualites : intitule -> VARCHAR(100)
--       - Stage : intitule, type_activite, niveau_etude -> VARCHAR(100),
--         description -> VARCHAR(255)
--       - Galerie : dossier_stage -> VARCHAR(100)
--   11. Descriptions des 5 stages en texte intégral dans les INSERT
--       (apostrophes échappées : l\'océan, l\'équipe).
--
--  AJOUT SUPPLÉMENTAIRE (demande du client) :
--   12. Table Utilisateur : ajout de la colonne `id_stage` (nullable) avec
--       la clé étrangère nommée `fk_utilisateur_stage` vers Stage(id_stage),
--       pour indiquer à quel stage l'utilisateur est inscrit
--       (NULL = pas encore inscrit). À noter : la table de jonction
--       `participer` (0,n)----(0,n) modélise déjà ce lien en version N:N.
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
DROP TABLE IF EXISTS Utilisateur;
DROP TABLE IF EXISTS Stage;
DROP TABLE IF EXISTS Actualites;
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
-- Table Image
-- `id_dossier` rattache chaque photo à sa galerie (plusieurs images par galerie).
-- ----------------------------------------------------------------------------
CREATE TABLE Image (
    id_image     INTEGER     NOT NULL AUTO_INCREMENT,
    id_dossier   INTEGER     COMMENT 'Galerie (dossier) contenant l''image',
    chemin_image VARCHAR(255),
    PRIMARY KEY (id_image)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Actualites  (« Actualités » sur le schéma)
-- (intitule élargi — modif client)
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
-- `date_stage` / `heure_stage` : affichées par le PHP
-- (« Le {date_stage} A {heure_stage} -- Il reste X place(s) »)
-- (colonnes VARCHAR élargies — modif client)
-- ----------------------------------------------------------------------------
CREATE TABLE Stage (
    id_stage      INTEGER     NOT NULL AUTO_INCREMENT,
    intitule      VARCHAR(100),
    type_activite VARCHAR(100),
    niveau_etude  VARCHAR(100),
    description   VARCHAR(255),
    nb_inscrits   INTEGER,
    nb_places     INTEGER,
    image_path    VARCHAR(255) COMMENT 'Chemin de l''image du stage (colonne simple, pas de FK)',
    date_stage    DATE            COMMENT 'Date du stage (affichée par le PHP)',
    heure_stage   TIME            COMMENT 'Heure du stage (affichée par le PHP)',
    PRIMARY KEY (id_stage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Utilisateur  (entité encadrée en rouge sur le schéma)
-- (VARCHAR élargies — modif client)
-- `id_stage` : clé étrangère vers Stage = stage auquel l'utilisateur est
-- inscrit (NULL = pas encore inscrit). La jonction N:N `participer`
-- permet en outre d'inscrire un utilisateur à plusieurs stages.
-- ----------------------------------------------------------------------------
CREATE TABLE Utilisateur (
    id_participant        INTEGER     NOT NULL  AUTO_INCREMENT,
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
    id_stage              INTEGER COMMENT 'Stage de l''inscription (NULL = pas encore inscrit)',
    PRIMARY KEY (id_participant),
    CONSTRAINT fk_utilisateur_stage
        FOREIGN KEY (id_stage) REFERENCES Stage (id_stage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- Table Galerie
-- Une ligne par galerie de stage ou dossier de photos daté.
-- `id_image` est une couverture facultative ; les photos sont liées via
-- Image.id_dossier. `dossier_stage` élargi — modif client.
-- ----------------------------------------------------------------------------
CREATE TABLE Galerie (
    id_dossier    INTEGER     NOT NULL AUTO_INCREMENT,
    id_image      INTEGER     COMMENT 'Image de couverture (facultative)',
    id_stage      INTEGER,
    dossier_stage VARCHAR(100),
    date          DATE,
    heure         TIME,
    PRIMARY KEY (id_dossier),
    UNIQUE KEY uq_galerie_stage (id_stage),
    CONSTRAINT fk_galerie_image
        FOREIGN KEY (id_image) REFERENCES Image (id_image) ON DELETE SET NULL,
    CONSTRAINT fk_galerie_stage
        FOREIGN KEY (id_stage) REFERENCES Stage (id_stage) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ajoutée après coup car Image est créée avant Galerie.
ALTER TABLE Image
    ADD CONSTRAINT fk_image_galerie
        FOREIGN KEY (id_dossier) REFERENCES Galerie (id_dossier) ON DELETE CASCADE;

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
        FOREIGN KEY (id_dossier) REFERENCES Galerie (id_dossier) ON DELETE CASCADE,
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
        FOREIGN KEY (id_dossier) REFERENCES Galerie (id_dossier) ON DELETE CASCADE,
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
-- (colonne simple, aucune jointure avec la table Image), ainsi que
-- `date_stage` et `heure_stage` (affichage PHP).
-- Semaine de stages : lundi 19/10/2026 -> vendredi 23/10/2026
-- (vacances de la Toussaint) — modifie les dates/heures selon tes besoins.
-- ATTENTION : la table s'appelle `Stage` (S majuscule). Sous Windows/XAMPP
-- et macOS, `stage` et `Stage` sont équivalents ; sous Linux (casse
-- sensible), il faut écrire `Stage` dans l'INSERT ci-dessous.
-- ----------------------------------------------------------------------------
INSERT INTO `stage` (`id_stage`, `intitule`, `type_activite`, `niveau_etude`, `description`, `nb_inscrits`, `nb_places`, `image_path`, `date_stage`, `heure_stage`) VALUES
(1, 'padel', 'jeu de ballon', 'college', 'Le padel est un sport de raquette ludique, accessible et ultra-dynamique qui se joue en double sur un court réduit entouré de vitres.', 13, 20, '../Annexes/Images/padel.jpg', '2026-10-19', '09:00:00'),
(2, 'Foot', 'jeu de ballon', 'college', 'Le football est un sport collectif stratégique, intense et populaire qui se joue à onze sur un grand terrain herbé encadré de cages.', 20, 20, '../Annexes/Images/football.jpg', '2026-10-20', '14:00:00'),
(3, 'surf', 'jeu en mer', 'college', 'Le surf est un sport de glisse exigeant qui se pratique sur les vagues, alliant équilibre, lecture de l\'océan et sensations fortes.', 15, 20, '../Annexes/Images/surf.jpg', '2026-10-21', '09:30:00'),
(4, 'Basket', 'jeu de ballon', 'college', 'Le basket-ball est un sport collectif rythmé qui se joue à cinq contre cinq, mêlant vitesse, précision et esprit d\'équipe.', 6, 20, '../Annexes/Images/basket.jpg', '2026-10-22', '14:00:00'),
(5, 'Volley', 'jeu de ballon', 'college', 'Le volley-ball se joue à six sur un terrain séparé par un filet, avec pour objectif de faire tomber le ballon dans le camp adverse.', 17, 20, '../Annexes/Images/volley.jpg', '2026-10-23', '10:00:00');

-- ----------------------------------------------------------------------------
-- Trois actualités par défaut affichées sur la page d'accueil.
-- ----------------------------------------------------------------------------
INSERT INTO Actualites (id_actu, chemin_image, intitule, description) VALUES
(1, '../Annexes/Images/1.webp', 'Des dinosaures aperçus sur la plage', 'Sur les plages de Miami, de jeunes dinosaures auraient été aperçus. Une découverte insolite qui fait parler les amateurs de paléontologie.'),
(2, '../Annexes/Images/2.jpg', 'Les Bronzés font du ski', 'Dans les montagnes du Honduras, une sortie ski pleine de surprises a réuni les amateurs de sport et de bonne humeur.'),
(3, '../Annexes/Images/3.webp', 'Rencontre insolite entre dirigeants', 'Une rencontre inattendue entre deux dirigeants a fait réagir. L’OMS rappelle que le sport reste avant tout un espace de dialogue et de partage.');

-- Une galerie (dossier Annexes/Galerie/stage_<id>) pour chaque stage
INSERT INTO Galerie (id_stage, dossier_stage, date, heure)
SELECT id_stage, CONCAT('stage_', id_stage), date_stage, heure_stage FROM Stage;

-- ----------------------------------------------------------------------------
-- Compte administrateur exemple
--   Mot de passe en clair : admin123
--   Hash SHA-256           : 240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9
-- Le hash est calculé directement par MariaDB/MySQL avec SHA2('admin123', 256).
-- Côté application (PHP), à la connexion : comparer
--   hash('sha256', $_POST['mdp'])  avec la colonne `mdp`.
-- ----------------------------------------------------------------------------
INSERT INTO Admin (id_utilisateur, nom, prenom, mdp) VALUES
(1, 'Dupont', 'Jean', SHA2('admin123', 256)),
(2, 'admin', 'admin', SHA2('admin', 256));

-- ----------------------------------------------------------------------------
-- Exemple d'utilisation (commenté — à adapter) : inscrire un utilisateur
-- à un stage via la clé étrangère ajoutée :
--   UPDATE Utilisateur SET id_stage = 1 WHERE id_participant = 1;
-- Pour un lien N:N (un utilisateur à plusieurs stages), utiliser plutôt
-- la table de jonction :
--   INSERT INTO participer (id_participant, id_stage) VALUES (1, 1);
-- ----------------------------------------------------------------------------

-- ============================================================================
-- Fin du script corrigé et fusionné : 11 tables, 13 clés étrangères nommées
-- (10 dans les 5 tables de jonction + 2 dans Galerie : image et stage
--  + 1 dans Utilisateur : inscription à un stage)
-- ============================================================================
