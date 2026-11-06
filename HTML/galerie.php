<?php
$photosParDate = [];
$erreurChargement = false;

try {
  $pdo = new PDO(
    'mysql:host=localhost;dbname=omstjj;charset=utf8mb4',
    'root',
    '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
  $requete = $pdo->query(
    "SELECT COALESCE(stage.date_stage, galerie.date) AS date_galerie, image.chemin_image
     FROM galerie
     INNER JOIN image ON image.id_dossier = galerie.id_dossier
     LEFT JOIN stage ON stage.id_stage = galerie.id_stage
     WHERE COALESCE(stage.date_stage, galerie.date) IS NOT NULL
       AND image.chemin_image IS NOT NULL
       AND image.chemin_image <> ''
     ORDER BY date_galerie ASC, galerie.id_dossier ASC, image.id_image ASC"
  );

  foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $photo) {
    $date = (string) $photo['date_galerie'];
    $photosParDate[$date][] = (string) $photo['chemin_image'];
  }
} catch (PDOException $e) {
  $erreurChargement = true;
  error_log($e->getMessage());
}

ksort($photosParDate, SORT_STRING);
$datesGalerie = array_keys($photosParDate);
$dateDemandee = (string) ($_GET['date'] ?? '');
$dateActive = in_array($dateDemandee, $datesGalerie, true)
  ? $dateDemandee
  : ($datesGalerie[0] ?? '');
$indexDateActive = array_search($dateActive, $datesGalerie, true);
$indexDatePrecedente = $datesGalerie
  ? ($indexDateActive - 1 + count($datesGalerie)) % count($datesGalerie)
  : null;
$indexDateSuivante = $datesGalerie
  ? ($indexDateActive + 1) % count($datesGalerie)
  : null;
$photosDateActive = $photosParDate[$dateActive] ?? [];
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

  <!-- Photos regroupées par date de galerie -->
  <section class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center gap-3">
      <span class="oms-filtre-label">FILTRE</span>

      <?php if ($datesGalerie): ?>
        <a
          class="oms-date-arrow prev"
          href="?date=<?= urlencode($datesGalerie[$indexDatePrecedente]) ?>"
          aria-label="Date précédente"
          data-date-prev
        >&#9664;</a>
      <?php else: ?>
        <button type="button" class="oms-date-arrow prev" aria-label="Date précédente" disabled>&#9664;</button>
      <?php endif; ?>

      <div class="d-flex flex-wrap" id="galerie-dates" aria-label="Dates des photos">
        <?php foreach ($datesGalerie as $index => $date): ?>
          <a
            class="oms-date-pill text-decoration-none<?= $date === $dateActive ? ' active' : '' ?>"
            href="?date=<?= urlencode($date) ?>"
            data-gallery-date="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>"
            <?= $date === $dateActive ? 'aria-current="date"' : '' ?>
          ><?= htmlspecialchars(date('d/m/Y', strtotime($date)), ENT_QUOTES, 'UTF-8') ?></a>
        <?php endforeach; ?>
      </div>

      <?php if ($datesGalerie): ?>
        <a
          class="oms-date-arrow next"
          href="?date=<?= urlencode($datesGalerie[$indexDateSuivante]) ?>"
          aria-label="Date suivante"
          data-date-next
        >&#9654;</a>
      <?php else: ?>
        <button type="button" class="oms-date-arrow next" aria-label="Date suivante" disabled>&#9654;</button>
      <?php endif; ?>
    </div>
  </section>

  <!-- GALERIE PHOTOS -->
  <main class="container-fluid pb-4">
    <div class="oms-gallery" id="galerie-photos" aria-live="polite">
      <?php if ($erreurChargement): ?>
        <p class="oms-gallery-message" role="alert">Impossible de charger la galerie pour le moment.</p>
      <?php elseif (!$photosParDate): ?>
        <p class="oms-gallery-message">Aucune photo n'est disponible pour le moment.</p>
      <?php else: ?>
        <?php foreach ($photosParDate as $date => $photos): ?>
          <?php foreach ($photos as $index => $cheminImage): ?>
            <figure data-photo-date="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>" <?= $date !== $dateActive ? 'hidden' : '' ?>>
              <img
                src="<?= htmlspecialchars($cheminImage, ENT_QUOTES, 'UTF-8') ?>"
                alt="Photo de la galerie du <?= htmlspecialchars(date('d/m/Y', strtotime($date)), ENT_QUOTES, 'UTF-8') ?>, image <?= $index + 1 ?>"
                loading="lazy"
              >
            </figure>
          <?php endforeach; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <?php if ($photosParDate): ?>
      <p class="oms-gallery-count" id="galerie-compteur" aria-live="polite">
        <?= count($photosDateActive) ?> <?= count($photosDateActive) > 1 ? 'photos' : 'photo' ?>
      </p>
    <?php endif; ?>
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
  <script src="../Javascript/galerie.js"></script>
</body>
</html>