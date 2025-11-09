<?php include __DIR__ . '/../layout.php'; ?>

<head>
    <link rel="stylesheet" href='assets/css/covoiturage/covoiturage.css'>
</head>

<body>
<div class="container py-5">



    <!-- Formulaire de recherche -->
    <form method="POST" action="" class="search-form mb-5 mx-auto">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Ville de départ</label>
                <input type="text" name="ville_depart" id="ville_depart" class="form-control" placeholder="Ex : Paris" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Ville d'arrivée</label>
                <input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control" placeholder="Ex : Lyon" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date_depart" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Heure (optionnel)</label>
                <input type="time" name="heure_depart" class="form-control">
            </div>
        </div>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-search">
                <i class="bi bi-search"></i> Rechercher
            </button>
            <button onclick="window.history.back()" class="btn btn-secondary btn-retour">
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
                                    <small class="text-muted"><?= htmlspecialchars($covoiturage['nom_marque'] ?? '') ?> <?= htmlspecialchars($covoiturage['modele'] ?? '') ?> (<?= htmlspecialchars($covoiturage['energie'] ?? '') ?>)</small>
                                </div>
                            </div>

                            <div class="trip-info">
                                <p>
                                    <strong>Départ :</strong> <?= htmlspecialchars($covoiturage['ville_depart_name'] ?? '') ?><br>
                                    <strong>Arrivée :</strong> <?= htmlspecialchars($covoiturage['ville_arrivee_name'] ?? '') ?><br>
                                    <strong>Date :</strong> <?= htmlspecialchars($covoiturage['date_depart'] ?? '') ?><br>
                                    <strong>Heure :</strong> <?= htmlspecialchars($covoiturage['heure_depart'] ?? '') ?><br>
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

</body>




