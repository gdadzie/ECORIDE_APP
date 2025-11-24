<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Covoiturages , économique, écologique, pratique et agréable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/covoiturage/covoiturage.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/theme/theme.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>
<?php include __DIR__ . '/../partials/header.php'; ?>

<section class="hero mb-5 text-center py-5 bg-light border-bottom">
    <h1 class="fw-bold display-5">Trouvez votre prochain trajet en quelques secondes</h1>
    <p class="lead mt-3">Des trajets économiques, écologiques et toujours plus conviviaux. Rejoignez le mouvement EcoRide.</p>
    <?php include __DIR__ . '/../partials/form/formulaire_recherche_covoiturages.php'; ?>
</section>

<section class="container my-5">
    <h2 class="mb-4 text-center">Suggestions de trajets populaires</h2>
    <div class="row g-4">
        <!-- Exemple de carte covoiturage -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Paris ➜ Lyon</h5>
                    <p class="card-text text-muted">Un trajet rapide et abordable avec un conducteur vérifié.</p>
                    <p class="fw-bold mb-1">Départ : 08h30</p>
                    <p class="fw-bold">Prix : 28€</p>
                    <a href="#" class="btn btn-success w-100">Voir le trajet</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Marseille ➜ Montpellier</h5>
                    <p class="card-text text-muted">Un trajet côtier agréable et économique.</p>
                    <p class="fw-bold mb-1">Départ : 14h15</p>
                    <p class="fw-bold">Prix : 15€</p>
                    <a href="#" class="btn btn-success w-100">Voir le trajet</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Toulouse ➜ Bordeaux</h5>
                    <p class="card-text text-muted">Un trajet populaire entre deux grandes villes du Sud-Ouest.</p>
                    <p class="fw-bold mb-1">Départ : 17h45</p>
                    <p class="fw-bold">Prix : 22€</p>
                    <a href="#" class="btn btn-success w-100">Voir le trajet</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-success text-white py-5 text-center">
    <h2 class="fw-bold mb-3">Voyagez autrement</h2>
    <p class="lead mx-auto" style="max-width: 700px;">EcoRide simplifie vos déplacements en vous connectant à des conducteurs et passagers partageant les mêmes trajets. Ensemble, réduisons l'empreinte carbone tout en économisant.</p>
</section>

</body>
</html>
