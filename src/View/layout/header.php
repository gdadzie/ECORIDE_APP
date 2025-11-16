<?php
// Démarrage de la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifie si l'utilisateur est connecté
$isConnected = isset($_SESSION['user']) && $_SESSION['user'] instanceof \Entity\Utilisateur;
$userPseudo = $isConnected ? $_SESSION['user']->getPseudo() : '';
?>

<header class="mb-5 header-layout">

    <!-- Inclusion du menu centralisé -->
    <?php include __DIR__ . '/../partials/header.php'; ?>

</header>
