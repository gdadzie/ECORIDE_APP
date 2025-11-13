
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Ecoride</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Tes CSS personnalisés -->
    <link rel="stylesheet" href="assets/css/header/header.css">
    <link rel="stylesheet" href="assets/css/main/main.css">
    <link rel="stylesheet" href="assets/css/footer/header.css">
    <link rel="stylesheet" href="assets/css/theme/theme.css">
</head>
<body class="d-flex flex-column min-vh-100">

<!-- HEADER -->
<?php include __DIR__ . '/../partials/header.php'; ?>

<!-- BANNIERE -->
<?php include __DIR__ . '/../partials/banniere.php'; ?>

<!-- FORMULAIRE DE RECHERCHE -->
<div class="container my-4">
    <?php include __DIR__ . '/../partials/covoiturages/formulaire_recherche_covoiturages.php'; ?>
</div>

<div> <h1 class="text-success text-center"> Bienvenue sur Ecoride</h1></div>
<div><p class="text-center">Voyager économiquement ! Voyager léger !</p></div>
<!-- MAIN ACCUEIL -->
<div class="container-fluid my-5">
    <?php include __DIR__ . '/../partials/accueil/main_accueil.php'; ?>
    <?php include __DIR__ . '/../partials/accueil/features.php'; ?>

    <!-- ============================= -->
    <!-- Bannière de valeurs Ecoride -->
    <!-- ============================= -->


</div>


<!-- FOOTER -->
<?php include __DIR__ . '/../partials/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
