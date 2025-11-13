<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link href="/assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f8f6;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 15px;
        }

        .dashboard-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 50px;
        }

        .dashboard-header h2 {
            color: #198754;
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .user-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #198754;
            margin-bottom: 15px;
        }

        .btn-logout {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .btn-logout:hover {
            color: #198754;
        }

        .card-dashboard {
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: transform 0.2s ease-in-out;
            background-color: #fff;
            height: 100%;
        }

        .card-dashboard:hover {
            transform: translateY(-5px);
        }

        .card-dashboard .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 25px 15px;
            text-align: center;
        }

        .card-dashboard h5 {
            font-weight: 600;
            color: #198754;
            margin-top: 15px;
            font-size: 1.1rem;
        }

        .card-dashboard p {
            font-size: 0.95rem;
            color: #555;
            margin-top: 10px;
        }

        @media (max-width: 767px) {
            .dashboard-header h2 {
                font-size: 1.5rem;
            }
            .card-dashboard h5 {
                font-size: 1rem;
            }
            .card-dashboard p {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container dashboard-container">
    <!-- En-tête avec photo -->
    <div class="dashboard-header">


        <img src="<?= htmlspecialchars($photoPathWeb) ?>"
             alt="Photo de profil"
             class="user-photo"
             id="currentPhoto">
        <h2>Bonjour, <?= htmlspecialchars($userPseudo) ?> !</h2>
        <button type="button" class="btn btn-outline-success btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalPhoto">
            Changer ma photo
        </button>
        <a href="index.php?entity=accueil&action=logout" class="btn-logout mt-2 d-block">Se déconnecter</a>
    </div>


    <!-- Cartes dashboard -->
    <div class="row g-4">
        <?php if ((int)$userRole === 2): ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card card-dashboard">
                    <div class="card-body">
                        <h5>Liste des utilisateurs</h5>
                        <p>Consultez tous les utilisateurs, modifiez ou supprimez-les si nécessaire.</p>
                        <a href="index.php?entity=utilisateurs&action=liste_utilisateurs" class="btn btn-success">Accéder</a>
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

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h5>Liste des covoiturages</h5>
                    <p>Consultez la liste complète des covoiturages disponibles et réservez facilement.</p>
                    <a href="index.php?entity=covoiturages&action=resultats_recherche" class="btn btn-success">Voir la liste</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h5>Mon profil</h5>
                    <p>Consultez et mettez à jour vos informations personnelles et préférences.</p>
                    <a href="index.php?entity=utilisateurs&action=mon_profil" class="btn btn-success">Accéder</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal changement photo -->
<div class="modal fade" id="modalPhoto" tabindex="-1" aria-labelledby="modalPhotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formPhoto"
                  action="index.php?entity=utilisateurs&action=mise_a_jour_profil"
                  method="POST"
                  enctype="multipart/form-data">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalPhotoLabel">Changer ma photo de profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <div class="modal-body text-center">
                    <div class="mb-3">
                        <!-- Affiche la photo actuelle -->
                        <img id="previewPhoto"
                             src="<?= htmlspecialchars($photoPathWeb) ?>"
                             class="user-photo mb-3"
                             style="max-width:120px; max-height:120px;">

                        <input class="form-control"
                               type="file"
                               id="photo"
                               name="photo"
                               accept="image/*">
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

<script>
    const inputPhoto = document.getElementById('photo');
    const previewPhoto = document.getElementById('previewPhoto');
    const currentPhoto = document.getElementById('currentPhoto');

    // Affichage en preview dès la sélection du fichier
    inputPhoto.addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = evt => previewPhoto.src = evt.target.result;
        reader.readAsDataURL(file);
    });

    // Met à jour la photo du dashboard après validation du formulaire
    const form = document.getElementById('formPhoto');
    form.addEventListener('submit', () => {
        currentPhoto.src = previewPhoto.src;
    });
</script>

</body>
</html>
