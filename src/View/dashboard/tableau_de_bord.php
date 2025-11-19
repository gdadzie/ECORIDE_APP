<?php
$photoPathWeb = $photoPathWeb ?? '/uploads/photos/default-avatar.jpg';
$userPseudo   = $userPseudo ?? 'Utilisateur';
$userRole     = $userRole ?? 0; // ou 'user' selon ton système de rôles
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Accueil</title>
    <link rel="stylesheet" href="assets/css/tableau_de_bord.css">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>


</head>
<body>
<!-- Inclure le menu -->
<?php include __DIR__ . '/../partials/header.php'; ?>

<section class="hero mb-5">
    <!-- En-tête avec photo -->

        <img src="<?= htmlspecialchars($photoPathWeb) ?>" alt="Photo de profil" class="user-photo" id="currentPhoto">
        <h2>Bonjour, <?= htmlspecialchars($userPseudo) ?> !</h2>
        <button type="button" class="btn btn-outline-success btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalPhoto">
            Changer ma photo
        </button>
        <a href="../utilisateurs/index.php?entity=accueil&action=logout" class="btn-logout mt-2 d-block">Se déconnecter</a>

</section>
<div class="container dashboard-container">
   </div>

    <!-- Cartes dashboard -->
    <div class="row g-4">
        <?php if ((int)$userRole === 2): ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card card-dashboard">
                    <div class="card-body">
                        <h5>Liste des utilisateurs</h5>
                        <p>Consultez tous les utilisateurs, modifiez ou supprimez-les si nécessaire.</p>
                        <a href="../utilisateurs/index.php?entity=utilisateurs&action=liste_utilisateurs" class="btn btn-success">Accéder</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h5>Créer un covoiturage</h5>
                    <p>Planifiez un nouveau covoiturage et renseignez les trajets et horaires.</p>
                    <a href="index.php?entity=covoiturages&action=creer_covoiturage" class="btn btn-success">Créer</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h5>Rechercher un covoiturage</h5>
                    <p>Trouvez un covoiturage correspondant à vos besoins et réservez votre place.</p>
                    <a href="index.php?entity=accueil&action=covoiturages" class="btn btn-success">Rechercher</a>
                </div>
            </div>
        </div>

        <!-- Mes covoiturages -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h5>Mes covoiturages</h5>
                    <p>Consultez et gérez tous vos covoiturages personnels.</p>
                    <a href="index.php?entity=covoiturages&action=mes_covoiturages" class="btn btn-success">Voir mes covoiturages</a>
                </div>
            </div>
        </div>

        <!-- Mes véhicules -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h5>Mes véhicules</h5>
                    <p>Consultez vos véhicules et ajoutez-en de nouveaux si nécessaire.</p>
                    <a href="index.php?entity=vehicules&action=liste_vehicules" class="btn btn-success">Voir mes véhicules</a>
                </div>
            </div>
        </div>

        <!-- Messagerie -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h5>Messagerie</h5>
                    <p>Échangez avec les conducteurs, l’administrateur ou d’autres utilisateurs.</p>
                    <a href="../utilisateurs/index.php?entity=messagerie&action=inbox" class="btn btn-success">Accéder à la messagerie</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h5>Mon profil</h5>
                    <p>Consultez et mettez à jour vos informations personnelles et préférences.</p>
                    <a href="../utilisateurs/index.php?entity=utilisateurs&action=mon_profil" class="btn btn-success">Accéder</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal changement photo -->
<div class="modal fade" id="modalPhoto" tabindex="-1" aria-labelledby="modalPhotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formPhoto" action="../utilisateurs/index.php?entity=utilisateurs&action=mise_a_jour_profil" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPhotoLabel">Changer ma photo de profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <img id="previewPhoto" src="<?= htmlspecialchars($photoPathWeb) ?>" class="user-photo mb-3" style="max-width:120px; max-height:120px;">
                        <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
                    </div>
                    <small class="text-muted">Taille max 2 Mo. Formats : JPG, PNG, GIF.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
    const inputPhoto = document.getElementById('photo');
    const previewPhoto = document.getElementById('previewPhoto');
    const currentPhoto = document.getElementById('currentPhoto');

    inputPhoto.addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = evt => previewPhoto.src = evt.target.result;
        reader.readAsDataURL(file);
    });

    const form = document.getElementById('formPhoto');
    form.addEventListener('submit', () => {
        currentPhoto.src = previewPhoto.src;
    });
</script>

</body>
</html>
