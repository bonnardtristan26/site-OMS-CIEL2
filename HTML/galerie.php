<?php
require_once 'galerie_outils.php';
$pdo = galerie_pdo();

// Galeries qui contiennent au moins une photo, la plus récente en premier
$galeries = $pdo->query(
    "SELECT g.id_dossier, s.intitule, s.date_stage
     FROM galerie g
     JOIN stage s ON s.id_stage = g.id_stage
     WHERE EXISTS (SELECT 1 FROM image i WHERE i.id_dossier = g.id_dossier)
     ORDER BY s.date_stage DESC, s.heure_stage DESC"
)->fetchAll(PDO::FETCH_ASSOC);

$indexActif = 0;
$demande = filter_input(INPUT_GET, 'g', FILTER_VALIDATE_INT);
foreach ($galeries as $i => $g) {
    if ((int) $g['id_dossier'] === $demande) {
        $indexActif = $i;
    }
}

$images = [];
if ($galeries) {
    $stmt = $pdo->prepare('SELECT chemin_image FROM image WHERE id_dossier = ? ORDER BY id_image');
    $stmt->execute([$galeries[$indexActif]['id_dossier']]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OMS - Galerie</title>

  <!-- Google Fonts : Lalezar (titres) / Ubuntu (texte) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Style perso -->
  <link href="../CSS/galerie.css" rel="stylesheet">
</head>
<body>

  <!-- HEADER -->
  <header class="oms-header">
    <div class="container-fluid">
      <div class="row align-items-center py-2">
        <div class="col-auto logo ">
          <a href="index.php" aria-label="Retour à l'accueil">
            <img src="../Annexes/Images/logo_OMS.svg" alt="Logo OMS - Office Municipal du Sport">
          </a>
        </div>
        <div class="col d-flex justify-content-center justify-content-lg-start ps-lg-5">
          <nav class="oms-nav">
            <a href="stage.php">STAGE</a>
            <a href="galerie.php" class="active" aria-current="page">GALERIE</a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <!-- FILTRE PAR STAGE (une galerie par stage) -->
  <section class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center gap-3">
      <span class="oms-filtre-label">FILTRE</span>

      <?php if ($galeries): ?>
      <a class="oms-date-arrow prev text-decoration-none" aria-label="Galerie précédente"
         href="?g=<?= (int) $galeries[max(0, $indexActif - 1)]['id_dossier'] ?>">&#9664;</a>

      <div class="d-flex flex-wrap">
        <?php foreach ($galeries as $i => $g): ?>
        <a href="?g=<?= (int) $g['id_dossier'] ?>"
           class="oms-date-pill text-decoration-none<?= $i === $indexActif ? ' active' : '' ?>"
           <?= $i === $indexActif ? 'aria-current="page"' : '' ?>>
          <?= htmlspecialchars(mb_strtoupper($g['intitule'])) ?> <?= $g['date_stage'] ? date('d/m/Y', strtotime($g['date_stage'])) : '' ?>
        </a>
        <?php endforeach; ?>
      </div>

      <a class="oms-date-arrow next text-decoration-none" aria-label="Galerie suivante"
         href="?g=<?= (int) $galeries[min(count($galeries) - 1, $indexActif + 1)]['id_dossier'] ?>">&#9654;</a>
      <?php else: ?>
      <span>Aucune photo pour le moment.</span>
      <?php endif; ?>
    </div>
  </section>

  <!-- GALERIE PHOTOS -->
  <main class="container-fluid pb-4">
    <div class="oms-gallery">
      <?php foreach ($images as $image): ?>
      <figure>
        <img src="<?= htmlspecialchars($image['chemin_image']) ?>" alt="Photo du stage <?= htmlspecialchars($galeries[$indexActif]['intitule']) ?>" loading="lazy">
      </figure>
      <?php endforeach; ?>
    </div>
  </main>
  <!-- FOOTER -->
  <footer class="oms-footer py-3">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center gap-2">
      <p>Dernière mise à jour 15/09/2026&nbsp;&nbsp;-&nbsp;&nbsp;Adresse&nbsp;: 25 Rue de Strasbourg 44000 NANTES - <a href="FAQ.php">FAQ</a></p>
      <a href="Admin_login.php" aria-label="Accéder à la connexion administrateur">
        <img src="../Annexes/Images/logo_ball_oms.svg" alt="" class="ball-icon" style="height:34px;width:auto;">
      </a>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>