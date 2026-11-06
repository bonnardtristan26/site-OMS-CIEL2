<?php
require_once 'check_auth.php';
require_once 'galerie_outils.php';
$pdo = galerie_pdo();

// Une galerie par stage (créée avec le stage), avec son nombre de photos
$galeries = $pdo->query(
    "SELECT g.id_dossier, s.intitule, s.date_stage, s.heure_stage, COUNT(i.id_image) AS nb_images
     FROM galerie g
     JOIN stage s ON s.id_stage = g.id_stage
     LEFT JOIN image i ON i.id_dossier = g.id_dossier
     GROUP BY g.id_dossier, s.intitule, s.date_stage, s.heure_stage
     ORDER BY s.date_stage DESC, s.heure_stage DESC"
)->fetchAll(PDO::FETCH_ASSOC);

function date_fr(?string $date): string
{
    return $date ? date('d/m/Y', strtotime($date)) : '—';
}
function heure_fr(?string $heure): string
{
    return $heure ? str_replace(':', 'h', substr($heure, 0, 5)) : '';
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OMS | Admin – Galerie</title>
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

        <div class="oms-liste px-3 px-lg-4 py-2 mb-4">
          <?php if (!$galeries): ?>
          <div class="oms-ligne"><span class="oms-date">Aucune galerie : créez un stage pour créer sa galerie.</span></div>
          <?php endif; ?>
          <?php foreach ($galeries as $galerie): ?>
          <div class="oms-ligne">
            <span class="oms-sport"><?= htmlspecialchars($galerie['intitule']) ?></span>
            <span class="oms-date">Le <?= date_fr($galerie['date_stage']) ?> A <?= heure_fr($galerie['heure_stage']) ?> -- <?= (int) $galerie['nb_images'] ?> photo(s)</span>
            <div class="d-flex align-items-center gap-2">
              <form action="delete_image.php" method="post" class="m-0"
                    onsubmit="return confirm('Supprimer toutes les photos de la galerie <?= htmlspecialchars(addslashes($galerie['intitule'])) ?> ?');">
                <input type="hidden" name="id_dossier" value="<?= (int) $galerie['id_dossier'] ?>">
                <input type="hidden" name="vider" value="1">
                <input type="hidden" name="retour" value="liste">
                <button type="submit" class="oms-btn oms-btn--x" aria-label="Vider la galerie <?= htmlspecialchars($galerie['intitule']) ?>"><span>X</span></button>
              </form>
              <a href="Admin_Galerie_detail.php?id=<?= (int) $galerie['id_dossier'] ?>" class="oms-btn oms-btn--blanc text-decoration-none"><span>Accéder</span></a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

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
</body>
</html>
