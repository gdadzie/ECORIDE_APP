<header class="header">
    <link rel="stylesheet" href="assets/css/header/dashboard_sidebar.css">
</header>
<!-- Sidebar -->
<aside class="sidebar">
    <img src="<?= htmlspecialchars($photoPathWeb) ?>" class="sidebar-photo" alt="Photo utilisateur">
    <h6><?= htmlspecialchars($userPseudo) ?></h6>
    <nav class="nav flex-column mt-4 w-100">
        <a class="nav-link" href="index.php?entity=utilisateurs&action=profil"><i class="bi bi-person"></i> Profil</a>
        <a class="nav-link" href="index.php?entity=covoiturages&action=mes_covoiturages"><i class="bi bi-car-front"></i> Mes covoiturages</a>
        <a class="nav-link" href="index.php?entity=vehicules&action=liste_vehicules"><i class="bi bi-truck"></i> Véhicules</a>
        <a class="nav-link" href="index.php?entity=messagerie&action=inbox"><i class="bi bi-chat-dots"></i> Messagerie</a>
        <?php if ((int)$userRole === 2): ?>
            <a class="nav-link" href="index.php?entity=utilisateurs&action=liste_utilisateurs"><i class="bi bi-people"></i> Utilisateurs</a>
        <?php endif; ?>
        <a class="nav-link mt-3 text-danger" href="index.php?entity=utilisateurs&action=deconnexion"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </nav>
</aside>