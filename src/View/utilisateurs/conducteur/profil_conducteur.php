<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Conducteur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .profile-header { background: linear-gradient(135deg, #2196F3, #64B5F6); color: white; border-radius: 0 0 20px 20px; }
        .profile-avatar { width: 120px; height: 120px; border-radius: 50%; border: 4px solid white; object-fit: cover; }
        .section-card { border-radius: 15px; }
    </style>
</head>
<body>
<div class="profile-header text-center py-5 mb-4 shadow-sm">
    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar conducteur" class="profile-avatar mb-3">
    <h2 class="mb-0">Pierre Martin</h2>
    <p class="mb-0">Conducteur confirmé</p>
</div>

<div class="container mb-5">
    <div class="row g-4">

        <!-- Informations personnelles -->
        <div class="col-md-6">
            <div class="card section-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Informations personnelles</h5>
                    <hr>
                    <p><strong>Nom :</strong> Martin</p>
                    <p><strong>Prénom :</strong> Pierre</p>
                    <p><strong>Email :</strong> pierre.martin@example.com</p>
                    <p><strong>Téléphone :</strong> 06 23 45 67 89</p>
                    <p><strong>Ville :</strong> Marseille</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Modifier mes informations</a>
                </div>
            </div>
        </div>

        <!-- Mes véhicules -->
        <div class="col-md-6">
            <div class="card section-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Mes véhicules</h5>
                    <hr>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Peugeot 208</strong> — Essence<br>
                            <small>Immatriculation : AB-123-CD</small>
                        </li>
                        <li class="list-group-item">
                            <strong>Renault Clio</strong> — Diesel<br>
                            <small>Immatriculation : XY-456-ZT</small>
                        </li>
                        <li class="list-group-item text-center text-muted">Voir tous mes véhicules...</li>
                    </ul>
                    <a href="#" class="btn btn-outline-primary btn-sm mt-2">Ajouter un véhicule</a>
                </div>
            </div>
        </div>

        <!-- Covoiturages créés -->
        <div class="col-md-6">
            <div class="card section-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Mes covoiturages</h5>
                    <hr>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Marseille → Nice</strong><br>
                            <small>Départ : 20 novembre 2025 à 08h30</small>
                        </li>
                        <li class="list-group-item">
                            <strong>Marseille → Lyon</strong><br>
                            <small>Départ : 25 novembre 2025 à 15h00</small>
                        </li>
                        <li class="list-group-item text-center text-muted">Voir tous mes trajets...</li>
                    </ul>
                    <a href="#" class="btn btn-outline-primary btn-sm mt-2">Créer un nouveau covoiturage</a>
                </div>
            </div>
        </div>

        <!-- Avis reçus -->
        <div class="col-md-6">
            <div class="card section-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Avis reçus</h5>
                    <hr>
                    <div class="mb-2">
                        <p class="mb-1"><strong>Passager :</strong> Julie Durand</p>
                        <p class="mb-1 text-warning">⭐⭐⭐⭐⭐</p>
                        <small class="text-muted">Excellent trajet, très ponctuel !</small>
                    </div>
                    <div class="mb-2">
                        <p class="mb-1"><strong>Passager :</strong> Karim B.</p>
                        <p class="mb-1 text-warning">⭐⭐⭐☆☆</p>
                        <small class="text-muted">Bonne ambiance, un peu de retard au départ.</small>
                    </div>
                    <a href="#" class="btn btn-outline-primary btn-sm">Voir tous mes avis</a>
                </div>
            </div>
        </div>

        <!-- Paramètres du compte -->
        <div class="col-md-6">
            <div class="card section-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Paramètres du compte</h5>
                    <hr>
                    <p><strong>Mot de passe :</strong> ********</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Changer le mot de passe</a>
                    <hr>
                    <a href="#" class="btn btn-outline-danger btn-sm">Supprimer mon compte</a>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="text-center text-muted py-3 small">
    © 2025 Covoiturage — Profil Conducteur
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
