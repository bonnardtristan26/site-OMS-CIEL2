<?php
/*
 * Suppression d'images d'une galerie (formulaires de Admin_Galerie*.php).
 * POST id_image  : supprime une photo
 * POST id_dossier + vider=1 : supprime toutes les photos de la galerie
 * POST retour : page vers laquelle revenir (liste ou détail)
 */
require_once 'check_auth.php';
require_once 'galerie_outils.php';

$pdo = galerie_pdo();
$idDossier = filter_input(INPUT_POST, 'id_dossier', FILTER_VALIDATE_INT);
$idImage   = filter_input(INPUT_POST, 'id_image', FILTER_VALIDATE_INT);
$retourListe = ($_POST['retour'] ?? '') === 'liste';

if ($idImage) {
    $stmt = $pdo->prepare('SELECT id_dossier, chemin_image FROM image WHERE id_image = ?');
    $stmt->execute([$idImage]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($image) {
        galerie_supprimer_fichier((string) $image['chemin_image']);
        $pdo->prepare('DELETE FROM image WHERE id_image = ?')->execute([$idImage]);
        $idDossier = (int) $image['id_dossier'];
    }
} elseif ($idDossier && ($_POST['vider'] ?? '') === '1') {
    galerie_vider($pdo, $idDossier);
}

if ($retourListe || !$idDossier) {
    header('Location: Admin_Galerie.php?message=supprime');
} else {
    header('Location: Admin_Galerie_detail.php?id=' . $idDossier . '&message=supprime');
}
exit;
