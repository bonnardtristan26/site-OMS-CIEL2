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

      <!-- Jeu en plein air -->
      <a class="stage-link" href="Formulaire_inscription.html" aria-label="S'inscrire au stage de padel">
        <article class="stage-banner stage-green">
          <div class="stage-image">
            <img src="https://images.unsplash.com/photo-1646649853703-7645147474ba?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Court de padel">
          </div>
          <div class="stage-text">
            <span class="stage-badge">11<span>/20</span></span>
            <h2 class="font-title">PADEL - LE 01/05/2026 A 15H</h2>
            <p class="stage-level">Niveaux : Collège</p>
            <p class="stage-desc">Le padel est un sport de raquette ludique, accessible et ultra-dynamique qui se joue en double sur un court réduit entouré de vitres.</p>
          </div>
        </article>
      </a>

      <!-- Jeu de balle -->
      <article class="stage-banner stage-red">
        <div class="stage-image">
          <img src="https://images.unsplash.com/photo-1622659097509-4d56de14539e?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Match de football">
        </div>
        <div class="stage-text">
          <span class="stage-badge">20<span>/20</span></span>
          <h2 class="font-title">FOOT - LE 02/05/2026 A 15H</h2>
          <p class="stage-level">Niveaux : Collège</p>
          <p class="stage-desc">Le football est un sport collectif stratégique, intense et populaire qui se joue à onze sur un grand terrain herbé encadré de cages.</p>
        </div>
      </article>

      <!-- Jeu en mer -->
      <article class="stage-banner stage-blue">
        <div class="stage-image">
          <img src="https://plus.unsplash.com/premium_photo-1672510003630-18d2535419ef?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Surfeuse sur une vague">
        </div>
        <div class="stage-text">
          <span class="stage-badge">19<span>/20</span></span>
          <h2 class="font-title">SURFER - LE 05/05/2026 A 15H</h2>
          <p class="stage-level">Niveaux : Collège</p>
          <p class="stage-desc">Le surf est un sport de glisse exigeant qui se pratique sur les vagues, alliant équilibre, lecture de l'océan et sensations fortes.</p>
        </div>
      </article>

      <!-- Jeu de balle -->
      <article class="stage-banner stage-red">
        <div class="stage-image">
          <img src="https://images.unsplash.com/photo-1706841533842-3bbfafb7fdbc?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Terrain de basket">
        </div>
        <div class="stage-text">
          <span class="stage-badge">15<span>/20</span></span>
          <h2 class="font-title">BASKET - LE 08/05/2026 A 15H</h2>
          <p class="stage-level">Niveaux : Collège</p>
          <p class="stage-desc">Le basket-ball est un sport collectif rythmé qui se joue à cinq contre cinq, mêlant vitesse, précision et esprit d'équipe.</p>
        </div>
      </article>

      <!-- Jeu de balle -->
      <article class="stage-banner stage-red">
        <div class="stage-image">
          <img src="https://images.unsplash.com/photo-1728968916776-7aab52f5ea99?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Terrain de volley">
        </div>
        <div class="stage-text">
          <span class="stage-badge">17<span>/20</span></span>
          <h2 class="font-title">VOLLEY - LE 12/05/2026 A 15H</h2>
          <p class="stage-level">Niveaux : Collège</p>
          <p class="stage-desc">Le volley-ball se joue à six sur un terrain séparé par un filet, avec pour objectif de faire tomber le ballon dans le camp adverse.</p>
        </div>
      </article>

    </div>
  </main>

  <!-- FOOTER -->
  <footer class="oms-footer py-3">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center gap-2">
      <p>Dernière mise à jour 07/09/2026&nbsp;&nbsp;-&nbsp;&nbsp;Adresse&nbsp;: 25 Rue de Strasbourg 44000 NANTES - <a href="FAQ.html">FAQ</a></p>
      <a href="Admin_login.html" aria-label="Accéder à la connexion administrateur">
        <img src="../Annexes/Images/logo_ball_oms.svg" alt="" class="ball-icon" style="height:34px;width:auto;">
      </a>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>(function(){function c(){var b=a.contentDocument||(a.contentWindow&&a.contentWindow.document);if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'a3b8607eca9f9e45',t:'MTc4OTQ4MzEyNQ=='};var a=document.createElement('script');a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>