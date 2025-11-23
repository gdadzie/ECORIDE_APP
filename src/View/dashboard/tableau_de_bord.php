<?php
// Assure-toi que $utilisateur est défini
$photoPathWeb = $user->getPhoto()
        ? '/uploads/photos/' . $user->getPhoto()
        : '/uploads/photos/default-avatar.jpg';
$userPseudo = $user->getPseudo();
$userRole = $user->getRole(); // 2 = admin
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Mon tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/theme/theme.css">
    <link rel="stylesheet" href="assets/css/tableau_de_bord.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
</head>

<body>

<section class="hero mb-5 fade-in text-center">
    <img src="<?= htmlspecialchars($user->getPhoto() ?: '/uploads/photos/default-avatar.jpg') ?>" alt="Photo de profil" class="user-photo rounded-circle mb-3" id="currentPhoto" style="width:120px;height:120px;object-fit:cover;" />
    <h2 class="mt-3">Bonjour, <?= htmlspecialchars($userPseudo) ?> !</h2>

    <button type="button" class="btn btn-outline-light btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalPhoto">
        <i class="bi bi-camera"></i> Changer ma photo
    </button>

    <a href="index.php?entity=utilisateurs&action=deconnexion" class="btn btn-danger mt-3 d-inline-block">
        <i class="bi bi-box-arrow-right"></i> Se déconnecter
    </a>
</section>

<div class="container pb-5">
    <div class="row g-4 fade-in">

        <?php if ((int)$userRole === 2): ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card card-dashboard h-100">
                    <div class="card-body">
                        <h5><i class="bi bi-people"></i> Liste des utilisateurs</h5>
                        <p>Consultez tous les utilisateurs, modifiez ou supprimez-les si nécessaire.</p>
                        <a href="index.php?entity=utilisateurs&action=liste_utilisateurs" class="btn btn-success">Accéder</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Créer un covoiturage -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard h-100">
                <div class="card-body">
                    <h5><i class="bi bi-plus-circle"></i> Créer un covoiturage</h5>
                    <p>Planifiez un nouveau covoiturage et renseignez les trajets et horaires.</p>
                    <a href="index.php?entity=covoiturages&action=creer_covoiturage" class="btn btn-success">Créer</a>
                </div>
            </div>
        </div>

        <!-- Rechercher un covoiturage -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard h-100">
                <div class="card-body">
                    <h5><i class="bi bi-search"></i> Rechercher un covoiturage</h5>
                    <p>Trouvez un covoiturage correspondant à vos besoins et réservez votre place.</p>
                    <a href="index.php?entity=accueil&action=covoiturages" class="btn btn-success">Rechercher</a>
                </div>
            </div>
        </div>

        <!-- Mes covoiturages -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard h-100">
                <div class="card-body">
                    <h5><i class="bi bi-car-front"></i> Mes covoiturages</h5>
                    <p>Consultez et gérez tous vos covoiturages personnels.</p>
                    <a href="index.php?entity=covoiturages&action=mes_covoiturages" class="btn btn-success">Voir mes covoiturages</a>
                </div>
            </div>
        </div>

        <!-- Mes véhicules -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard h-100">
                <div class="card-body">
                    <h5><i class="bi bi-truck"></i> Mes véhicules</h5>
                    <p>Consultez vos véhicules et ajoutez-en de nouveaux si nécessaire.</p>
                    <a href="index.php?entity=vehicules&action=liste_vehicules" class="btn btn-success">Voir mes véhicules</a>
                </div>
            </div>
        </div>

        <!-- Messagerie -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard h-100">
                <div class="card-body">
                    <h5><i class="bi bi-chat-dots"></i> Messagerie</h5>
                    <p>Échangez avec les conducteurs, l’administrateur ou d’autres utilisateurs.</p>
                    <a href="index.php?entity=messagerie&action=inbox" class="btn btn-success">Accéder à la messagerie</a>
                </div>
            </div>
        </div>

        <!-- Mon profil -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard h-100">
                <div class="card-body">
                    <h5><i class="bi bi-person"></i> Mon profil</h5>
                    <p>Consultez et mettez à jour vos informations personnelles et préférences.</p>
                    <a href="index.php?entity=utilisateurs&action=mise_a_jour_profil" class="btn btn-success">Accéder</a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal changement photo -->
<div class="modal fade" id="modalPhoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formPhoto" action="index.php?entity=utilisateurs&action=mise_a_jour_profil" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Changer ma photo de profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewPhoto" src="<?= htmlspecialchars($photoPathWeb) ?>" class="user-photo mb-3 rounded-circle" style="width:120px; height:120px; object-fit:cover;" />
                    <input class="form-control" type="file" id="photo" name="photo" accept="image/*" />
                    <small class="text-muted">Taille max 2 Mo • Formats : JPG, PNG, GIF</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

    document.getElementById('formPhoto').addEventListener('submit', () => {
        // Met à jour l'image sur le tableau de bord après soumission
        currentPhoto.src = previewPhoto.src;
    });
</script>

</body>
</html>
