<header class="mb-5">
    <nav class="navbar navbar-expand-lg navbar-theme-green fixed-top px-4 py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="index.php">
                ECORIDE
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?entity=accueil&action=covoiturages">Covoiturage</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?entity=accueil&action=contact">Contact</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">Les fonctions</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                            <li><a class="dropdown-item" href="index.php?entity=accueil&action=dashboard">Tableau de bord ECORIDE</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=liste_utilisateurs">Liste des utilisateurs</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=covoiturages&action=liste_covoiturages_ecologique">Liste des covoiturages</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=creer_compte">Créer un compte</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=covoiturages&action=rechercher_covoiturages">Rechercher covoiturage</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=covoiturages&action=creer_covoiturage">Créer un covoiturage</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=charte_graphique">Charte graphique</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=tableau_de_bord">Tableau de bord</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=vehicules&action=liste_vehicules">Mes véhicules</a></li>

                            <li><a class="dropdown-item" href="index.php?entity=covoiturages&action=resultats_recherche">Resultat covoiturage</a></li>


                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="index.php?entity=accueil&action=connexion">Connexion</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
