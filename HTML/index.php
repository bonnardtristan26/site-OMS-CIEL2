<?php
$actualites = [];
$erreurActualites = false;

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=omstjj;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $actualites = $pdo->query(
        'SELECT id_actu, intitule, description, chemin_image FROM Actualites ORDER BY id_actu DESC'
    )->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erreurActualites = true;
    error_log($e->getMessage());
}

$nombreActualites = count($actualites);
?>
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
                            <a href="stage.php" class="btn btn-dark px-4 py-2 oms-btn">STAGE</a>
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
                <div class="carousel-actu d-flex align-items-center justify-content-between gap-3" data-carousel tabindex="0" aria-label="Actualités">

                    <button type="button" class="fleche fleche-gauche" data-carousel-prev aria-label="Actualité précédente" aria-controls="actu-liste" <?= $nombreActualites <= 1 ? 'disabled' : '' ?>>
                        <svg viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                            <polygon points="100,0 0,50 100,100" />
                        </svg>
                    </button>

                    <div class="actu-viewport" id="actu-liste" aria-live="polite">
                        <?php if ($erreurActualites): ?>
                            <p class="actu-message" role="alert">Impossible de charger les actualités pour le moment.</p>
                        <?php elseif (!$actualites): ?>
                            <p class="actu-message">Aucune actualité n'est disponible pour le moment.</p>
                        <?php else: ?>
                            <?php foreach ($actualites as $index => $actualite): ?>
                                <?php
                                $intitule = trim((string) ($actualite['intitule'] ?? ''));
                                $description = trim((string) ($actualite['description'] ?? ''));
                                $image = trim((string) ($actualite['chemin_image'] ?? ''));
                                if ($intitule === '') {
                                    $intitule = 'Actualité';
                                }
                                if ($description === '') {
                                    $description = 'Aucune description disponible.';
                                }
                                if ($image === '') {
                                    $image = '../Annexes/Images/enfants_football.png';
                                }
                                ?>
                                <article class="box_actu" data-carousel-slide <?= $index === 0 ? 'aria-hidden="false"' : 'aria-hidden="true" hidden' ?>>
                                    <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($intitule, ENT_QUOTES, 'UTF-8') ?>" class="actu-img">
                                    <div class="box_text">
                                        <h2 class="font-title"><?= htmlspecialchars($intitule, ENT_QUOTES, 'UTF-8') ?></h2>
                                        <p class="box_desc"><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="fleche fleche-droite" data-carousel-next aria-label="Actualité suivante" aria-controls="actu-liste" <?= $nombreActualites <= 1 ? 'disabled' : '' ?>>
                        <svg viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                            <polygon points="0,0 100,50 0,100" />
                        </svg>
                    </button>

                </div>
                <?php if ($nombreActualites > 0): ?>
                    <p class="actu-compteur" data-carousel-status aria-live="polite">1 / <?= $nombreActualites ?></p>
                <?php endif; ?>
            </div>

        </main>

        <!--FOOTER-->
        <footer class="oms-footer py-3">
            <!-- container-fluid : pleine largeur ; d-flex : Flexbox ; flex-wrap : retour à la ligne sur petit écran -->
            <!-- justify-content-between : éloigne le texte du logo ; align-items-center : centre verticalement ; gap-2 : espace entre les éléments -->
            <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center gap-2">
                <p>Dernière mise à jour 15/09/2026&nbsp;&nbsp;-&nbsp;&nbsp;Adresse&nbsp;: 25 Rue de Strasbourg 44000
                    NANTES - <a href="FAQ.php">FAQ</a></p>
                <a href="Admin_login.php" aria-label="Accéder à la connexion administrateur">
                    <img src="../Annexes/Images/logo_ball_oms.svg" alt="" class="ball-icon">
                </a>
            </div>
        </footer>
    </div>

    <script src="../Javascript/index.js"></script>
</body>

</html>