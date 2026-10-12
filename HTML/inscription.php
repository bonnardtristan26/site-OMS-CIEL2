<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=omstjj;charset=utf8mb4',
    'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$identifiant = $_POST['nom_enfant'] ?? '';
$prenom = $_POST['prenom_enfant'] ?? '';
$age = $_POST['age'] ?? '';
$sexe = $_POST['sexe'] ?? '';
if ($sexe == 'F') {
    $sexe = 'Femme';
} elseif ($sexe == 'M') {
    $sexe = 'Homme';
}
$niveau_d_etude = $_POST['niveau_d_etude'] ?? '';
if ($niveau_d_etude == 'P') {
    $niveau_d_etude = 'Primaire';
} elseif ($niveau_d_etude == 'M') {
    $niveau_d_etude = 'Collège';
} elseif ($niveau_d_etude == 'A') {
    $niveau_d_etude = 'Lycée';
}
$nom_parent = $_POST['nom_parent'] ?? '';
$prenom_parent = $_POST['prenom_parent'] ?? '';
$parente = $_POST['lien_parente'] ?? '';
if ($parente == 'P') {
    $parente = 'Père';
} elseif ($parente == 'M') {
    $parente = 'Mère';
} else {
    $parente = 'Autre';
}
$num_tel = $_POST['num_tel'] ?? '';
$mail = $_POST['mail'] ?? '';
$num_tel_secours = $num_tel;
$mail_secours = $mail;
$id_stage = $_POST['id_stage'] ?? 'erreur';

var_dump($identifiant, $prenom, $age, $sexe, $niveau_d_etude, $nom_parent, $prenom_parent, $parente, $num_tel, $mail, $num_tel_secours, $mail_secours);
    $stmt = $pdo->prepare("INSERT INTO utilisateur (nom_enfant, prenom_enfant, age, sexe, niveau_d_etude, nom_parent, prenom_parent, lien_parente, num_tel, mail, num_tel_secours, mail_secours) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stat = $stmt->execute([$identifiant ,$prenom,$age, $sexe, $niveau_d_etude, $nom_parent, $prenom_parent, $parente, $num_tel, $mail, $num_tel_secours, $mail_secours]);
    $select = $pdo->query("SELECT id_participant FROM utilisateur WHERE nom = '$identifiant' AND prenom = '$prenom' AND num_tel = '$num_tel'")->fetch(PDO::FETCH_ASSOC);
    $pepparticiper = $pdo->prepare("INSERT INTO participer (id_participant, id_stage) VALUES (?, ?)");
    $participer = $pepparticiper->execute([$select['id_participant'], $id_stage]);
    header('Location: stage.php');
?>