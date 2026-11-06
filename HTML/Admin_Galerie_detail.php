<?php
require_once 'check_auth.php';
require_once 'galerie_outils.php';
$pdo = galerie_pdo();

$idDossier = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare(
    'SELECT g.id_dossier, g.dossier_stage, s.intitule, s.date_stage, s.heure_stage
     FROM galerie g JOIN stage s ON s.id_stage = g.id_stage
     WHERE g.id_dossier = ?'
);
$stmt->execute([$idDossier ?: 0]);
$galerie = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$galerie) {
    header('Location: Admin_Galerie.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id_image, chemin_image FROM image WHERE id_dossier = ? ORDER BY id_image');
$stmt->execute([$galerie['id_dossier']]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dateStage = $galerie['date_stage'] ? date('d/m/Y', strtotime($galerie['date_stage'])) : '';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OMS | Admin – Galerie <?= htmlspecialchars($galerie['intitule']) ?></title>
  <link rel="icon" type="image/svg+xml" href="../Annexes/Images/logo_ball_oms.svg">

  <!-- Bootstrap 5.3.8 (dernière version stable) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts : Lalezar (titres) + Ubuntu (texte) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <style>
    /* ============ OMS – Thème administration ============ */
    :root{
      --oms-rouge:#ff0000;
      --oms-rouge-fonce:#b00000;
      --oms-sombre:#101b17;
    }
    body{
      display:flex;flex-direction:column;min-height:100vh;
      font-family:'Ubuntu',Arial,Helvetica,sans-serif;
      background:#fff;
    }
    main{flex:1 0 auto;}
    .titre{font-family:'Lalezar','Arial Black',sans-serif;font-weight:400;letter-spacing:.02em;}

    /* ---- En-tête ---- */
    .oms-header{background:var(--oms-sombre);}
    .oms-logo{display:block;height:84px;width:auto;}
    .oms-header .nav-link{
      color:#fff;text-decoration:none;text-transform:uppercase;
      font-family:'Lalezar',sans-serif;font-size:2.5rem;line-height:1;
      letter-spacing:.03em;padding:.3rem 1.6rem;
    }
    .oms-header .nav-link:hover{color:#ff4d4d;}

    /* ---- Bandeau rouge ---- */
    .oms-rouge{background:var(--oms-rouge);}

    /* Le bandeau rouge remplit la hauteur jusqu'au pied de page */
    .oms-main{display:flex;flex-direction:column;}
    .oms-main>.container{display:flex;flex-direction:column;flex:1 1 auto;}
    .oms-main>.container>section{flex:1 1 auto;display:flex;flex-direction:column;}

    /* ---- Onglets de navigation admin ---- */
    .oms-tabs{display:flex;align-items:stretch;}
    .oms-tab{
      flex:1 1 0;display:flex;align-items:center;justify-content:center;
      padding:1.1rem 0;color:#fff;text-decoration:none;text-transform:uppercase;
      font-family:'Lalezar',sans-serif;font-size:2.4rem;line-height:1;letter-spacing:.02em;
    }
    .oms-tab:hover{color:#ffe1e1;}
    .oms-tab.actif{background:var(--oms-rouge-fonce);}
    .oms-tab.actif:hover{color:#fff;}
    .oms-sep{flex:0 0 3px;background:#fff;}

    /* ---- Liste des stages ---- */
    .oms-liste{background:var(--oms-rouge-fonce);}
    .oms-ligne{display:flex;align-items:center;gap:1rem;padding:.95rem 1.5rem;}
    .oms-ligne + .oms-ligne{border-top:2px solid #fff;}
    .oms-sport{font-family:'Lalezar',sans-serif;font-size:1.75rem;color:#fff;letter-spacing:.04em;min-width:230px;}
    .oms-date{flex:1 1 auto;color:#fff;font-weight:700;font-size:1.45rem;}

    /* ---- Boutons "parallelogramme" ---- */
    .oms-btn{
      display:inline-block;border:0;border-radius:0;cursor:pointer;
      background:var(--oms-sombre);color:#fff;
      font-family:'Lalezar',sans-serif;font-size:2.4rem;line-height:1.1;
      letter-spacing:.03em;text-transform:uppercase;
      padding:.6rem 2.3rem .45rem;transform:skewX(-14deg);
      box-shadow:3px 4px 0 rgba(0,0,0,.28);
    }
    .oms-btn>span{display:inline-block;transform:skewX(14deg);}
    .oms-btn:hover{background:#000;color:#fff;}
    .oms-btn--blanc{
      background:#fff;color:#111;font-family:'Ubuntu',sans-serif;font-weight:700;
      font-size:1rem;padding:.5rem 1.4rem .4rem;letter-spacing:.02em;
    }
    .oms-btn--blanc:hover{background:#f2f2f2;color:#000;}
    .oms-btn--x{
      width:48px;height:42px;padding:0;background:#fff;color:#000;
      font-family:'Ubuntu',sans-serif;font-weight:900;font-size:1.2rem;
      display:inline-flex;align-items:center;justify-content:center;
    }
    .oms-btn--x:hover{background:#e8e8e8;color:#000;}

    /* ---- Champs de formulaire ---- */
    .oms-champ{
      border:0;border-radius:0;background:#fff;color:#111;
      font-size:1.25rem;padding:.75rem 1rem;box-shadow:none;
    }
    .oms-champ::placeholder{color:#8c8c8c;text-transform:uppercase;letter-spacing:.02em;}
    .oms-champ:focus{border-color:transparent;outline:0;box-shadow:none;background:#fff;color:#111;}
    .oms-champ--actu{min-height:235px;}

    /* ---- Accueil / actualités ---- */
    .oms-actu-titre{color:#fff;font-size:2rem;}

    /* ---- Pied de page ---- */
    .oms-footer{background:var(--oms-sombre);color:#fff;flex-shrink:0;}
    .oms-footer .ballon{width:46px;height:46px;display:block;}

    /* ---- Responsive ---- */
    @media (max-width: 991.98px){
      .oms-logo{height:58px;}
      .oms-header .nav-link{font-size:1.5rem;padding:.3rem .8rem;}
      .oms-tab{font-size:1.5rem;}
      .oms-h1{font-size:2.9rem;}
      .oms-btn{font-size:1.45rem;padding:.5rem 1.3rem .4rem;}
      .oms-sport{font-size:1.25rem;min-width:0;}
      .oms-date{font-size:1.05rem;}
      .oms-ligne{flex-wrap:wrap;gap:.5rem;padding:.8rem 1rem;}
      .oms-date{flex:1 1 100%;order:5;}
      .oms-actu-titre{font-size:1.5rem;}
      .oms-champ{font-size:1rem;}
    }

    /* ---- Détail d'une galerie ---- */
    .oms-galerie-titre{color:#fff;font-size:2.4rem;line-height:1;}
    .oms-info{color:#fff;font-weight:700;}
    .oms-depot{
      display:flex;flex-wrap:wrap;align-items:center;gap:1rem 1.5rem;
      padding:1.25rem 1.5rem;border:3px dashed rgba(255,255,255,.75);transition:background .15s;
    }
    .oms-depot.survol{background:var(--oms-rouge-fonce);border-color:#fff;}
    .oms-statut{background:var(--oms-rouge-fonce);color:#fff;font-weight:700;padding:.9rem 1.25rem;white-space:pre-line;}
    .oms-photos{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;}
    .oms-photo{position:relative;margin:0;background:var(--oms-rouge-fonce);aspect-ratio:1/1;}
    .oms-photo img{width:100%;height:100%;object-fit:cover;display:block;}
    .oms-photo form{position:absolute;top:8px;right:10px;margin:0;}
    .oms-photo .oms-btn--x{width:38px;height:34px;font-size:1rem;}
    .oms-vide{color:#fff;font-weight:700;background:var(--oms-rouge-fonce);padding:1rem 1.5rem;}
    .oms-btn:disabled{opacity:.6;cursor:wait;}
  </style>
</head>
<body>

  <!-- ===================== EN-TÊTE ===================== -->
  <header class="oms-header py-2">
    <div class="container-fluid px-4 px-lg-5 d-flex align-items-center" style="min-height:108px;">
      <a href="index.php" class="d-flex align-items-center me-4" aria-label="Accueil OMS">
        <img src="../Annexes/Images/logo_OMS.svg" alt="OMS – Office Municipal du Sport" class="oms-logo">
      </a>
      <nav class="d-flex align-items-center" aria-label="Navigation principale">
        <a class="nav-link" href="stage.php">Stage</a>
        <a class="nav-link" href="galerie.php">Galerie</a>
      </nav>
    </div>
  </header>

  <!-- ===================== GALERIE ===================== -->
  <main class="oms-main pt-4 pb-0">
    <div class="container">
      <section class="oms-rouge p-4 p-lg-4">

        <nav class="oms-tabs mb-4 mb-lg-5" aria-label="Navigation administration">
          <span class="oms-sep" aria-hidden="true"></span>
          <a class="oms-tab" href="Admin_Stage.php">Stage</a>
          <span class="oms-sep" aria-hidden="true"></span>
          <a class="oms-tab actif" href="Admin_Galerie.php" aria-current="page">Galerie</a>
          <span class="oms-sep" aria-hidden="true"></span>
          <a class="oms-tab" href="Admin_Accueil.php">Accueil</a>
          <span class="oms-sep" aria-hidden="true"></span>
          <a class="oms-tab" href="Admin_Utilisateur.php">Utilisateur</a>
          <span class="oms-sep" aria-hidden="true"></span>

        </nav>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
          <h1 class="titre oms-galerie-titre mb-0">
            Galerie – <?= htmlspecialchars($galerie['intitule']) ?>
            <?php if ($dateStage): ?><small class="fs-4">(<?= $dateStage ?>)</small><?php endif; ?>
          </h1>
          <a href="Admin_Galerie.php" class="oms-btn oms-btn--blanc text-decoration-none"><span>Retour aux galeries</span></a>
        </div>

        <!-- Dépôt d'un dossier d'images -->
        <input type="file" id="dossier_galerie" accept="image/*" multiple webkitdirectory hidden>
        <input type="file" id="images_galerie" accept="image/jpeg,image/png,image/gif,image/webp" multiple hidden>
        <div class="oms-depot mb-3" id="zone_depot">
          <button type="button" class="oms-btn" data-ouvrir="dossier_galerie"><span>Téléverser un dossier</span></button>
          <button type="button" class="oms-btn oms-btn--blanc" data-ouvrir="images_galerie"><span>Ajouter des images</span></button>
          <span class="oms-info">ou glissez-déposez un dossier d'images ici</span>
        </div>
        <div class="oms-statut mb-4" id="statut" role="status" aria-live="polite" hidden></div>

        <?php if (!$images): ?>
        <p class="oms-vide mb-4">Cette galerie ne contient encore aucune photo.</p>
        <?php else: ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="oms-info"><?= count($images) ?> photo(s)</span>
          <form action="delete_image.php" method="post" class="m-0"
                onsubmit="return confirm('Supprimer toutes les photos de cette galerie ?');">
            <input type="hidden" name="id_dossier" value="<?= (int) $galerie['id_dossier'] ?>">
            <input type="hidden" name="vider" value="1">
            <button type="submit" class="oms-btn oms-btn--blanc"><span>Vider la galerie</span></button>
          </form>
        </div>
        <div class="oms-photos mb-4">
          <?php foreach ($images as $image): ?>
          <figure class="oms-photo">
            <img src="<?= htmlspecialchars($image['chemin_image']) ?>" alt="" loading="lazy">
            <form action="delete_image.php" method="post" onsubmit="return confirm('Supprimer cette photo ?');">
              <input type="hidden" name="id_image" value="<?= (int) $image['id_image'] ?>">
              <button type="submit" class="oms-btn oms-btn--x" aria-label="Supprimer la photo"><span>X</span></button>
            </form>
          </figure>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

      </section>
    </div>
  </main>

  <!-- ===================== PIED DE PAGE ===================== -->
  <footer class="oms-footer">
    <div class="container-fluid px-4 px-lg-5 d-flex align-items-center justify-content-between py-3">
      <p class="mb-0 fw-bold" style="font-size:1rem;">
        Dernière mise à jour 07/09/2026 - Adresse : 25 Rue de Strasbourg 44000 NANTES -
        <a href="#" class="text-white text-decoration-none">FAQ</a>
      </p>
      <img src="../Annexes/Images/logo_ball_oms.svg" alt="" class="ballon" aria-hidden="true">
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../Javascript/upload_galerie.js"></script>
  <script>
    (function () {
      const idDossier = <?= (int) $galerie['id_dossier'] ?>;
      const statut = document.getElementById('statut');
      const boutons = document.querySelectorAll('[data-ouvrir]');

      boutons.forEach(function (btn) {
        btn.addEventListener('click', function () {
          document.getElementById(btn.dataset.ouvrir).click();
        });
      });

      function afficher(message) {
        statut.hidden = false;
        statut.textContent = message;
      }

      async function envoyer(fichiers) {
        if (!fichiers.length) {
          afficher('Aucune image JPEG, PNG, GIF ou WebP trouvée.');
          return;
        }
        boutons.forEach(function (b) { b.disabled = true; });
        const bilan = await OMSGalerie.televerser(idDossier, fichiers, function (fait, total) {
          afficher('Envoi des images : ' + fait + ' / ' + total);
        });
        if (bilan.erreurs.length) {
          boutons.forEach(function (b) { b.disabled = false; });
          afficher(bilan.reussies + ' image(s) ajoutée(s), ' + bilan.erreurs.length + ' refusée(s) :\n'
            + bilan.erreurs.join('\n') + '\nRechargez la page pour voir les images ajoutées.');
          return;
        }
        window.location.reload();
      }

      OMSGalerie.initZone(document.getElementById('zone_depot'), document.getElementById('dossier_galerie'), envoyer);
      OMSGalerie.initZone(null, document.getElementById('images_galerie'), envoyer);
    })();
  </script>
</body>
</html>
