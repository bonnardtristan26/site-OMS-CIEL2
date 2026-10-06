<?php
require_once 'check_auth.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OMS - Office Municipal du Sport</title>
<link rel="icon" type="image/svg+xml" href="../Annexes/Images/logo_ball_oms.svg">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
<link rel="stylesheet" href="../CSS/Cree_utilisateur.css">
</head>
<body>

  <!-- EN-TÊTE -->
  <header class="oms-header py-2">
    <div class="container-fluid px-4 px-lg-5 d-flex align-items-center">
      <a href="Admin_Accueil.php" class="d-flex align-items-center me-4" aria-label="Accueil OMS">
        <img src="../Annexes/Images/logo_OMS.svg" alt="OMS - Office Municipal du Sport" class="oms-logo">
      </a>
      <nav class="d-flex align-items-center" aria-label="Navigation principale">
        <a class="nav-link" href="Admin_Stage.php">Stage</a>
        <a class="nav-link" href="Admin_Galerie.php">Galerie</a>
      </nav>
    </div>
  </header>

  <!-- CONTENU PRINCIPAL -->
  <main class="oms-main pt-4 pb-0">
    <div class="container">
      <section class="oms-rouge p-4 p-lg-4">

        <nav class="oms-tabs mb-4 mb-lg-5" aria-label="Navigation administration">
          <span class="oms-sep" aria-hidden="true"></span>
          <a class="oms-tab" href="Admin_Stage.php">Stage</a>
          <span class="oms-sep" aria-hidden="true"></span>
          <a class="oms-tab" href="Admin_Galerie.php">Galerie</a>
          <span class="oms-sep" aria-hidden="true"></span>
          <a class="oms-tab" href="Admin_Accueil.php">Accueil</a>
          <span class="oms-sep" aria-hidden="true"></span>
          <a class="oms-tab actif" href="Admin_Utilisateur.php" aria-current="page">Utilisateur</a>
          <span class="oms-sep" aria-hidden="true"></span>
        </nav>

        <div class="user-form-wrapper">
          <h4 class="info-title">INFO UTILISATEUR :</h4>

          <form action="make_user.php" method="post" class="user-form">
            <div class="mb-3">
              <input type="text" name="identifiant" class="form-control custom-input" placeholder="IDENTIFIANT..." id="identifiant">
            </div>
            <div class="mb-4">
              <input type="password" name="password" class="form-control custom-input" placeholder="MOT DE PASSE..." id="password">
            </div>
            <button type="submit" class="btn btn-valider" href="make_user.php">VALIDER</button>
          </form>
          <p id="user-incorecrt" class="text-white fw-bold text-uppercase mt-4" style="font: size 0.6em;rem;"> </p>
        </div>
      </section>
    </div>

  </main>

  <!-- PIED DE PAGE -->
  <footer class="oms-footer">
    <div class="container-fluid px-4 px-lg-5 d-flex align-items-center justify-content-between py-3">
      <p class="mb-0 fw-bold" style="font-size:1rem;">
        Dernière mise à jour 07/09/2026 - Adresse : 25 Rue de Strasbourg 44000 NANTES -
        <a href="FAQ.php" class="text-white text-decoration-none">FAQ</a>
      </p>
      <img src="../Annexes/Images/logo_ball_oms.svg" alt="" class="ballon" aria-hidden="true">
    </div>
  </footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script>
  const urlParams = new URLSearchParams(window.location.search);
  const error = urlParams.get('error');

  if (error === '1') {
    document.getElementById('user-incorecrt').textContent = 'Erreur lors de la création de l\'utilisateur.';
  } else if (error === '0') {
    document.getElementById('user-incorecrt').textContent = 'Utilisateur créé avec succès.';
  }
</script>
</body>
</html>