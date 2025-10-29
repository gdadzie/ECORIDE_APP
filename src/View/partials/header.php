
<!-- Header -->
<header class="text-center py-5 position-relative bg-light">

    <!-- Menu principal -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-theme-green position-absolute w-100 top-0 start-0 px-4 py-3" style="z-index: 10;">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold fs-3" href="index.php">
                <img src="/assets/images/logo.png" alt="EcoRide Logo" height="40">
            </a>

            <!-- Bouton hamburger pour mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Liens -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-light px-3" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-light px-3" href="index.php?entity=accueil&action=covoiturage">Covoiturage</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-light px-3" href="index.php?entity=accueil&action=contact">Contact</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-light px-3" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Les fonctions
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=liste_utilisateurs">Liste des utilisateurs</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=accueil&action=liste_covoiturage">Liste des covoiturages</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=creer_compte">Créer un compte</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=covoiturages&action=rechercher_covoiturage">Rechercher covoiturage</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=covoiturages&action=creer_covoiturage">Créer un covoiturage</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=charte_graphique">Charte graphique</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=tableau_de_bord">Tableau de bord</a></li>


                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-light px-3" href="index.php?entity=utilisateurs&action=se_connecter">Connexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</header>
