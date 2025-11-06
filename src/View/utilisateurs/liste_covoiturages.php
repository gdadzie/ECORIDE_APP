<?php
// $covoiturages doit être un tableau provenant du repository
// Exemple : $covoiturages = $covoituragesRepo->filterByEcologique(1);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des covoiturages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #a8e063, #56ab2f);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }
        .covoit-card {
            border-radius: 1rem;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0 20px rgba(56, 142, 60, 0.3);
            transition: transform 0.2s;
        }
        .covoit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 30px rgba(56, 142, 60, 0.5);
        }
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #2e7d32;
        }
        .covoit-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .covoit-header h5 {
            margin: 0;
            color: #2e7d32;
        }
        .covoit-body {
            font-size: 0.95rem;
            color: #1a3c1a;
        }
        .covoit-body i {
            color: #43a047;
        }
        .btn-add {
            background-color: #43a047;
            color: #fff;
            border-color: #43a047;
            box-shadow: 0 0 15px rgba(67,160,71,0.5);
            transition: 0.3s;
        }
        .btn-add:hover {
            background-color: #66bb6a;
            box-shadow: 0 0 25px rgba(102,187,106,0.6);
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h1 class="text-center mb-5 text-white">🚗 Covoi­turages écologiques disponibles</h1>

    <?php if (empty($covoiturages)): ?>
        <div class="alert alert-light text-center shadow-sm">
            Aucun covoiturage disponible pour le moment.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($covoiturages as $covoit): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="covoit-card">
                        <div class="covoit-header">
                            <h5><?= htmlspecialchars($covoit['ville_depart']) ?> ➜ <?= htmlspecialchars($covoit['ville_arrivee']) ?></h5>
                            <img class="avatar" src="<?= htmlspecialchars($covoit['avatar'] ?? 'https://via.placeholder.com/50') ?>" alt="Avatar conducteur">
                        </div>
                        <div class="covoit-body">
                            <p><i class="bi bi-calendar2-event"></i> <?= htmlspecialchars($covoit['date_depart']) ?> à <?= htmlspecialchars($covoit['heure_depart']) ?></p>
                            <p><i class="bi bi-people"></i> Places disponibles : <?= htmlspecialchars($covoit['nb_places']) ?></p>
                            <p><i class="bi bi-cash-stack"></i> Prix : <?= htmlspecialchars($covoit['prix']) ?> €</p>
                            <p><i class="bi bi-clock"></i> Durée : <?= htmlspecialchars($covoit['duree_minutes'] ?? 'N/A') ?> min</p>
                            <p><i class="bi bi-person-circle"></i> Conducteur : <?= htmlspecialchars($covoit['nom_conducteur'] ?? 'Anonyme') ?></p>
                            <?php if (!empty($covoit['ecologique'])): ?>
                                <span class="badge bg-success">🌱 Écologique</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="index.php?entity=covoiturages&action=creer_covoiturage" class="btn btn-add">
            ➕ Ajouter un covoiturage
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
