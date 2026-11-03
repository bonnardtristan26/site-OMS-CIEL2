<!DOCTYPE html>
<?php
$stage = null;
$erreurChargement = '';
$idStage = filter_input(INPUT_GET, 'id_stage', FILTER_VALIDATE_INT);

if ($idStage === false || $idStage === null || $idStage < 1) {
  http_response_code(400);
  $erreurChargement = 'Identifiant de stage invalide.';
} else {
  try {
    $pdo = new PDO(
      'mysql:host=localhost;dbname=omstjj;charset=utf8mb4',
      'root',
      '',
      [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $requete = $pdo->prepare('SELECT * FROM stage WHERE id_stage = ?');
    $requete->execute([$idStage]);
    $stage = $requete->fetch(PDO::FETCH_ASSOC) ?: null;

    if ($stage === null) {
      http_response_code(404);
      $erreurChargement = 'Ce stage n’existe pas ou n’est plus disponible.';
    }
  } catch (PDOException $e) {
    http_response_code(500);
    $erreurChargement = 'Impossible de charger les informations du stage.';
    error_log($e->getMessage());
  }
}

$intitule = (string) ($stage['intitule'] ?? 'Stage');
$description = (string) ($stage['description'] ?? '');
$image = (string) ($stage['image_path'] ?? '');
$nbInscrits = max(0, (int) ($stage['nb_inscrits'] ?? 0));
$nbPlaces = max(0, (int) ($stage['nb_places'] ?? 0));
$typeActivite = strtolower((string) ($stage['type_activite'] ?? ''));
$classeCouleur = strtolower($intitule) === 'padel'
  ? 'oms-theme-green'
  : (str_contains($typeActivite, 'mer')
    ? 'oms-theme-blue'
    : (str_contains($typeActivite, 'plein air') ? 'oms-theme-green' : 'oms-theme-red'));
?>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OMS - Inscription <?= htmlspecialchars($intitule, ENT_QUOTES, 'UTF-8') ?></title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Poppins:wght@400;600;700;800&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Notre feuille de style -->
  <link rel="stylesheet" href="../CSS/formulaire_inscription.css?v=<?= filemtime(__DIR__ . '/../CSS/formulaire_inscription.css') ?>">
</head>
<body>

  <!-- HEADER -->
  <header class="oms-header">
    <div class="container-fluid">
      <div class="row align-items-center py-2">
        <div class="col-auto logo">
          <a href="index.php" aria-label="Retour à l'accueil">
            <img src="../Annexes/Images/logo_OMS.svg" alt="Logo OMS - Office Municipal du Sport">
          </a>
        </div>
        <div class="col d-flex justify-content-center justify-content-lg-start ps-lg-5">
          <nav class="oms-nav" aria-label="Navigation principale">
            <a href="stage.php">STAGE</a>
            <a href="galerie.php">GALERIE</a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <!-- ===== SECTION PRINCIPALE ===== -->
  <?php if ($stage === null): ?>
  <main class="oms-main position-relative">
    <div class="container py-5">
      <p role="alert"><?= htmlspecialchars($erreurChargement, ENT_QUOTES, 'UTF-8') ?></p>
      <a href="stage.php">Retour à la liste des stages</a>
    </div>
  </main>
  <?php else: ?>
  <main class="oms-main position-relative <?= $classeCouleur ?>">
    <div class="container-fluid px-0">
      <div class="row g-0">

        <!-- Colonne colorée : formulaire -->
        <div class="col-lg-8 oms-green-panel <?= $classeCouleur ?>">

          <span class="oms-badge" aria-label="<?= $nbInscrits ?> inscrits sur <?= $nbPlaces ?> places"><?= $nbInscrits ?>/<?= $nbPlaces ?></span>

          <h1 class="oms-title"><?= htmlspecialchars(strtoupper($intitule), ENT_QUOTES, 'UTF-8') ?></h1>
          <p class="oms-description"><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>

          <form action="inscription.php"  method="post" class="oms-form">
            <h2 class="oms-section-title">Informations concernant l'enfant</h2>

            <div class="mb-3">
              <input type="text" class="form-control oms-input" name = "nom_enfant" placeholder="Nom..." minlength="2" maxlength="50" required>
            </div>
            <div class="mb-3">
              <input type="text" class="form-control oms-input" name = "prenom_enfant" placeholder="Prénom..." minlength="2" maxlength="50" required>
            </div>
            <div class="mb-3">
              <input type="text" class="form-control oms-input" name = "age" placeholder="Âge ..." minlength="1" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
            </div>
            <div class="mb-3">
                <select class="form-select oms-input" name = "sexe" required>
                    <option value="" disabled selected>Sexe...</option>
                    <option value="M">Homme</option>
                    <option value="F">Femme</option>
                </select>
            </div>
            <div class="mb-4">
              <select class="form-select oms-input" name = "niveau_d_etude" required>
                    <option value="" disabled selected>Niveau d'étude scolaire...</option>
                    <option value="P">primaire</option>
                    <option value="M">collège</option>
                    <option value="A">lycée</option>
                </select>
            </div>

            <h2 class="oms-section-title">Informations concernant le/les parents</h2>

            <div class="mb-3">
              <input type="text" class="form-control oms-input" name = "nom_parent" placeholder="Nom..." minlength="2" maxlength="25" required>
            </div>
             <div class="mb-3">
              <input type="text" class="form-control oms-input" name = "prenom_parent" placeholder="prénom..." minlength="2" maxlength="25" required>
            </div>
            <div class="mb-3">
                <select class="form-select oms-input" name = "lien_parente" required>
                    <option value="" disabled selected>Lien de parenté...</option>
                    <option value="P">Père</option>
                    <option value="M">Mère</option>
                    <option value="A">Autre</option>
                </select>
            </div>
            <div class="mb-3">
              <input type="text" name = "num_tel" class="form-control oms-input" inputmode="numeric" pattern="[0-9]*" title="Numéro à 10 chiffres" placeholder="Téléphone..." maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control oms-input" placeholder="Mail..." name = "mail" maxlength="50" required />
            </div>
            <button type="submit" class="oms-submit-button"><span>Envoyer</span></button>
            <input type="hidden" name="id_stage" value="<?= (int) $idStage ?>">
          </form>

        </div>

        <!-- Colonne image : court de padel -->
        <div class="col-lg-4 oms-image-panel">
          <?php if ($image !== ''): ?>
            <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($intitule, ENT_QUOTES, 'UTF-8') ?>">
          <?php endif; ?>
        </div>

      </div>
    </div>
  </main>
  <?php endif; ?>

  <!-- FOOTER -->
  <footer class="oms-footer py-3">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center gap-2">
      <p>Dernière mise à jour 15/09/2026&nbsp;&nbsp;-&nbsp;&nbsp;Adresse&nbsp;: 25 Rue de Strasbourg 44000 NANTES - <a href="FAQ.php">FAQ</a></p>
      <img src="../Annexes/Images/logo_ball_oms.svg" alt="" class="ball-icon">
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>     // Vérifier si l'URL contient le paramètre "error"
    const urlParams = new URLSearchParams(window.location.search);
    const errorParam = urlParams.get('error');

    if (errorParam === '1') {
      // Afficher le message d'erreur
      const errorMessage = document.getElementById('motdepasse-incorecrt');
      errorMessage.textContent = "Nom d'utilisateur ou mot de passe incorrect.";
    }
  </script>
</body>
</html>