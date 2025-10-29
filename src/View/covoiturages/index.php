<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Covoiturage - EcoRide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="hero-covoiturage position-relative">
    <div class="overlay"></div>
    <div class="position-relative container">
        <h1 class="fw-bold display-4">Trouvez votre trajet <span class="text-success">EcoRide</span></h1>
        <p class="lead">Partagez vos trajets, économisez et préservez la planète 🌍</p>
    </div>
</header>

<section class="container search-box mt-5">
    <h4 class="text-success fw-light mb-4">Rechercher un covoiturage</h4>
    <form class="row g-3">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Départ">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Destination">
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control">
        </div>
        <div class="col-md-1 d-grid">
            <button class="btn btn-ecoride text-white">OK</button>
        </div>
    </form>
</section>

<section class="container my-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="text-success">Économique</h5>
                    <p>Réduisez vos frais de transport en partageant vos trajets avec d’autres utilisateurs.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="text-success">Écologique</h5>
                    <p>Contribuez à réduire les émissions de CO₂ et favorisez une mobilité durable.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="text-success">Convivial</h5>
                    <p>Rencontrez d’autres voyageurs partageant vos valeurs et vos destinations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer-ecoride">
    © 2025 EcoRide – Ensemble pour une mobilité durable 🚗💨
</footer>

</body>
</html>
