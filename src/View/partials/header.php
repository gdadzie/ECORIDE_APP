<?php
// Démarrage de la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifie si l'utilisateur est connecté
$isConnected = isset($_SESSION['user']) && $_SESSION['user'] instanceof \Entity\Utilisateur;
$userPseudo = $isConnected ? $_SESSION['user']->getPseudo() : '';
?>

<header class="mb-5">
    <nav class="navbar navbar-expand-lg navbar-theme-green fixed-top px-4 py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="index.php">ECORIDE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?entity=accueil&action=covoiturages">Covoiturage</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?entity=accueil&action=contact">Contact</a></li>

                    <!-- Dropdown "Les fonctions" -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Les fonctions
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="index.php?entity=accueil&action=dashboard">Tableau de bord ECORIDE</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=liste_utilisateurs">Liste des utilisateurs</a></li>
                            <li><a class="dropdown-item" href="index.php?entity=covoiturages&action=liste_covoiturages_ecologique">Liste des covoiturages</a></li>
                        </ul>
                    </li>

                    <!-- Connexion ou pseudo -->
                    <?php if ($isConnected): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($userPseudo) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=tableau_de_bord">Tableau de bord</a></li>
                                <li><a class="dropdown-item" href="index.php?entity=covoiturages&action=creer_covoiturage">Créer un covoiturage</a></li>
                                <li><a class="dropdown-item" href="index.php?entity=covoiturages&action=liste_covoiturages_ecologique">Mes covoiturages</a></li>
                                <li><a class="dropdown-item" href="index.php?entity=vehicules&action=liste_vehicules">Mes véhicules</a></li>
                                <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=mise_a_jour_profil">Mettre à jour mon profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="index.php?entity=utilisateurs&action=deconnexion">Se déconnecter</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?entity=accueil&action=connexion"><i class="bi bi-person-circle me-1"></i> Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
