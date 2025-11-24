<?php
$photoPathWeb = $user->getPhoto() ?: '/uploads/photos/default-avatar.jpg';
$userPseudo = $user->getPseudo();
$userRole = $user->getRole(); // 2 = admin
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Tableau de bord</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/tableau_de_bord.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin:0;
            background: #f4f7f4;
            color: #2f3e2f;
            transition: background 0.3s, color 0.3s;
        }



        /* Dashboard Content */
        .dashboard-content {
            margin-left: 220px;
            padding: 2rem;
        }

        .user-hero {
            text-align: center;
            margin-bottom: 2rem;
        }

        .user-photo-lg {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #4caf50;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .user-photo-lg:hover { transform: scale(1.05); }

        /* Cartes */
        .card-dashboard {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
        }

        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .card-dashboard h5 i {
            color: #6c757d;
            margin-right: 0.5rem;
        }

        .btn-dashboard {
            margin-top: auto;
            background-color: #4caf50;
            color: #fff;
            border: none;
        }

        .btn-dashboard:hover { background-color: #45a049; }

        /* Formulaire */
        .card-form {
            background-color: #fff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* Thème sombre */
        body.dark-theme { background: #121212; color: #fff; }
        .dark-theme .sidebar { background-color: #1c1c1c; color: #fff; }
        .dark-theme .sidebar a { color: #4caf50; }
        .dark-theme .sidebar a:hover { background-color: rgba(76,175,80,0.1); }
        .dark-theme .card-dashboard, .dark-theme .card-form { background-color: #1e1e1e; color: #fff; }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-content { margin-left: 0; }
            .sidebar { position: relative; width: 100%; flex-direction: row; justify-content: space-around; }
            .sidebar-photo { display: none; }
        }

        /* Bouton thème sobre */
        #themeToggle {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 999;
            padding: 0.25rem 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            color: #2f3e2f;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.3s, color 0.3s;
        }

        #themeToggle:hover {
            background-color: #f0f0f0;
        }

        body.dark-theme #themeToggle {
            background-color: #2a2a2a;
            color: #fff;
            border-color: #555;
        }
    </style>
</head>
<body>

<!-- Bouton thème sobre -->
<button id="themeToggle">Mode nuit</button>

<!-- Sidebar -->
<aside class="sidebar">
    <?php include __DIR__ . '/../menu/menu_dashboard.php'; ?>; ?>
</aside>

<!-- Dashboard -->
<div class="dashboard-content">

    <!-- Hero utilisateur -->
    <div class="user-hero">
        <img src="<?= htmlspecialchars($photoPathWeb) ?>" alt="Photo utilisateur" class="user-photo-lg">
        <h2 class="mt-2">Bonjour, <?= htmlspecialchars($userPseudo) ?> !</h2>
    </div>

    <!-- Cartes d’action -->
    <div class="row g-4 mb-5">
        <?php if ($userRole === 2): ?>
            <div class="col-12 col-sm-6 col-md-4 d-flex">
                <div class="card-dashboard p-3 d-flex flex-column h-100">
                    <h5><i class="bi bi-people"></i> Liste des utilisateurs</h5>
                    <p class="text-muted">Consultez tous les utilisateurs, modifiez ou supprimez-les si nécessaire.</p>
                    <a href="index.php?entity=utilisateurs&action=liste_utilisateurs" class="btn btn-dashboard mt-auto">Accéder</a>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-12 col-sm-6 col-md-4 d-flex">
            <div class="card-dashboard p-3 d-flex flex-column h-100">
                <h5><i class="bi bi-plus-circle"></i> Créer un covoiturage</h5>
                <p class="text-muted">Planifiez un nouveau covoiturage et renseignez les trajets et horaires.</p>
                <a href="index.php?entity=covoiturages&action=creer_covoiturage" class="btn btn-dashboard mt-auto">Créer</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 d-flex">
            <div class="card-dashboard p-3 d-flex flex-column h-100">
                <h5><i class="bi bi-search"></i> Rechercher un covoiturage</h5>
                <p class="text-muted">Trouvez un covoiturage correspondant à vos besoins.</p>
                <a href="index.php?entity=accueil&action=covoiturages" class="btn btn-dashboard mt-auto">Rechercher</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 d-flex">
            <div class="card-dashboard p-3 d-flex flex-column h-100">
                <h5><i class="bi bi-truck"></i> Mes véhicules</h5>
                <p class="text-muted">Consultez vos véhicules et ajoutez-en de nouveaux si nécessaire.</p>
                <a href="index.php?entity=vehicules&action=liste_vehicules" class="btn btn-dashboard mt-auto">Voir mes véhicules</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 d-flex">
            <div class="card-dashboard p-3 d-flex flex-column h-100">
                <h5><i class="bi bi-person"></i> Mon profil</h5>
                <p class="text-muted">Consultez et mettez à jour vos informations personnelles et préférences.</p>
                <a href="index.php?entity=utilisateurs&action=mise_a_jour_profil" class="btn btn-dashboard mt-auto">Accéder</a>
            </div>
        </div>
    </div>


    <!-- Formulaire rôle / véhicules -->
    <div class="card-form mb-5">
        <h5>Modifier mon rôle</h5>
        <form action="index.php?entity=utilisateurs&action=update_type_utilisateur" method="POST">
            <div class="mb-3">
                <label class="form-label">Type utilisateur</label>
                <select name="type_utilisateur" id="type_utilisateur" class="form-select" required>
                    <option value="passager" <?= $user->getTypeUtilisateur() === 'passager' ? 'selected' : '' ?>>Passager</option>
                    <option value="conducteur" <?= $user->getTypeUtilisateur() === 'conducteur' ? 'selected' : '' ?>>Conducteur</option>
                    <option value="PC" <?= $user->getTypeUtilisateur() === 'PC' ? 'selected' : '' ?>>Passager & Conducteur</option>
                </select>
            </div>

            <div id="conducteurFields" style="display:none;">
                <hr>
                <h6>Informations véhicule</h6>
                <div class="mb-3"><input type="text" name="vehicule[immatriculation]" class="form-control" placeholder="Plaque d'immatriculation"></div>
                <div class="mb-3"><input type="date" name="vehicule[date_immat]" class="form-control"></div>
                <div class="mb-3"><input type="text" name="vehicule[marque_modele_couleur]" class="form-control" placeholder="Marque / Modèle / Couleur"></div>
                <div class="mb-3"><input type="number" name="vehicule[places]" class="form-control" min="1" placeholder="Nombre de places disponibles"></div>

                <h6>Préférences</h6>
                <div class="form-check"><input class="form-check-input" type="checkbox" name="preferences_fixes[]" value="fumeur" id="fumeur"><label class="form-check-label" for="fumeur">Accepte fumeur</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" name="preferences_fixes[]" value="animal" id="animal"><label class="form-check-label" for="animal">Accepte animaux</label></div>
                <div class="mb-3 mt-2"><input type="text" name="pref_custom[]" class="form-control" placeholder="Préférences personnalisées"></div>
            </div>

            <button class="btn btn-dashboard mt-3">Enregistrer</button>
        </form>
    </div>

</div>

<script>
    const typeSelect = document.getElementById('type_utilisateur');
    const conducteurFields = document.getElementById('conducteurFields');
    function toggleConducteurFields() {
        conducteurFields.style.display = (typeSelect.value === 'conducteur' || typeSelect.value === 'PC') ? 'block' : 'none';
    }
    typeSelect.addEventListener('change', toggleConducteurFields);
    toggleConducteurFields();

    // Toggle sobre jour/nuit
    const themeToggle = document.getElementById('themeToggle');
    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-theme');
        localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
    });
    if(localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-theme');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
