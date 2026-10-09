<<<<<<< Updated upstream
<?php
$stages = [];
$erreurChargement = false;

try {
  $pdo = new PDO(
    'mysql:host=localhost;dbname=omstjj;charset=utf8mb4',
    'root',
    '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
  $stages = $pdo->query('SELECT * FROM stage ORDER BY id_stage')->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $erreurChargement = true;
  error_log($e->getMessage());
}

$imagesParDefaut = [
  'padel' => '../Annexes/Images/image_accueil.png',
  'foot' => 'https://images.unsplash.com/photo-1622659097509-4d56de14539e?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
  'surf' => 'https://plus.unsplash.com/premium_photo-1672510003630-18d2535419ef?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
  'basket' => 'https://images.unsplash.com/photo-1706841533842-3bbfafb7fdbc?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
  'volley' => 'https://images.unsplash.com/photo-1728968916776-7aab52f5ea99?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
];
?>
=======
<?php 
try { 
$pdo = new PDO('mysql:host=localhost;dbname=omstjj;charset=utf8mb4', 
'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
$releve = $pdo->query('SELECT * FROM stage, image WHERE stage.id_stage = image.id_stage')->fetch(PDO::FETCH_ASSOC); 
} catch (PDOException $e) { $releve = false; } 
?> 
>>>>>>> Stashed changes

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OMS - Stage</title>

  <!-- Google Fonts : Lalezar (titres) / Ubuntu (texte) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Style perso -->
  <link href="../CSS/stage.css" rel="stylesheet">
</head>
<body>

  <!-- HEADER -->
  <header class="oms-header">
    <div class="container-fluid">
      <div class="row align-items-center py-2">
        <div class="col-auto logo">
          <a href="index.html" aria-label="Retour à l'accueil">
            <img src="../Annexes/Images/logo_OMS.svg" alt="Logo OMS - Office Municipal du Sport">
          </a>
        </div>
        <div class="col d-flex justify-content-center justify-content-lg-start ps-lg-5">
          <nav class="oms-nav">
            <a href="stage.html" class="active">STAGE</a>
            <a href="galerie.html">GALERIE</a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <!-- LISTE DES STAGES -->
  <main class="container-fluid p-4">
    <div class="stage-list">

<<<<<<< Updated upstream
      <?php if ($erreurChargement): ?>
        <p>Impossible de charger les stages. Vérifie la connexion à la base de données.</p>
      <?php elseif (!$stages): ?>
        <p>Aucun stage n'est disponible pour le moment.</p>
      <?php else: ?>
        <?php foreach ($stages as $stage): ?>
          <?php
          $intitule = (string) ($stage['intitule'] ?? 'Stage');
          $activite = strtolower((string) ($stage['type_activite'] ?? ''));
          $classeCouleur = strtolower($intitule) === 'padel'
            ? 'stage-green'
            : (str_contains($activite, 'mer')
              ? 'stage-blue'
              : (str_contains($activite, 'plein air') ? 'stage-green' : 'stage-red'));
          $image = (string) ($stage['image_path'] ?? '');
          $imageEstUrl = filter_var($image, FILTER_VALIDATE_URL) !== false;
=======
      <!-- Jeu en plein air -->
      <a class="stage-link" href="Formulaire_inscription.html" aria-label="S'inscrire au stage de padel">
        <article class="stage-banner stage-green">
          <div class="stage-image">
            <img src="<?= htmlspecialchars((string) $releve['chemin_image']) ?>" alt="Court de padel">
          </div>
          <div class="stage-text">
            <span class="stage-badge"><?= htmlspecialchars((int) $releve['nb_inscrits']) ?><span>/<?= htmlspecialchars((int) $releve['nb_places']) ?> </span></span>
            <h2 class="font-title"><?= htmlspecialchars((string) $releve['intitule']) ?>  - LE 01/05/2026</h2>
            <p class="stage-level">Niveaux : <?= htmlspecialchars((string) $releve['niveau_etude']) ?> </p>
            <p class="stage-desc"><?= htmlspecialchars((string) $releve['description']) ?> </p>
          </div>
        </article>
      </a>
>>>>>>> Stashed changes

          if (!$imageEstUrl && ($image === '' || !is_file(__DIR__ . DIRECTORY_SEPARATOR . $image))) {
            $image = $imagesParDefaut[strtolower($intitule)] ?? '../Annexes/Images/image_accueil.png';
          }
          ?>
          <a class="stage-link" href="Formulaire_inscription.html">
            <article class="stage-banner <?= $classeCouleur ?>">
              <div class="stage-image">
                <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($intitule, ENT_QUOTES, 'UTF-8') ?>">
              </div>
              <div class="stage-text">
                <span class="stage-badge"><?= (int) ($stage['nb_inscrits'] ?? 0) ?><span>/<?= (int) ($stage['nb_places'] ?? 0) ?></span></span>
                <h2 class="font-title"><?= htmlspecialchars(strtoupper($intitule), ENT_QUOTES, 'UTF-8') ?></h2>
                <p class="stage-level">Niveaux : <?= htmlspecialchars((string) ($stage['niveau_etude'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                <p class="stage-desc"><?= htmlspecialchars((string) ($stage['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
              </div>
            </article>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </main>

  <!-- FOOTER -->
  <footer class="oms-footer py-3">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center gap-2">
      <p>Dernière mise à jour 07/09/2026&nbsp;&nbsp;-&nbsp;&nbsp;Adresse&nbsp;: 25 Rue de Strasbourg 44000 NANTES - <a href="FAQ.html">FAQ</a></p>
      <a href="Admin_login.php" aria-label="Accéder à la connexion administrateur">
        <img src="../Annexes/Images/logo_ball_oms.svg" alt="" class="ball-icon" style="height:34px;width:auto;">
      </a>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>(function(){function c(){var b=a.contentDocument||(a.contentWindow&&a.contentWindow.document);if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'a3b8607eca9f9e45',t:'MTc4OTQ4MzEyNQ=='};var a=document.createElement('script');a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>