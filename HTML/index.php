<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Ubuntu:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../CSS/index.css">
    <title>OMS</title>
</head>

<body>

    <div class="enrobage">
        <div class="prime-header">
            <header class="oms-header">

                <div class="container-fluid">

                    <div class="row align-items-center py-2">

                        <div class="col-auto logo">
                            <a href="index.html" aria-label="Retour à l'accueil">
                                <img src="../Annexes/Images/logo_OMS.svg" alt="Logo OMS - Office Municipal du Sport">
                            </a>
                        </div>

                        <div class="col d-flex justify-content-center justify-content-lg-start ps-lg-5">
                            <nav class="oms-nav" aria-label="Navigation principale">
                                <a href="stage.html">STAGE</a>
                                <a href="galerie.html">GALERIE</a>
                            </nav>
                        </div>

                    </div>

                </div>

            </header>
        </div>

        <!--CONTENT-->
        <main>
            <!--DESCRIPTION-->

            <header class="description py-3 overflow-hidden">

                <div class="container-fluid">
                    <div class="row align-items-center justify-content-between g-3">

                        <div class="col-12 col-md-5 d-flex flex-column align-items-start gap-3 mx-3">
                            <p class="mb-0">
                                L'Office Municipal du Sport (OMS) accueille tous les jeunes de 6 à 15 ans dans nos
                                événements sportifs. Associés aux clubs de la ville, nous aidons à rendre l'activité
                                physique accessible aux jeunes.
                            </p>
                            <a href="stage.html" class="btn btn-dark px-4 py-2 oms-btn">STAGE</a>
                        </div>

                        <div class="col-12 col-md-5">
                            <img src="../Annexes/Images/image_accueil.png" class="img-fluid" alt="image_accueil">
                        </div>
                    </div>

                </div>

            </header>

            <!--ACTUS-->

            <h1 class="mx-3 my-3 h_actu">ACTUALITÉS</h1>

            <div class="container-fluid">
                <article class="box_actu mx-3">

            <!--FOOTER-->
            <footer class="oms-footer py-3">
                <div class="box_text">
                    <div class="col-12 col-md-5">
                        <img src="../Annexes/Images/enfants_football.png" alt="" class="">
                    </div>
                    <h2 class="font-title">SPORT POUR TOUS</h2>
                    <p class="box_desc">L'Office Municipal des Sports accompagne les habitants dans leur pratique sportive et contribue au développement du sport dans la commune. Que vous soyez débutant, sportif régulier ou simplement à...</p>
                </div>
                </article>
            </div>

        </main>

        <!--FOOTER-->
        <footer class="oms-footer py-3">
            <!-- container-fluid : pleine largeur ; d-flex : Flexbox ; flex-wrap : retour à la ligne sur petit écran -->
            <!-- justify-content-between : éloigne le texte du logo ; align-items-center : centre verticalement ; gap-2 : espace entre les éléments -->
            <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center gap-2">
                <p>Dernière mise à jour 15/09/2026&nbsp;&nbsp;-&nbsp;&nbsp;Adresse&nbsp;: 25 Rue de Strasbourg 44000
                    NANTES - <a href="FAQ.html">FAQ</a></p>
                <img src="../Annexes/Images/logo_ball_oms.svg" alt="" class="ball-icon">
            </div>
        </footer>
    </div>


</body>

</html>