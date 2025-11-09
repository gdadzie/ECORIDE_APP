<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f5f6fa; font-family: "Segoe UI", sans-serif; }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 260px;
            background: linear-gradient(180deg, #37474F, #607D8B);
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
        .main-content { margin-left: 280px; padding: 30px; }
        .card-section { border-radius: 15px; }
    </style>
</head>
<body>

<!-- Barre latérale -->
<div class="sidebar">
    <div class="text-center mb-4">
        <img src="https://cdn-icons-png.flaticon.com/512/3064/3064197.png" alt="Admin" width="80" class="rounded-circle mb-2">
        <h5 class="mb-0">Espace Admin</h5>
        <small>Tableau de bord</small>
    </div>
    <a href="#" class="active">📊 Tableau de bord</a>
    <a href="#">👤 Gestion utilisateurs</a>
    <a href="#">👥 Gestion employés</a>
    <a href="#">⚙️ Paramètres</a>
    <hr class="text-light">
    <a href="#" class="text-danger">🚪 Déconnexion</a>
</div>

<!-- Contenu principal -->
<div class="main-content">
    <div class="text-center mb-4">
        <h2 class="mb-0">Tableau de bord Administrateur</h2>
        <p class="text-muted">Bienvenue, administrateur — gérez la plateforme et surveillez les statistiques</p>
    </div>

    <!-- Section : création comptes -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card card-section shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Créer un utilisateur</h5>
                    <hr>
                    <form>
                        <div class="mb-3">
                            <label for="userName" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="userName">
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="userEmail">
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="userPassword">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Créer l'utilisateur</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-section shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Créer un employé</h5>
                    <hr>
                    <form>
                        <div class="mb-3">
                            <label for="empName" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="empName">
                        </div>
                        <div class="mb-3">
                            <label for="empEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="empEmail">
                        </div>
                        <div class="mb-3">
                            <label for="empPassword" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="empPassword">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Créer l'employé</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Section : statistiques graphiques -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card card-section shadow-sm p-3">
                <h5 class="text-success">Nombre de covoiturages par jour</h5>
                <canvas id="covoitChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-section shadow-sm p-3">
                <h5 class="text-success">Crédits gagnés par jour</h5>
                <canvas id="creditsChart" height="200"></canvas>
                <p class="mt-2"><strong>Total de crédits gagnés :</strong> 1250 crédits</p>
            </div>
        </div>
    </div>

    <!-- Section : gestion des comptes -->
    <div class="card card-section shadow-sm mb-4">
        <div class="card-body">
            <h5 class="text-danger">Gestion des comptes</h5>
            <hr>
            <table class="table table-striped align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Julie Durand</td>
                    <td>julie.durand@example.com</td>
                    <td>Utilisateur</td>
                    <td>Actif</td>
                    <td>
                        <button class="btn btn-warning btn-sm">Suspendre</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Karim B.</td>
                    <td>karim.b@example.com</td>
                    <td>Employé</td>
                    <td>Actif</td>
                    <td>
                        <button class="btn btn-warning btn-sm">Suspendre</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="text-center text-muted py-3 small">
        © 2025 Covoiturage — Espace Administrateur
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Graphiques Chart.js statiques (exemple)
    const ctxCovoit = document.getElementById('covoitChart').getContext('2d');
    const covoitChart = new Chart(ctxCovoit, {
        type: 'line',
        data: {
            labels: ['01/11', '02/11', '03/11', '04/11', '05/11', '06/11', '07/11'],
            datasets: [{
                label: 'Covoiturages',
                data: [5, 8, 6, 10, 7, 4, 9],
                borderColor: '#00796B',
                backgroundColor: 'rgba(0,121,107,0.2)',
                fill: true
            }]
        },
        options: { responsive: true }
    });

    const ctxCredits = document.getElementById('creditsChart').getContext('2d');
    const creditsChart = new Chart(ctxCredits, {
        type: 'bar',
        data: {
            labels: ['01/11', '02/11', '03/11', '04/11', '05/11', '06/11', '07/11'],
            datasets: [{
                label: 'Crédits gagnés',
                data: [120, 200, 150, 180, 220, 170, 190],
                backgroundColor: '#26A69A'
            }]
        },
        options: { responsive: true }
    });
</script>
</body>
</html>
