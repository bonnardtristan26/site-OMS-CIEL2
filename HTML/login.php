<?php
$pdo = new PDO('mysql:host=localhost;dbname=omstjj;charset=utf8mb4',
    'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$identifiant = $_POST['identifiant'];
$password    = $_POST['motdepasse'] ?? '';
$hash        = hash('sha256', $password);


$releve = $pdo->query("SELECT * FROM admin WHERE nom = '$identifiant' AND mdp = '$hash'")->fetch(PDO::FETCH_ASSOC);

if ($releve) {
    // Connexion réussie, rediriger vers la page d'administration
    header('Location: Admin_Stage.html');
    exit();
} else {
    header('Location: Admin_login.php?error=1');
}
?>