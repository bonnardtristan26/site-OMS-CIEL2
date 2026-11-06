<?php
/*
 * Outils partagés pour la gestion des galeries (1 stage = 1 galerie).
 * Les photos sont stockées dans Annexes/Galerie/<dossier_stage>/
 * et chaque photo a une ligne dans la table `image` (colonne id_dossier).
 */

const GALERIE_RACINE_WEB = '../Annexes/Galerie/';

function galerie_pdo(): PDO
{
    return new PDO(
        'mysql:host=localhost;dbname=omstjj;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
}

/** Chemin disque du dossier d'une galerie. */
function galerie_chemin_disque(string $dossier): string
{
    return __DIR__ . '/../Annexes/Galerie/' . basename($dossier);
}

/** Crée la galerie (ligne + dossier) d'un stage et renvoie son id_dossier. */
function galerie_creer(PDO $pdo, int $idStage): int
{
    $dossier = 'stage_' . $idStage;
    $stmt = $pdo->prepare(
        'INSERT INTO galerie (id_stage, dossier_stage, date, heure)
         SELECT id_stage, ?, date_stage, heure_stage
         FROM stage
         WHERE id_stage = ?'
    );
    $stmt->execute([$dossier, $idStage]);
    if ($stmt->rowCount() !== 1) {
        throw new RuntimeException('impossible de créer la galerie : stage introuvable');
    }

    $chemin = galerie_chemin_disque($dossier);
    if (!is_dir($chemin)) {
        mkdir($chemin, 0775, true);
    }
    return (int) $pdo->lastInsertId();
}

/**
 * Vérifie un fichier envoyé ($_FILES[...]) et renvoie son extension
 * (jpg, png, gif, webp), ou lève une exception avec un message lisible.
 */
function image_extension_valide(?array $fichier): string
{
    if ($fichier === null || $fichier['error'] !== UPLOAD_ERR_OK) {
        $code = $fichier['error'] ?? UPLOAD_ERR_NO_FILE;
        $messages = [
            UPLOAD_ERR_INI_SIZE  => 'image trop lourde (limite du serveur : ' . ini_get('upload_max_filesize') . ')',
            UPLOAD_ERR_FORM_SIZE => 'image trop lourde',
            UPLOAD_ERR_PARTIAL   => 'envoi interrompu',
            UPLOAD_ERR_NO_FILE   => 'aucun fichier reçu',
        ];
        throw new RuntimeException($messages[$code] ?? 'échec de l’envoi');
    }

    $extensions = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG  => 'png',
        IMAGETYPE_GIF  => 'gif',
        IMAGETYPE_WEBP => 'webp',
    ];
    $info = @getimagesize($fichier['tmp_name']);
    $type = $info[2] ?? null;
    if (!isset($extensions[$type])) {
        throw new RuntimeException('le fichier doit être une image JPEG, PNG, GIF ou WebP');
    }
    return $extensions[$type];
}

/** Enregistre une image envoyée dans la galerie et renvoie la ligne créée. */
function galerie_ajouter_image(PDO $pdo, array $galerie, array $fichier): array
{
    $extension = image_extension_valide($fichier);

    $cheminDisque = galerie_chemin_disque($galerie['dossier_stage']);
    if (!is_dir($cheminDisque)) {
        mkdir($cheminDisque, 0775, true);
    }

    $nomFichier = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    if (!move_uploaded_file($fichier['tmp_name'], $cheminDisque . '/' . $nomFichier)) {
        throw new RuntimeException('impossible d’enregistrer l’image sur le serveur');
    }

    $cheminWeb = GALERIE_RACINE_WEB . basename($galerie['dossier_stage']) . '/' . $nomFichier;
    $stmt = $pdo->prepare('INSERT INTO image (id_dossier, chemin_image) VALUES (?, ?)');
    $stmt->execute([$galerie['id_dossier'], $cheminWeb]);

    return ['id_image' => (int) $pdo->lastInsertId(), 'chemin_image' => $cheminWeb];
}

/** Supprime le fichier d'une image à partir de son chemin web. */
function galerie_supprimer_fichier(string $cheminWeb): void
{
    if (strpos($cheminWeb, GALERIE_RACINE_WEB) !== 0) {
        return; // on ne touche qu'aux fichiers rangés dans Annexes/Galerie
    }
    $chemin = __DIR__ . '/' . $cheminWeb;
    if (is_file($chemin)) {
        unlink($chemin);
    }
}

/** Supprime toutes les images (fichiers + lignes) d'une galerie. */
function galerie_vider(PDO $pdo, int $idDossier): void
{
    $stmt = $pdo->prepare('SELECT chemin_image FROM image WHERE id_dossier = ?');
    $stmt->execute([$idDossier]);
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $chemin) {
        galerie_supprimer_fichier((string) $chemin);
    }
    $pdo->prepare('UPDATE galerie SET id_image = NULL WHERE id_dossier = ?')->execute([$idDossier]);
    $pdo->prepare('DELETE FROM image WHERE id_dossier = ?')->execute([$idDossier]);
}

/** Supprime le dossier (vide) d'une galerie. */
function galerie_supprimer_dossier(string $dossier): void
{
    $chemin = galerie_chemin_disque($dossier);
    if (is_dir($chemin)) {
        foreach (glob($chemin . '/*') ?: [] as $fichier) {
            if (is_file($fichier)) {
                unlink($fichier);
            }
        }
        @rmdir($chemin);
    }
}

/** Réponse JSON pour les appels fetch(). */
function repondre_json(array $donnees, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($donnees, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
