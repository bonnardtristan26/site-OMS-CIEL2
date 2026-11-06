<?php
require_once 'check_auth.php';
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OMS | Admin – Création de stage</title>
    <link rel="icon" type="image/svg+xml" href="../Annexes/Images/logo_ball_oms.svg">

    <!-- Bootstrap 5.3.8 (dernière version stable) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts : Lalezar (titres) + Ubuntu (texte) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,400;1,700&display=swap"
        rel="stylesheet">

    <style>
        /* ============ OMS – Thème administration ============ */
        :root {
            --oms-rouge: #ff0000;
            --oms-rouge-fonce: #b00000;
            --oms-sombre: #101b17;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Ubuntu', Arial, Helvetica, sans-serif;
            background: #fff;
        }

        main {
            flex: 1 0 auto;
        }

        .titre {
            font-family: 'Lalezar', 'Arial Black', sans-serif;
            font-weight: 400;
            letter-spacing: .02em;
        }

        /* ---- En-tête ---- */
        .oms-header {
            background: var(--oms-sombre);
        }

        .oms-logo {
            display: block;
            height: 84px;
            width: auto;
        }

        .oms-header .nav-link {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-family: 'Lalezar', sans-serif;
            font-size: 2.5rem;
            line-height: 1;
            letter-spacing: .03em;
            padding: .3rem 1.6rem;
        }

        .oms-header .nav-link:hover {
            color: #ff4d4d;
        }

        /* ---- Bandeau rouge ---- */
        .oms-rouge {
            background: var(--oms-rouge);
        }

        /* Le bandeau rouge remplit la hauteur jusqu'au pied de page */
        .oms-main {
            display: flex;
            flex-direction: column;
        }

        .oms-main>.container {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
        }

        .oms-main>.container>section {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
        }

        /* ---- Onglets de navigation admin ---- */
        .oms-tabs {
            display: flex;
            align-items: stretch;
        }

        .oms-tab {
            flex: 1 1 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.1rem 0;
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-family: 'Lalezar', sans-serif;
            font-size: 2.4rem;
            line-height: 1;
            letter-spacing: .02em;
        }

        .oms-tab:hover {
            color: #ffe1e1;
        }

        .oms-tab.actif {
            background: var(--oms-rouge-fonce);
        }

        .oms-tab.actif:hover {
            color: #fff;
        }

        .oms-sep {
            flex: 0 0 3px;
            background: #fff;
        }

        /* ---- Liste des stages ---- */
        .oms-liste {
            background: var(--oms-rouge-fonce);
        }

        .oms-ligne {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .95rem 1.5rem;
        }

        .oms-ligne+.oms-ligne {
            border-top: 2px solid #fff;
        }

        .oms-sport {
            font-family: 'Lalezar', sans-serif;
            font-size: 1.75rem;
            color: #fff;
            letter-spacing: .04em;
            min-width: 230px;
        }

        .oms-date {
            flex: 1 1 auto;
            color: #fff;
            font-weight: 700;
            font-size: 1.45rem;
        }

        /* ---- Boutons "parallelogramme" ---- */
        .oms-btn {
            display: inline-block;
            border: 0;
            border-radius: 0;
            cursor: pointer;
            background: var(--oms-sombre);
            color: #fff;
            font-family: 'Lalezar', sans-serif;
            font-size: 2.4rem;
            line-height: 1.1;
            letter-spacing: .03em;
            text-transform: uppercase;
            padding: .6rem 2.3rem .45rem;
            transform: skewX(-14deg);
            box-shadow: 3px 4px 0 rgba(0, 0, 0, .28);
        }

        .oms-btn>span {
            display: inline-block;
            transform: skewX(14deg);
        }

        .oms-btn:hover {
            background: #000;
            color: #fff;
        }

        .oms-btn--blanc {
            background: #fff;
            color: #111;
            font-family: 'Ubuntu', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            padding: .5rem 1.4rem .4rem;
            letter-spacing: .02em;
        }

        .oms-btn--blanc:hover {
            background: #f2f2f2;
            color: #000;
        }

        .oms-btn--x {
            width: 48px;
            height: 42px;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: 'Ubuntu', sans-serif;
            font-weight: 900;
            font-size: 1.2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .oms-btn--x:hover {
            background: #e8e8e8;
            color: #000;
        }

        /* ---- Champs de formulaire ---- */
        .oms-champ {
            border: 0;
            border-radius: 0;
            background: #fff;
            color: #111;
            font-size: 1.25rem;
            padding: .75rem 1rem;
            box-shadow: none;
        }

        .oms-champ::placeholder {
            color: #8c8c8c;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        .oms-champ:focus {
            border-color: transparent;
            outline: 0;
            box-shadow: none;
            background: #fff;
            color: #111;
        }

        .oms-champ--actu {
            min-height: 235px;
        }

        /* ---- Accueil / actualités ---- */
        .oms-actu-titre {
            color: #fff;
            font-size: 2rem;
        }

        /* ---- Pied de page ---- */
        .oms-footer {
            background: var(--oms-sombre);
            color: #fff;
            flex-shrink: 0;
        }

        .oms-footer .ballon {
            width: 46px;
            height: 46px;
            display: block;
        }

        .div_check {
            display : inline-flex;
            text-align : center;
            align-items : center;
        }

        .label_check_1  {
            width: 40px;
            height: 40px;
            margin : 0px 10px 10px 40px; /* top, bottom, left, right */
            background-size : contain;
            background-repeat : no-repeat;
            background-image: url(../Annexes/Images/checkbox/not_checked.png);
            cursor: pointer;
        }

        .label_check_2  {
            width: 40px;
            height: 40px;
            margin : 0px 10px 10px 40px; /* top, bottom, left, right */
            background-size : contain;
            background-repeat : no-repeat;
            background-image: url(../Annexes/Images/checkbox/not_checked.png);
            cursor: pointer;
        }

        .label_check_3  {
            width: 40px;
            height: 40px;
            margin : 0px 10px 10px 40px; /* top, bottom, left, right */
            background-size : contain;
            background-repeat : no-repeat;
            background-image: url(../Annexes/Images/checkbox/not_checked.png);
            cursor: pointer;
        }

        p {
            color: #fff;
            font-family: 'Lalezar', sans-serif;
            font-size : 1.2rem;
        }

        input[type="checkbox"] {
            display : none;
        }

        #check_1:checked + .label_check_1 {
            background-image: url(../Annexes/Images/checkbox/checked.png);
        }

        #check_2:checked + .label_check_2 {
            background-image: url(../Annexes/Images/checkbox/checked.png);
        }

        #check_3:checked + .label_check_3 {
            background-image: url(../Annexes/Images/checkbox/checked.png);
        }

        .etude_pres {
            margin-bottom : 50px;
        }

        #places_restantes {
            width : 40px;
        }

        /* Pour Chrome, Safari, Edge et Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Pour Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }

        /* Flèches */
        .fleche {
            flex: 0 0 auto;
            width: clamp(20px, 12vw, 20px);
            aspect-ratio: 1 / 1.15;
            padding: 0;
            border: 0;
            background: none;
            cursor: pointer;
        }

        .fleche svg {
            width: 100%;
            height: 100%;
            display: block;
            fill: #262d24; /* vert très foncé du header */
        }

        .fleche:hover svg,
        .fleche:focus-visible svg {
            fill: #008f3f; /* vert OMS */
        }

        .form__input {
            font-family: 'Roboto', sans-serif;
            color: #333;
            font-size: 1.2rem;
            padding: 1.5rem 1rem;
            border-radius: 0.2rem;
            background-color: rgb(255, 255, 255);
            border: none;
            width: 70px;
            height: 10px;
            display: block;
            border-bottom: 0.3rem solid transparent;
            transition: all 0.3s;
        }
        
        /* Mobile : flèches plus petites */
        @media (max-width: 576px) {
            .fleche { width: 10px; }
        }

        /* ---- Création : libellés, dépôt du dossier, statut ---- */
        .oms-label {
            display: block;
            color: #fff;
            font-family: 'Lalezar', sans-serif;
            font-size: 1.2rem;
            letter-spacing: .03em;
            text-transform: uppercase;
            margin-bottom: .3rem;
        }

        #places_restantes {
            width: 70px;
            border: 0;
            font-weight: 700;
            font-size: 1.25rem;
            padding: .35rem 0;
        }

        .oms-info {
            color: #fff;
            font-weight: 700;
        }

        .oms-photo-apercu {
            display: block;
            max-width: min(100%, 320px);
            max-height: 220px;
            object-fit: contain;
            margin-top: .75rem;
            background: #fff;
        }

        .oms-depot {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1rem 1.5rem;
            padding: 1.25rem 1.5rem;
            border: 3px dashed rgba(255, 255, 255, .75);
            transition: background .15s;
        }

        .oms-depot.survol {
            background: var(--oms-rouge-fonce);
            border-color: #fff;
        }

        .oms-statut {
            background: var(--oms-rouge-fonce);
            color: #fff;
            font-weight: 700;
            padding: .9rem 1.25rem;
            white-space: pre-line;
        }

        .oms-btn:disabled {
            opacity: .6;
            cursor: wait;
        }

        /* ---- Responsive ---- */
        @media (max-width: 991.98px) {
            .oms-logo {
                height: 58px;
            }

            .oms-header .nav-link {
                font-size: 1.5rem;
                padding: .3rem .8rem;
            }

            .oms-tab {
                font-size: 1.5rem;
            }

            .oms-h1 {
                font-size: 2.9rem;
            }

            .oms-btn {
                font-size: 1.45rem;
                padding: .5rem 1.3rem .4rem;
            }

            .oms-sport {
                font-size: 1.25rem;
                min-width: 0;
            }

            .oms-date {
                font-size: 1.05rem;
            }

            .oms-ligne {
                flex-wrap: wrap;
                gap: .5rem;
                padding: .8rem 1rem;
            }

            .oms-date {
                flex: 1 1 100%;
                order: 5;
            }

            .oms-actu-titre {
                font-size: 1.5rem;
            }

            .oms-champ {
                font-size: 1rem;
            }
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
                    <a class="oms-tab actif" href="Admin_Stage.php" aria-current="page">Stage</a>
                    <span class="oms-sep" aria-hidden="true"></span>
                    <a class="oms-tab" href="Admin_Galerie.php">Galerie</a>
                    <span class="oms-sep" aria-hidden="true"></span>
                    <a class="oms-tab" href="Admin_Accueil.php">Accueil</a>
                    <span class="oms-sep" aria-hidden="true"></span>
                    <a class="oms-tab" href="Admin_Utilisateur.php">Utilisateur</a>
                    <span class="oms-sep" aria-hidden="true"></span>

                </nav>

                <form id="form-stage" action="create_stage.php" method="post" enctype="multipart/form-data" novalidate>

                <div class="mb-3" style="max-width:420px;">
                    <label for="activite" class="visually-hidden">Activité</label>
                    <input type="text" class="form-control oms-champ" id="activite" name="activite"
                        placeholder="Activité..." maxlength="100" required>
                </div>

                <div class="div_check">
                    <input type="checkbox" id="check_1" name="type_activite[]" value="jeu de ballon">
                    <label class="label_check_1" for="check_1"></label><p>JEU DE BALLE</p>

                    <input type="checkbox" id="check_2" name="type_activite[]" value="jeu en mer">
                    <label class="label_check_2" for="check_2"></label><p>JEU EN MER</p>

                    <input type="checkbox" id="check_3" name="type_activite[]" value="jeu en plein-air">
                    <label class="label_check_3" for="check_3"></label><p>JEU EN PLEIN-AIR</p>
                </div>

                <div class="mb-4 etude_pres" style="max-width:420px;">
                    <label for="niv_etude" class="visually-hidden">Niveau d'étude scolaire</label>
                    <input type="text" class="form-control oms-champ" id="niv_etude" name="niv_etude" placeholder="Niveau d'étude scolaire..." maxlength="100">
                </div>

                <div class="mb-4 etude_pres" style="max-width:420px;">
                    <label for="presentation" class="visually-hidden">Présentation</label>
                    <input type="text" class="form-control oms-champ" id="presentation" name="presentation" placeholder="Présentation..." maxlength="255">
                </div>

                <div class="d-flex flex-wrap gap-3 mb-4" style="max-width:420px;">
                    <div class="flex-fill">
                        <label for="date_stage" class="oms-label">Date du stage</label>
                        <input type="date" class="form-control oms-champ" id="date_stage" name="date_stage" required>
                    </div>
                    <div class="flex-fill">
                        <label for="heure_stage" class="oms-label">Heure</label>
                        <input type="time" class="form-control oms-champ" id="heure_stage" name="heure_stage" required>
                    </div>
                </div>

                <label for="places_restantes" class="oms-label">Nombre de places</label>
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="recenter">
                    <!-- Flèche précédente -->
                        <button type="button" class="fleche fleche-gauche" aria-label="Retirer une place" data-places="-1">
                            <svg viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                                <polygon points="100,0 0,50 100,100" />
                            </svg>
                        </button>

                        <input type="number" id="places_restantes" name="nb_places" value="1" min="1" required
                            style="text-align: center" class="mb-3"/>

                        <!-- Flèche suivante -->
                        <button type="button" class="fleche fleche-droite" aria-label="Ajouter une place" data-places="1">
                            <svg viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                                <polygon points="0,0 100,50 0,100" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="photo_stage" class="oms-label">Photo d’illustration du stage</label>
                    <input type="file" id="photo_stage" name="photo" accept="image/jpeg,image/png,image/gif,image/webp"
                        class="visually-hidden">
                    <button type="button" class="oms-btn oms-btn--blanc mb-2" data-ouvrir="photo_stage">
                        <span>Importer une photo</span>
                    </button>
                    <p class="oms-info mb-0" id="nom_photo">Aucune photo sélectionnée</p>
                    <img id="apercu_photo" class="oms-photo-apercu" alt="Aperçu de la photo d’illustration du stage" hidden>
                </div>

                <p class="oms-statut mb-4" id="statut" role="status" aria-live="polite" hidden></p>
                <button type="submit" class="oms-btn" id="btn_ajouter"><span>Ajouter le stage</span></button>

                </form>
            </section>
        </div>
    </main>

    <!-- ===================== PIED DE PAGE ===================== -->
    <footer class="oms-footer">
        <div class="container-fluid px-4 px-lg-5 d-flex align-items-center justify-content-between py-3">
            <p class="mb-0 fw" style="font-size:1rem;">
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
            const form = document.getElementById('form-stage');
            const places = document.getElementById('places_restantes');
            const photo = document.getElementById('photo_stage');
            const statut = document.getElementById('statut');
            const bouton = document.getElementById('btn_ajouter');
            const apercuPhoto = document.getElementById('apercu_photo');
            let urlApercuPhoto = null;

            // Boutons "Importer une photo" / "Téléverser un dossier"
            document.querySelectorAll('[data-ouvrir]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById(btn.dataset.ouvrir).click();
                });
            });

            // Flèches du nombre de places
            document.querySelectorAll('[data-places]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const valeur = (parseInt(places.value, 10) || 0) + parseInt(btn.dataset.places, 10);
                    places.value = Math.max(1, valeur);
                });
            });

            photo.addEventListener('change', function () {
                document.getElementById('nom_photo').textContent =
                    photo.files.length ? photo.files[0].name : 'Aucune photo sélectionnée';
                if (urlApercuPhoto) URL.revokeObjectURL(urlApercuPhoto);
                urlApercuPhoto = photo.files.length ? URL.createObjectURL(photo.files[0]) : null;
                apercuPhoto.src = urlApercuPhoto || '';
                apercuPhoto.hidden = !urlApercuPhoto;
            });

            function afficher(message) {
                statut.hidden = false;
                statut.textContent = message;
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                if (!form.reportValidity()) return;
                bouton.disabled = true;
                afficher('Ajout du stage…');

                let resultat;
                try {
                    const donnees = new FormData(form);
                    donnees.delete('photo');
                    if (photo.files.length) {
                        donnees.append('photo', await OMSGalerie.redimensionner(photo.files[0]));
                    }
                    const reponse = await fetch(form.action, { method: 'POST', body: donnees });
                    resultat = await reponse.json();
                } catch (err) {
                    resultat = { ok: false, erreur: 'Impossible d’ajouter le stage. Vérifiez la connexion puis réessayez.' };
                }
                if (!resultat.ok) {
                    afficher(resultat.erreur);
                    bouton.disabled = false;
                    return;
                }

                window.location.href = 'Admin_Stage.php?cree=' + resultat.id_stage;
            });
        })();
    </script>
</body>

</html>