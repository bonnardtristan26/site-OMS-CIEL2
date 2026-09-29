<?php
echo "test";
$pdo = new PDO('mysql:host=localhost;dbname=meteodb;charset=utf8mb4',
    'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$username = $_POST['username'];
$password_hash = $_POST['hash'];

$releve = $pdo->query("SELECT * FROM admin WHERE nom = $username AND mdp = $password_hash")->fetch(PDO::FETCH_ASSOC);

if ($releve) {
    // Connexion réussie, rediriger vers la page d'administration
    header('Location: Admin_Stage.html');
    exit();
} else {
    echo "Nom d'utilisateur ou mot de passe incorrect.";
}
?>