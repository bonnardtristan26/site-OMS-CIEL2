<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OMS - Inscription Padel</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Poppins:wght@400;600;700;800&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Notre feuille de style -->
  <link rel="stylesheet" href="../CSS/formulaire_inscription.css">
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
  <main class="oms-main position-relative">
    <div class="container-fluid px-0">
      <div class="row g-0">

        <!-- Colonne verte : formulaire -->
        <div class="col-lg-8 oms-green-panel">

          <!-- Badge 11/20 -->
          <span class="oms-badge">11/20</span>

          <h1 class="oms-title">PADEL</h1>
          <p class="oms-description">
            Le padel est un sport de raquette ludique, accessible et ultra-dynamique
            qui se joue en double sur un court réduit entouré de vitres.
          </p>

          <form>
            <h2 class="oms-section-title">Informations concernant l'enfant</h2>

            <div class="mb-3">
              <input type="text" class="form-control oms-input" placeholder="Nom et prénom..." minlength="2" maxlength="50" required>
            </div>
            <div class="mb-3">
              <input type="text" class="form-control oms-input" placeholder="Âge ..." minlength="1" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
            </div>
            <div class="mb-3">
                <select class="form-select oms-input" required>
                    <option value="" disabled selected>Sexe...</option>
                    <option value="M">Homme</option>
                    <option value="F">Femme</option>
                </select>
            </div>
            <div class="mb-4">
              <select class="form-select oms-input" required>
                    <option value="" disabled selected>Niveau d'étude scolaire...</option>
                    <option value="P">primaire</option>
                    <option value="M">collège</option>
                    <option value="A">lycée</option>
                </select>
            </div>

            <h2 class="oms-section-title">Informations concernant le/les parents</h2>

            <div class="mb-3">
              <input type="text" class="form-control oms-input" placeholder="Nom et prénom..." minlength="2" maxlength="25" required>
            </div>
            <div class="mb-3">
                <select class="form-select oms-input" required>
                    <option value="" disabled selected>Lien de parenté...</option>
                    <option value="P">Père</option>
                    <option value="M">Mère</option>
                    <option value="A">Autre</option>
                </select>
            </div>
            <div class="mb-3">
              <input type="text" class="form-control oms-input" inputmode="numeric" pattern="[0-9]*" title="Numéro à 10 chiffres" placeholder="Téléphone..." maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control oms-input" placeholder="Mail..."id="email" maxlength="50" required />
            </div>
            <button type="submit" class="oms-submit-button"><span>Envoyer</span></button>
          </form>

        </div>

        <!-- Colonne image : court de padel -->
        <div class="col-lg-4 oms-image-panel">
          <img
            src="https://images.unsplash.com/photo-1646649853703-7645147474ba?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
            alt="Court de padel"
          >
        </div>

      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="oms-footer py-3">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center gap-2">
      <p>Dernière mise à jour 15/09/2026&nbsp;&nbsp;-&nbsp;&nbsp;Adresse&nbsp;: 25 Rue de Strasbourg 44000 NANTES - <a href="FAQ.php">FAQ</a></p>
      <img src="../Annexes/Images/logo_ball_oms.svg" alt="" class="ball-icon">
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>