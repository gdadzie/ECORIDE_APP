<?php
// On suppose que $message et $covoiturages sont définis par le contrôleur
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rechercher un covoiturage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f8f6;
            font-family: 'Inter', sans-serif;
        }
        .search-header {
            text-align: center;
            margin-top: 40px;
            margin-bottom: 30px;
        }
        .search-header h2 {
            color: #198754;
            font-weight: 700;
        }
        .search-form {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            padding: 30px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .btn-search {
            background-color: #198754;
            color: #fff;
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.2s ease-in-out;
        }
        .btn-search:hover {
            background-color: #157347;
            transform: translateY(-2px);
        }
        .message {
            text-align: center;
            color: #d9534f;
            font-weight: 600;
            margin-top: 20px;
        }
        .covoiturage-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: transform 0.2s ease-in-out;
            background: #fff;
        }
        .covoiturage-card:hover {
            transform: translateY(-5px);
        }
        .covoiturage-card .card-body {
            padding: 20px;
        }
        .driver-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        .driver-info img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #198754;
        }
        .driver-info strong {
            font-size: 18px;
            color: #198754;
        }
        .trip-info {
            font-size: 15px;
            color: #555;
            margin-bottom: 10px;
        }
        .price {
            font-size: 18px;
            font-weight: 700;
            color: #198754;
        }
        .btn-details {
            border: 1px solid #198754;
            color: #198754;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
        }
        .btn-details:hover {
            background-color: #198754;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="search-header">
        <h2>Rechercher un covoiturage</h2>
        <p class="text-muted">Trouvez un trajet correspondant à vos besoins en quelques clics.</p>
    </div>

    <!-- Formulaire de recherche -->
    <form method="POST" action="" class="search-form mb-5">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Ville de départ</label>
                <input type="text" name="ville_depart" id="ville_depart" class="form-control" placeholder="Ex : Paris" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ville d'arrivée</label>
                <input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control" placeholder="Ex : Lyon" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="date_depart" class="form-control" required>
            </div>
        </div>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-search">
                <i class="bi bi-search"></i> Rechercher
            </button>
        </div>
    </form>
    <!-- Message d'erreur -->
    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Résultats -->
    <?php if (!empty($covoiturages)): ?>
        <div class="row g-4">
            <?php foreach ($covoiturages as $covoiturage): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card covoiturage-card">
                        <div class="card-body">
                            <div class="driver-info">
                                <img src="/photos/<?= htmlspecialchars($covoiturage['photo_conducteur'] ?? 'default.jpg') ?>" alt="Conducteur">
                                <div>
                                    <strong><?= htmlspecialchars($covoiturage['pseudo'] ?? 'Conducteur inconnu') ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($covoiturage['modele'] ?? '') ?> (<?= htmlspecialchars($covoiturage['energie'] ?? '') ?>)</small>
                                </div>
                            </div>

                            <div class="trip-info">
                                <p>
                                    <strong>Départ :</strong> <?= htmlspecialchars($covoiturage['ville_depart'] ?? '') ?><br>
                                    <strong>Arrivée :</strong> <?= htmlspecialchars($covoiturage['ville_arrivee'] ?? '') ?><br>
                                    <strong>Date :</strong> <?= htmlspecialchars($covoiturage['date_depart'] ?? '') ?><br>
                                    <strong>Heure :</strong> <?= htmlspecialchars($covoiturage['heure_depart'] ?? '') ?> → <?= htmlspecialchars($covoiturage['heure_arrivee'] ?? '') ?><br>
                                    <strong>Places disponibles :</strong> <?= htmlspecialchars($covoiturage['nb_places'] ?? '0') ?>
                                </p>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price"><?= number_format((float)($covoiturage['prix'] ?? 0), 2) ?> €</span>
                                <button class="btn btn-details" onclick="location.href='details_covoiturage.php?id=<?= urlencode($covoiturage['id_covoiturage']) ?>'">
                                    Détails
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(function() {
        function setupAutocomplete(inputId) {
            $("#" + inputId).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "index.php?entity=villes&action=autocomplete",
                        dataType: "json",
                        data: { term: request.term },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 2
            });
        }

        setupAutocomplete("ville_depart");
        setupAutocomplete("ville_arrivee");
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Optionnel : icônes Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
