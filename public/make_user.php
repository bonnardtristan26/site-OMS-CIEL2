<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=ciel2627-b2;charset=utf8mb4',
    'ciel2627-b2', 'jtWccys1j0%l$6NJ',  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$identifiant = $_POST['identifiant'];
$password    = $_POST['password'] ?? '';
$hash        = hash('sha256', $password) ?? '';

$releve = $pdo->query("SELECT * FROM admin WHERE nom = '$identifiant' AND mdp = '$hash'")->fetch(PDO::FETCH_ASSOC);
if (!$releve) {
    $stmt = $pdo->prepare("INSERT INTO admin (nom, mdp) VALUES (?, ?)");
    $stat = $stmt->execute([$identifiant, $hash]);
    if ($stat) {
        $_SESSION['logged_in'] = true;
        header('Location: Cree_utilisateur.php?error=0');
        exit();
    } else {
        header('Location: Cree_utilisateur.php?error=1');
    
}
}else {
    header('Location: Cree_utilisateur.php?error=1');
    exit();
}




?>