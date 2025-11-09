<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Passager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .profile-header { background: linear-gradient(135deg, #4CAF50, #81C784); color: white; border-radius: 0 0 20px 20px; }
        .profile-avatar { width: 120px; height: 120px; border-radius: 50%; border: 4px solid white; object-fit: cover; }
        .section-card { border-radius: 15px; }
    </style>
</head>
<body>
<div class="profile-header text-center py-5 mb-4 shadow-sm">
    <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Avatar" class="profile-avatar mb-3">
    <h2 class="mb-0">Jean Dupont</h2>
    <p class="mb-0">Passager régulier</p>
</div>

<div class="container mb-5">
    <div class="row g-4">

        <!-- Informations personnelles -->
        <div class="col-md-6">
            <div class="card section-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success">Informations personnelles</h5>
                    <hr>
                    <p><strong>Nom :</strong> Jean Dupont</p>
                    <p><strong>Email :</strong> jean.dupont@example.com</p>
                    <p><strong>Téléphone :</strong> 06 12 34 56 78</p>
                    <p><strong>Ville :</strong> Lyon</p>
                    <a href="#" class="btn btn-outline-success btn-sm">Modifier mes informations</a>
                </div>
            </div>
        </div>

        <!-- Historique des réservations -->
        <div class="col-md-6">
            <div class="card section-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success">Mes réservations</h5>
                    <hr>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Lyon → Paris</strong><br>
                            <small>15 octobre 2025 - 08h00</small>
                        </li>
                        <li class="list-group-item">
                            <strong>Marseille → Lyon</strong><br>
                            <small>22 septembre 2025 - 17h30</small>
                        </li>
                        <li class="list-group-item text-muted text-center">Voir tout l’historique...</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Avis laissés -->
        <div class="col-md-6">
            <div class="card section-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success">Mes avis</h5>
                    <hr>
                    <div class="mb-2">
                        <p class="mb-1"><strong>Conducteur :</strong> Pierre Martin</p>
                        <p class="mb-1 text-warning">⭐⭐⭐⭐☆</p>
                        <small class="text-muted">Très ponctuel et sympathique.</small>
                    </div>
                    <div class="mb-2">
                        <p class="mb-1"><strong>Conducteur :</strong> Sophie Leroy</p>
                        <p class="mb-1 text-warning">⭐⭐⭐☆☆</p>
                        <small class="text-muted">Conduite agréable mais retard de 10 min.</small>
                    </div>
                    <a href="#" class="btn btn-outline-success btn-sm">Voir tous mes avis</a>
                </div>
            </div>
        </div>

        <!-- Paramètres du compte -->
        <div class="col-md-6">
            <div class="card section-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success">Paramètres du compte</h5>
                    <hr>
                    <p><strong>Mot de passe :</strong> ********</p>
                    <a href="#" class="btn btn-outline-success btn-sm">Changer le mot de passe</a>
                    <hr>
                    <a href="#" class="btn btn-outline-danger btn-sm">Supprimer mon compte</a>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="text-center text-muted py-3 small">
    © 2025 Covoiturage — Profil Passager
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
