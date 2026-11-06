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
$id_stage = filter_input(INPUT_POST, 'id_stage', FILTER_VALIDATE_INT);

if ($id_stage === false || $id_stage === null || $id_stage < 1) {
    http_response_code(400);
    exit('Identifiant de stage invalide.');
}

$pdo->beginTransaction();

try {
    $stage = $pdo->prepare('SELECT id_stage FROM stage WHERE id_stage = ?');
    $stage->execute([$id_stage]);
    if (!$stage->fetchColumn()) {
        $pdo->rollBack();
        http_response_code(400);
        exit('Le stage demandé n’existe pas.');
    }

    $stmt = $pdo->prepare("INSERT INTO utilisateur (nom_enfant, prenom_enfant, age, sexe, niveau_d_etude, nom_parent, prenom_parent, lien_parente, num_tel, mail, num_tel_secours, mail_secours) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$identifiant, $prenom, $age, $sexe, $niveau_d_etude, $nom_parent, $prenom_parent, $parente, $num_tel, $mail, $num_tel_secours, $mail_secours]);

    $id_participant = $pdo->lastInsertId();
    if ((int) $id_participant < 1) {
        throw new RuntimeException('La colonne id_participant doit être configurée en AUTO_INCREMENT.');
    }

    $pepparticiper = $pdo->prepare('INSERT INTO participer (id_participant, id_stage) VALUES (?, ?)');
    $pepparticiper->execute([$id_participant, $id_stage]);

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $e;
}

header('Location: stage.php');
exit;
?>