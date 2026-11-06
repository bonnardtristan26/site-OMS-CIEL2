<?php
require_once 'check_auth.php';
require_once 'galerie_outils.php';

$pdo = galerie_pdo();

$id = filter_input(INPUT_POST, 'stage_id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: Admin_Stage.php?error=1');
    exit();
}

$stmt = $pdo->prepare('SELECT id_stage, image_path FROM stage WHERE id_stage = ?');
$stmt->execute([$id]);
$stage = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$stage) {
    header('Location: Admin_Stage.php?error=1');
    exit();
}

// Galerie du stage : photos (fichiers + lignes) puis dossier
$stmt = $pdo->prepare('SELECT id_dossier, dossier_stage FROM galerie WHERE id_stage = ?');
$stmt->execute([$id]);
$galeries = $stmt->fetchAll(PDO::FETCH_ASSOC);

try {
    $pdo->beginTransaction();
    foreach ($galeries as $galerie) {
        galerie_vider($pdo, (int) $galerie['id_dossier']);
    }
    // Liaisons vers le stage (sinon les clés étrangères bloquent la suppression)
    $pdo->prepare('UPDATE utilisateur SET id_stage = NULL WHERE id_stage = ?')->execute([$id]);
    $pdo->prepare('DELETE FROM participer WHERE id_stage = ?')->execute([$id]);
    $pdo->prepare('DELETE FROM creer WHERE id_stage = ?')->execute([$id]);
    // La galerie est supprimée en cascade avec le stage
    $pdo->prepare('DELETE FROM stage WHERE id_stage = ?')->execute([$id]);
    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    header('Location: Admin_Stage.php?error=1');
    exit();
}

foreach ($galeries as $galerie) {
    galerie_supprimer_dossier((string) $galerie['dossier_stage']);
}

// Photo principale téléversée à la création (stage_<id>.ext) ; les images
// d'origine partagées (padel.jpg, ...) ne sont jamais supprimées
$photo = basename((string) $stage['image_path']);
if (preg_match('/^stage_' . $id . '\.(jpg|png|gif|webp)$/', $photo)) {
    $chemin = __DIR__ . '/../Annexes/Images/' . $photo;
    if (is_file($chemin)) {
        unlink($chemin);
    }
}

header('Location: Admin_Stage.php?error=0');
exit();
