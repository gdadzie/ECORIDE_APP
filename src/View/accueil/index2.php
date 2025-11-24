<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/theme/theme.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/js/covoiturages/formulaire_recherche_covoiturages.js" defer></script>


</head>
<body>

<!-- Inclure le menu -->
<?php include __DIR__ . '/../partials/header.php'; ?>

<!-- Hero / Formulaire de recherche rapide -->
<section class="hero mb-5">
    <h1 class="mb-3">Bienvenue sur EcoRide</h1>
    <p class="mb-4">Partagez vos trajets et réduisez votre impact environnemental.</p>
    <?php include __DIR__ . '/../partials/form/formulaire_recherche_covoiturages.php'; ?>
</section>

<!-- Présentation de l’entreprise -->
<div class="container mb-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h2>Qui sommes-nous ?</h2>
            <p>EcoRide est une startup française qui promeut le covoiturage écologique. Notre objectif est de réduire l’impact environnemental des déplacements tout en offrant une solution économique et conviviale pour tous les voyageurs.</p>
        </div>
        <div class="col-md-6">
            <img src="assets/images/covoiturage_2.jpg" class="img-fluid rounded" alt="Covoiturage écologique">
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center">
    <p>Contact : <a href="mailto:contact@ecoride.fr" class="text-white">contact@ecoride.fr</a> | <a href="mentions.php" class="text-white">Mentions légales</a></p>
</footer>





</body>
</html>
