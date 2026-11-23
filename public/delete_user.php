<?php
require_once 'check_auth.php';

session_start();
$pdo = new PDO('mysql:host=localhost;dbname=ciel2627-b2;charset=utf8mb4',
    'ciel2627-b2', 'jtWccys1j0%l$6NJ',  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$id = $_POST['user_id'];

$releve = $pdo->query("SELECT * FROM admin WHERE id_utilisateur = '$id'")->fetch(PDO::FETCH_ASSOC);
if ($releve) {
    $stmt = $pdo->prepare("DELETE FROM admin WHERE id_utilisateur = ?");
    $stat = $stmt->execute([$id]);
    if ($stat) {
        $_SESSION['logged_in'] = true;
        header('Location: Admin_Utilisateur.php?error=0');
        exit();
    } else {
        header('Location: Admin_Utilisateur.php?error=1');
    
}
}else {
    header('Location: Admin_Utilisateur.php?error=1');
    exit();
}

