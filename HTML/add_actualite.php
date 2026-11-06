<?php
require_once 'check_auth.php';

$pdo = new PDO(
    'mysql:host=localhost;dbname=omstjj;charset=utf8mb4',
    'root',
    '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$actuId = filter_input(INPUT_GET, 'actu', FILTER_VALIDATE_INT);
if ($actuId === false || $actuId === null || $actuId < 1 || $actuId > 3) {
    http_response_code(400);
    exit('Identifiant d’actualité invalide.');
}

$description = trim((string) ($_POST['actualite'] ?? ''));
$titre = trim((string) ($_POST['titre'] ?? ''));
if ($titre === '' || $description === '') {
    http_response_code(400);
    exit('Le titre et la description sont obligatoires.');
}

$cheminImage = null;
$image = $_FILES['monImage'] ?? null;
if ($image !== null && $image['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($image['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        exit('Le téléchargement de l’image a échoué.');
    }

    $imageInfo = getimagesize($image['tmp_name']);
    $extensions = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_GIF => 'gif',
        IMAGETYPE_WEBP => 'webp',
    ];
    $typeImage = $imageInfo[2] ?? null;
    if (!isset($extensions[$typeImage])) {
        http_response_code(400);
        exit('Le fichier envoyé doit être une image JPEG, PNG, GIF ou WebP.');
    }

    $nomFichier = $actuId . '.' . $extensions[$typeImage];
    $destination = __DIR__ . '/../Annexes/Images/' . $nomFichier;
    if (!move_uploaded_file($image['tmp_name'], $destination)) {
        http_response_code(500);
        exit('Impossible d’enregistrer l’image.');
    }

    $cheminImage = '../Annexes/Images/' . $nomFichier;
}

$stmt = $pdo->prepare(
    'INSERT INTO Actualites (id_actu, chemin_image, intitule, description)
     VALUES (:id_actu, :chemin_image, :intitule, :description)
     ON DUPLICATE KEY UPDATE
       chemin_image = COALESCE(VALUES(chemin_image), chemin_image),
       intitule = VALUES(intitule),
       description = VALUES(description)'
);
$stmt->execute([
    'id_actu' => $actuId,
    'chemin_image' => $cheminImage,
    'intitule' => $titre,
    'description' => $description,
]);

header('Location: Admin_Accueil.php?actualite=publiee');
exit;
