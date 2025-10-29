<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: index.php?entity=utilisateurs&action=se_connecter');
    exit;
}

// ✅ On récupère l'utilisateur depuis la session
$user = $_SESSION['user'];

// ✅ On gère le cas où $_SESSION['user'] peut être un objet ou un tableau
$userRole = is_object($user) ? $user->getRole() : ($user['role'] ?? null);
$userPseudo = is_object($user) ? $user->getPseudo() : ($user['pseudo'] ?? 'Utilisateur');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f5f8f6; }
        .dashboard-header { text-align: center; margin-top: 40px; margin-bottom: 50px; }
        .dashboard-header h2 { color: #198754; font-weight: 700; }
        .dashboard-header p { font-size: 18px; color: #333; }
        .card-dashboard { border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); transition: transform 0.2s ease-in-out; }
        .card-dashboard:hover { transform: translateY(-5px); }
        .card-dashboard .card-body { display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 30px; }
        .card-dashboard .card-body h5 { margin-top: 15px; font-weight: 600; color: #198754; }
        .card-dashboard .card-body p { font-size: 14px; color: #555; text-align: center; }
        .card-dashboard .btn { margin-top: 15px; }
        .dashboard-container { max-width: 1200px; margin: auto; }
    </style>
</head>
<body>

<div class="container dashboard-container">
    <div class="dashboard-header">
        <h2>Bienvenue, <?= htmlspecialchars($userPseudo) ?> !</h2>
        <p>Voici votre tableau de bord. Accédez aux différentes fonctionnalités ci-dessous.</p>
        <a href="index.php?entity=accueil&action=logout" class="btn btn-outline-success mt-3">Se déconnecter</a>
    </div>

    <div class="row g-4">

        <!-- Carte : Liste des utilisateurs (admin uniquement) -->
        <?php if ((int)$userRole === 2): ?>
            <div class="col-md-4">
                <div class="card card-dashboard">
                    <div class="card-body">
                        <img src="https://img.icons8.com/color/96/000000/group.png" alt="Liste Utilisateurs"/>
                        <h5>Liste des utilisateurs</h5>
                        <p>Consultez tous les utilisateurs, modifiez ou supprimez-les si nécessaire.</p>
                        <a href="index.php?entity=utilisateurs&action=liste_utilisateurs" class="btn btn-success">Accéder</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Carte : Créer un covoiturage -->
        <div class="col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <img src="https://img.icons8.com/color/96/000000/car--v1.png" alt="Créer un Covoiturage"/>
                    <h5>Créer un covoiturage</h5>
                    <p>Planifiez un nouveau covoiturage et renseignez les trajets et horaires.</p>
                    <a href="index.php?entity=covoiturages&action=creer_covoiturage" class="btn btn-success">Créer</a>
                </div>
            </div>
        </div>

        <!-- Carte : Rechercher un covoiturage -->
        <div class="col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <img src="https://img.icons8.com/color/96/000000/search--v1.png" alt="Rechercher un Covoiturage"/>
                    <h5>Rechercher un covoiturage</h5>
                    <p>Trouvez un covoiturage correspondant à vos besoins et réservez votre place.</p>
                    <a href="index.php?entity=covoiturages&action=rechercher_covoiturage" class="btn btn-success">Rechercher</a>
                </div>
            </div>
        </div>

        <!-- Carte : Liste des covoiturages -->
        <div class="col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <img src="https://img.icons8.com/color/96/000000/list.png" alt="Liste des Covoiturages"/>
                    <h5>Liste des covoiturages</h5>
                    <p>Consultez la liste complète des covoiturages disponibles et réservez facilement.</p>
                    <a href="index.php?entity=covoiturages&action=liste_covoiturages" class="btn btn-success">Voir la liste</a>
                </div>
            </div>
        </div>

        <!-- Carte : Mon profil -->
        <div class="col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <img src="https://img.icons8.com/color/96/000000/user-male-circle--v1.png" alt="Mon Profil"/>
                    <h5>Mon profil</h5>
                    <p>Consultez et mettez à jour vos informations personnelles et préférences.</p>
                    <a href="index.php?entity=utilisateurs&action=mon_profil" class="btn btn-success">Accéder</a>
                </div>
            </div>
        </div>

    </div> <!-- row -->
</div> <!-- container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
