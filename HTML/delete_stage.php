<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=omstjj;charset=utf8mb4',
    'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$id = $_POST['stage_id'];

$releve = $pdo->query("SELECT * FROM stage WHERE id_stage = '$id'")->fetch(PDO::FETCH_ASSOC);
if ($releve) {
    $stmt = $pdo->prepare("DELETE FROM stage WHERE id_stage = ?");
    $stat = $stmt->execute([$id]);
    if ($stat) {
        $_SESSION['logged_in'] = true;
        header('Location: Admin_Stage.php?error=0');
        exit(); 
    } else {
        header('Location: Admin_Stage.php?error=1');
    
}
}else {
    header('Location: Admin_Stage.php?error=1');
    exit();
}

