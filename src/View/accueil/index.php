<!DOCTYPE html>

<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Accueil - Ecoride</title>
        <!-- STYLES CSS -->
        <link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/header/header.css">
        <link rel="stylesheet" href="assets/css/main/main.css">
        <link rel="stylesheet" href="assets/css/footer/header.css">
        <link rel="stylesheet" href="assets/css/theme/theme.css">
    </head>

    <!-- HEADER: MENU DE NAVIGATION,LOGO -->
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <!-- HEADER: BANNIERE PAGE D'ACCUEIL -->
    <?php include __DIR__ . '/../partials/banniere.php'; ?>


    <body class="d-flex flex-column min-vh-100">

        <!-- FORMULAIRE DE RECHERCHE DE COVOITURAGE -->
        <?php include __DIR__ . '/../partials/formulaire_recherche_covoiturages.php'; ?>



        <!-- SECTION MAIN DE L'ACCUEIL: PRESENTATION DE L'ENTREPRISE -->
        <?php include __DIR__ . '/../partials/main_accueil.php'; ?>

        <?php include __DIR__ . '/../partials/resultats_covoiturages.php'; ?>

    </body>

    <!-- FOOTER: CONTACT, MENTIONS LEGALES -->
    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <!-- SCRIPT JAVASCRIPT -->
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Optionnel : icônes Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</html>
