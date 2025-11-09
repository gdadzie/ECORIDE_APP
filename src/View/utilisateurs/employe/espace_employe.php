<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Employé – Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
            font-family: "Segoe UI", sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 240px;
            background: linear-gradient(180deg, #00796B, #26A69A);
            color: white;
            padding-top: 60px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
        .header-admin {
            background: linear-gradient(135deg, #00796B, #26A69A);
            color: white;
            border-radius: 15px;
        }
        .section-card {
            border-radius: 15px;
        }
        .badge-pending { background-color: #ffb300; }
        .badge-validated { background-color: #4caf50; }
        .badge-refused { background-color: #f44336; }
    </style>
</head>
<body>

<!-- Barre latérale -->
<div class="sidebar">
    <div class="text-center mb-4">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Employé" width="80" class="rounded-circle mb-2">
        <h5 class="mb-0">Espace Employé</h5>
        <small class="text-light">Modération</small>
    </div>
    <a href="#" class="active">📋 Tableau de bord</a>
    <a href="#">🗨️ Avis à valider</a>
    <a href="#">🚗 Covoiturages signalés</a>
    <a href="#">👥 Gestion utilisateurs</a>
    <a href="#">⚙️ Paramètres</a>
    <hr class="text-light">
    <a href="#" class="text-danger">🚪 Déconnexion</a>
</div>

<!-- Contenu principal -->
<div class="main-content">
    <div class="header-admin text-center py-4 mb-4 shadow-sm">
        <h2 class="mb-0">Tableau de bord de modération</h2>
        <p class="mb-0">Bienvenue, <strong>Employé</strong> — surveillez et gérez les activités du site</p>
    </div>

    <!-- Section : avis à valider -->
    <div class="card section-card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title text-success">🗨️ Avis en attente de validation</h4>
            <hr>
            <table class="table align-middle table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Passager</th>
                    <th>Chauffeur</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Date dépôt</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Julie Durand</td>
                    <td>Pierre Martin</td>
                    <td>⭐⭐⭐⭐⭐</td>
                    <td>Super trajet, très ponctuel.</td>
                    <td>05/11/2025</td>
                    <td><span class="badge badge-pending">En attente</span></td>
                    <td>
                        <button class="btn btn-success btn-sm">Valider</button>
                        <button class="btn btn-danger btn-sm">Refuser</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Karim B.</td>
                    <td>Sophie Leroy</td>
                    <td>⭐⭐☆☆☆</td>
                    <td>Beaucoup de retard, trajet stressant.</td>
                    <td>03/11/2025</td>
                    <td><span class="badge badge-pending">En attente</span></td>
                    <td>
                        <button class="btn btn-success btn-sm">Valider</button>
                        <button class="btn btn-danger btn-sm">Refuser</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section : covoiturages problématiques -->
    <div class="card section-card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title text-danger">🚨 Covoiturages signalés ou problématiques</h4>
            <hr>
            <table class="table align-middle table-striped">
                <thead>
                <tr>
                    <th># Covoiturage</th>
                    <th>Chauffeur</th>
                    <th>Passager</th>
                    <th>Date départ</th>
                    <th>Date arrivée</th>
                    <th>Trajet</th>
                    <th>Problème signalé</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>CVT-00124</td>
                    <td>
                        <strong>Pierre Martin</strong><br>
                        <small>pierre.martin@example.com</small>
                    </td>
                    <td>
                        <strong>Julie Durand</strong><br>
                        <small>julie.durand@example.com</small>
                    </td>
                    <td>02/11/2025 - 08h00</td>
                    <td>02/11/2025 - 10h30</td>
                    <td>Marseille → Nice</td>
                    <td>Le conducteur a annulé au dernier moment.</td>
                    <td>
                        <button class="btn btn-outline-info btn-sm">Voir détails</button>
                    </td>
                </tr>
                <tr>
                    <td>CVT-00132</td>
                    <td>
                        <strong>Sophie Leroy</strong><br>
                        <small>sophie.leroy@example.com</small>
                    </td>
                    <td>
                        <strong>Karim B.</strong><br>
                        <small>karim.b@example.com</small>
                    </td>
                    <td>03/11/2025 - 18h00</td>
                    <td>03/11/2025 - 22h00</td>
                    <td>Lyon → Paris</td>
                    <td>Dispute pendant le trajet signalée par les deux parties.</td>
                    <td>
                        <button class="btn btn-outline-info btn-sm">Voir détails</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="text-center text-muted py-3 small">
        © 2025 Covoiturage — Espace Employé
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
