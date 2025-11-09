<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord - Brouillon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .card-icon { font-size: 28px; width: 48px; height: 48px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; background:#e9f7ef; margin-right:12px; }
        .card-action { font-size:13px; }
        .badge-draft { background:#6c757d; color:#fff; }
        .search-wrap { max-width:520px; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <h1 class="h3 mb-0">🧩 Plan de l'application — Brouillon</h1>
            <small class="text-muted">Page de navigation rapide vers les formulaires / listes — place tes pages dans les liens.</small>
        </div>
        <div class="ms-auto search-wrap">
            <input id="searchInput" class="form-control" placeholder="Rechercher une carte (ex: véhicule, covoiturage)...">
        </div>
    </div>

    <div class="row g-3" id="cardsContainer">

        <!-- Connexion -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="index.php?entity=accueil&action=connexion" class="text-decoration-none text-body">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="card-icon">🔐</div>
                        <div>
                            <h5 class="card-title mb-1">Formulaire de connexion</h5>
                            <p class="card-text text-muted mb-2">Page : <code>pages/connexion.php</code></p>
                            <div class="card-action"><span class="badge badge-draft">Form</span> <small class="text-muted">POST / session</small></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Création compte -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="index.php?entity=accueil&action=creer_compte" class="text-decoration-none text-body">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="card-icon">📝</div>
                        <div>
                            <h5 class="card-title mb-1">Formulaire de création de compte</h5>
                            <p class="card-text text-muted mb-2">Page : <code>pages/inscription.php</code></p>
                            <div class="card-action"><span class="badge badge-draft">Form</span> <small class="text-muted">Validation / E-mail</small></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Ajouter véhicule -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="index.php?entity=vehicules&action=ajouter_vehicule" class="text-decoration-none text-body">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="card-icon">🚗</div>
                        <div>
                            <h5 class="card-title mb-1">Formulaire d'ajout de véhicule</h5>
                            <p class="card-text text-muted mb-2">Page : <code>pages/ajouter_vehicule.php</code></p>
                            <div class="card-action"><span class="badge badge-draft">Form</span> <small class="text-muted">Marque / Modèle / Immat.</small></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Création covoiturage -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="index.php?entity=covoiturages&action=creer_covoiturage" class="text-decoration-none text-body">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="card-icon">📍</div>
                        <div>
                            <h5 class="card-title mb-1">Formulaire de création de covoiturage</h5>
                            <p class="card-text text-muted mb-2">Page : <code>pages/creer_covoiturage.php</code></p>
                            <div class="card-action"><span class="badge badge-draft">Form</span> <small class="text-muted">Itinéraire / Date</small></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Liste covoiturages -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="index.php?entity=covoiturages&action=liste_covoiturages" class="text-decoration-none text-body">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="card-icon">🗂️</div>
                        <div>
                            <h5 class="card-title mb-1">Liste des covoiturages</h5>
                            <p class="card-text text-muted mb-2">Page : <code>pages/liste_covoiturages.php</code></p>
                            <div class="card-action"><span class="badge badge-draft">List</span> <small class="text-muted">Recherche / Filtre</small></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Liste véhicules -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="index.php?entity=vehicules&action=liste_vehicules" class="text-decoration-none text-body">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="card-icon">🚘</div>
                        <div>
                            <h5 class="card-title mb-1">Liste des véhicules</h5>
                            <p class="card-text text-muted mb-2">Page : <code>pages/liste_vehicules.php</code></p>
                            <div class="card-action"><span class="badge badge-draft">List</span> <small class="text-muted">CRUD véhicules</small></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Liste avis -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="index.php?entity=covoiturages&action=liste_avis.php" class="text-decoration-none text-body">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="card-icon">⭐</div>
                        <div>
                            <h5 class="card-title mb-1">Liste des avis</h5>
                            <p class="card-text text-muted mb-2">Page : <code>pages/liste_avis.php</code></p>
                            <div class="card-action"><span class="badge badge-draft">List</span> <small class="text-muted">Modération</small></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Liste chauffeurs -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="index.php?entity=utilisateurs&action=profil_conducteur" class="text-decoration-none text-body">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="card-icon">👨‍✈️</div>
                        <div>
                            <h5 class="card-title mb-1">Liste des chauffeurs</h5>
                            <p class="card-text text-muted mb-2">Page : <code>pages/liste_chauffeurs.php</code></p>
                            <div class="card-action"><span class="badge badge-draft">List</span> <small class="text-muted">Profil / Contact</small></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Liste passagers -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="index.php?entity=utilisateurs&action=profil_passager" class="text-decoration-none text-body">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="card-icon">🧑‍🤝‍🧑</div>
                        <div>
                            <h5 class="card-title mb-1">Liste des passagers</h5>
                            <p class="card-text text-muted mb-2">Page : <code>pages/liste_passagers.php</code></p>
                            <div class="card-action"><span class="badge badge-draft">List</span> <small class="text-muted">Réservations</small></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <!-- ESPACE EMPLOYE -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="index.php?entity=utilisateurs&action=espace_employe" class="text-decoration-none text-body">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="card-icon">🧑‍🤝‍🧑</div>
                        <div>
                            <h5 class="card-title mb-1">Espace employe</h5>
                            <p class="card-text text-muted mb-2">Page : <code>employe/espace_employe.php</code></p>
                            <div class="card-action"><span class="badge badge-draft">List</span> <small class="text-muted">Réservations</small></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- ESPACE ADMIN -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="index.php?entity=utilisateurs&action=espace_admin" class="text-decoration-none text-body">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="card-icon">🧑‍🤝‍🧑</div>
                        <div>
                            <h5 class="card-title mb-1">Espace admin</h5>
                            <p class="card-text text-muted mb-2">Page : <code>admin/espace_admin.php</code></p>
                            <div class="card-action"><span class="badge badge-draft">List</span> <small class="text-muted">Réservations</small></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>

    <div class="mt-5 text-muted small">Conseil : crée les fichiers vers lesquels pointent les liens dans le dossier <code>pages/</code>. Tu peux aussi remplacer les href par des routes existantes (ex: <code>index.php?entity=vehicules&amp;action=liste</code>).</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Recherche simple des cartes
    document.getElementById('searchInput').addEventListener('input', function(e){
        const q = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('#cardsContainer > div');
        cards.forEach(col => {
            const text = col.innerText.toLowerCase();
            col.style.display = text.includes(q) ? '' : 'none';
        });
    });
</script>
</body>
</html>