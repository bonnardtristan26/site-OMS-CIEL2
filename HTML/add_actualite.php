<?php 
require_once 'check_auth.php';

$pdo = new PDO('mysql:host=localhost;dbname=omstjj;charset=utf8mb4',
    'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$actu_id = (int)$_GET['actu'] ?? 0;
$actualite = $_POST['actualite'] ?? '';
$titre = $_POST['titre'] ?? '';
$image = $_FILES['monImage']?? '';
if ($image['error'] === UPLOAD_ERR_OK) {
    $nomFichier = basename($image['name']);
    $cheminTemporaire = $image['tmp_name'];
    $cheminDestination = '../Annexes/Images/' . $actu_id . '.' . pathinfo($nomFichier, PATHINFO_EXTENSION);
    move_uploaded_file($cheminTemporaire, $cheminDestination);

}
var_dump($actu_id, $actualite, $titre, $cheminTemporaire, $cheminDestination);

$stmt = $pdo->prepare("INSERT INTO actualites (id_actu, chemin_image, intitule, description) VALUES (?, ?, ?, ?)");
$stat = $stmt->execute([$actu_id, $cheminDestination, $titre, $actualite]);
?>