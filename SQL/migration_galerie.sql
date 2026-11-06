-- ============================================================================
--  MIGRATION GALERIE  (à exécuter UNE fois sur une base `omstjj` existante)
--
--  Objectif : 1 stage = 1 galerie (dossier) = N images.
--   - Stage.id_stage, Galerie.id_dossier, Image.id_image passent en
--     AUTO_INCREMENT (création depuis l'administration).
--   - Image reçoit la colonne `id_dossier` (FK vers Galerie, ON DELETE
--     CASCADE) : c'est elle qui rattache chaque photo à sa galerie.
--   - Galerie.id_stage passe en ON DELETE CASCADE et devient UNIQUE.
--   - Une galerie est créée pour chaque stage qui n'en a pas encore.
--
--  Les photos sont stockées dans Annexes/Galerie/stage_<id_stage>/
-- ============================================================================

SET NAMES utf8mb4;
USE omstjj;

SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE stage   MODIFY id_stage   INTEGER NOT NULL AUTO_INCREMENT;
ALTER TABLE galerie MODIFY id_dossier INTEGER NOT NULL AUTO_INCREMENT;
ALTER TABLE image   MODIFY id_image   INTEGER NOT NULL AUTO_INCREMENT;

ALTER TABLE image
    ADD COLUMN id_dossier INTEGER NULL AFTER id_image,
    ADD CONSTRAINT fk_image_galerie
        FOREIGN KEY (id_dossier) REFERENCES galerie (id_dossier) ON DELETE CASCADE;

ALTER TABLE galerie DROP FOREIGN KEY fk_galerie_stage;
ALTER TABLE galerie
    ADD UNIQUE KEY uq_galerie_stage (id_stage),
    ADD CONSTRAINT fk_galerie_stage
        FOREIGN KEY (id_stage) REFERENCES stage (id_stage) ON DELETE CASCADE;

-- Galerie.id_image (image de couverture) : remise à NULL si l'image disparaît
ALTER TABLE galerie DROP FOREIGN KEY fk_galerie_image;
ALTER TABLE galerie
    ADD CONSTRAINT fk_galerie_image
        FOREIGN KEY (id_image) REFERENCES image (id_image) ON DELETE SET NULL;

-- Les liaisons vers une galerie disparaissent avec elle
ALTER TABLE alimenter DROP FOREIGN KEY fk_alimenter_galerie;
ALTER TABLE alimenter ADD CONSTRAINT fk_alimenter_galerie
    FOREIGN KEY (id_dossier) REFERENCES galerie (id_dossier) ON DELETE CASCADE;
ALTER TABLE consulter DROP FOREIGN KEY fk_consulter_galerie;
ALTER TABLE consulter ADD CONSTRAINT fk_consulter_galerie
    FOREIGN KEY (id_dossier) REFERENCES galerie (id_dossier) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;

-- Une galerie pour chaque stage existant
INSERT INTO galerie (id_stage, dossier_stage, date, heure)
SELECT s.id_stage, CONCAT('stage_', s.id_stage), CURDATE(), CURTIME()
FROM stage s
LEFT JOIN galerie g ON g.id_stage = s.id_stage
WHERE g.id_dossier IS NULL;
