<!-- src/View/accueil/index.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - EcoRide</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Header -->
<header class="bg-success text-white text-center py-5">
    <!-- Menu principal -->
    <!-- Menu transparent / moderne -->
    <nav class="navbar navbar-expand-lg navbar-dark position-absolute w-100 top-0 start-0 px-4 py-3 mb-5" style="z-index: 10;">
        <div class="container">
            <!-- Logo / Accueil -->
            <a class="navbar-brand fw-light fs-3 text-white" href="http://localhost:8000/index.php">
                EcoRide
            </a>

            <!-- Bouton hamburger pour mobile -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Liens -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-light px-3" href="http://localhost:8000/index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-light px-3" href="http://localhost:8000/index.php?entity=accueil&action=covoiturage">Covoiturage</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-light px-3" href="index.php?entity=accueil&action=contact">Contact</a>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-light px-3" href="http://localhost:8000/index.php?entity=utilisateurs&action=se_connecter">Connexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container">
        <h1 class="display-4 mt-5">Bienvenue sur EcoRide</h1>
        <p class="lead">Découvrez nos covoiturages écologiques et économiques</p>
    </div>
</header>

<!-- Main -->
<main class="flex-grow-1 container my-5 text-center">

    <!-- Barre de recherche -->
    <section>
        <h2 class="mt-5 mb-5">Trouvez un itinéraire</h2>
        <form action="index.php" method="get" class="row justify-content-center g-2">
            <input type="hidden" name="entity" value="utilisateurs">
            <input type="hidden" name="action" value="search">
            <div class="col-auto">
                <input type="text" name="depart" class="form-control" placeholder="Ville de départ" required>
            </div>
            <div class="col-auto">
                <input type="text" name="arrivee" class="form-control" placeholder="Ville d'arrivée" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Rechercher</button>
            </div>
        </form>
    </section>

    <!-- Présentation -->
    <section class="mt-5">
        <h2 class="mt-5">Présentation de l'entreprise</h2>
        <p class="">EcoRide vous propose une solution de covoiturage simple, économique et écologique. Rejoignez notre communauté et voyagez responsable !</p>
        <div class="row justify-content-center mt-4">
            <div class="col-md-2 mb-3">
                <img src="/assets/images/car1.jpg" class="img-fluid rounded" alt="Covoiturage écologique">
            </div>
            <div class="col-md-2 mb-3">
                <img src="/assets/images/car2.jpg" class="img-fluid rounded" alt="Voyage économique">
            </div>
        </div>
    </section>





</main>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3 mt-auto">
    <p>Contact : <a href="mailto:contact@ecoride.fr" class="text-white">contact@ecoride.fr</a> |
        <a href="index.php?entity=accueil&action=mentions" class="text-white"
