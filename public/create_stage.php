<?php
/*
 * Création d'un stage depuis Admin_création_stage.php (appel fetch).
 * Crée aussi automatiquement la galerie du stage (ligne `galerie` +
 * dossier Annexes/Galerie/stage_<id>/). Les photos du dossier choisi
 * sont ensuite envoyées une par une vers upload_galerie.php.
 * Réponse JSON : { ok, id_stage, id_dossier } ou { ok:false, erreur }.
 */
require_once 'check_auth.php';
require_once 'galerie_outils.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    repondre_json(['ok' => false, 'erreur' => 'Méthode non autorisée.'], 405);
}

$intitule    = trim((string) ($_POST['activite'] ?? ''));
$types       = array_map('trim', (array) ($_POST['type_activite'] ?? []));
$niveau      = trim((string) ($_POST['niv_etude'] ?? ''));
$description = trim((string) ($_POST['presentation'] ?? ''));
$nbPlaces    = filter_var($_POST['nb_places'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$date        = (string) ($_POST['date_stage'] ?? '');
$heure       = (string) ($_POST['heure_stage'] ?? '');

$erreurs = [];
if ($intitule === '' || mb_strlen($intitule) > 100) {
    $erreurs[] = 'l’activité est obligatoire (100 caractères max)';
}
if ($nbPlaces === false || $nbPlaces === null) {
    $erreurs[] = 'le nombre de places doit être d’au moins 1';
}
if (!DateTime::createFromFormat('!Y-m-d', $date)) {
    $erreurs[] = 'la date est obligatoire';
}
if (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $heure)) {
    $erreurs[] = 'l’heure est obligatoire';
}
if (mb_strlen($niveau) > 100) {
    $erreurs[] = 'le niveau d’étude est trop long (100 caractères max)';
}
if (mb_strlen($description) > 255) {
    $erreurs[] = 'la présentation est trop longue (255 caractères max)';
}
$typeActivite = implode(', ', array_filter($types, 'strlen'));
if (mb_strlen($typeActivite) > 100) {
    $erreurs[] = 'type d’activité invalide';
}
if ($erreurs) {
    repondre_json(['ok' => false, 'erreur' => 'Stage non créé : ' . implode(', ', $erreurs) . '.'], 400);
}

$photo = $_FILES['photo'] ?? null;
$avecPhoto = $photo !== null && $photo['error'] !== UPLOAD_ERR_NO_FILE;
if ($avecPhoto) {
    try {
        $extensionPhoto = image_extension_valide($photo);
    } catch (RuntimeException $e) {
        repondre_json(['ok' => false, 'erreur' => 'Photo du stage : ' . $e->getMessage() . '.'], 400);
    }
}

$pdo = galerie_pdo();
$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare(
        'INSERT INTO stage (intitule, type_activite, niveau_etude, description,
                            nb_inscrits, nb_places, date_stage, heure_stage)
         VALUES (?, ?, ?, ?, 0, ?, ?, ?)'
    );
    $stmt->execute([$intitule, $typeActivite, $niveau, $description, $nbPlaces, $date, $heure]);
    $idStage = (int) $pdo->lastInsertId();

    if ($avecPhoto) {
        $nomPhoto = 'stage_' . $idStage . '.' . $extensionPhoto;
        if (!move_uploaded_file($photo['tmp_name'], __DIR__ . '/../Annexes/Images/' . $nomPhoto)) {
            throw new RuntimeException('impossible d’enregistrer la photo du stage');
        }
        $pdo->prepare('UPDATE stage SET image_path = ? WHERE id_stage = ?')
            ->execute(['../Annexes/Images/' . $nomPhoto, $idStage]);
    }

    $idDossier = galerie_creer($pdo, $idStage);
    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    repondre_json(['ok' => false, 'erreur' => 'Stage non créé : ' . $e->getMessage() . '.'], 500);
}

repondre_json(['ok' => true, 'id_stage' => $idStage, 'id_dossier' => $idDossier]);
