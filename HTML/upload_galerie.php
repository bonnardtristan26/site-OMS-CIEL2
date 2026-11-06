<?php
/*
 * Ajout d'UNE image dans une galerie (appel fetch, une requête par photo :
 * on évite ainsi les limites max_file_uploads / post_max_size de PHP
 * quand on dépose un dossier entier).
 * POST : id_dossier, image (fichier)
 * Réponse JSON : { ok, id_image, chemin_image } ou { ok:false, erreur }.
 */
require_once 'check_auth.php';
require_once 'galerie_outils.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    repondre_json(['ok' => false, 'erreur' => 'Méthode non autorisée.'], 405);
}

$idDossier = filter_input(INPUT_POST, 'id_dossier', FILTER_VALIDATE_INT);
if (!$idDossier) {
    repondre_json(['ok' => false, 'erreur' => 'Galerie invalide.'], 400);
}

$pdo = galerie_pdo();
$stmt = $pdo->prepare('SELECT id_dossier, dossier_stage FROM galerie WHERE id_dossier = ?');
$stmt->execute([$idDossier]);
$galerie = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$galerie) {
    repondre_json(['ok' => false, 'erreur' => 'Galerie introuvable.'], 404);
}

try {
    $image = galerie_ajouter_image($pdo, $galerie, $_FILES['image'] ?? []);
} catch (RuntimeException $e) {
    repondre_json(['ok' => false, 'erreur' => ucfirst($e->getMessage()) . '.'], 400);
}

repondre_json(['ok' => true] + $image);
