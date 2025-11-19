<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Covoiturages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f8f0; }
        .navbar { background-color: #2e7d32; }
        .navbar a { color: #fff !important; }
        footer { background-color: #2e7d32; color: #fff; padding: 20px 0; }
        .btn-eco { background-color: #66bb6a; color: white; }
        .btn-eco:hover { background-color: #4caf50; color: white; }
        .card-eco { border: 2px solid #66bb6a; }
    </style>
</head>
<body>
<!-- Inclure le menu -->
<?php include __DIR__ . '/../partials/header.php'; ?>


<section class="hero mb-5">
    <h2 class="mb-4">Rechercher un covoiturage</h2>
    <?php include __DIR__ . '/../partials/form/formulaire_recherche_covoiturages.php'; ?>
</section>

    <div class="row">
        <!-- Exemple de covoiturage (à remplacer par PHP/PDO) -->
        <div class="col-md-4 mb-4">
            <div class="card card-eco">
                <img src="images/driver.jpg" class="card-img-top" alt="Chauffeur">
                <div class="card-body">
                    <h5 class="card-title">John Doe - Note 4.5/5</h5>
                    <p class="card-text">
                        Places disponibles : 3<br>
                        Prix : 12€<br>
                        Départ : 08:00 - Arrivée : 10:00<br>
                        Voiture électrique : Oui
                    </p>
                    <a href="detail_covoiturage.php" class="btn btn-eco">Détails</a>
                </div>
            </div>
        </div>
        <!-- Ajouter d'autres cartes dynamiquement -->
    </div>
</div>

<footer class="text-center">
    <p>Contact : contact@ecoride.fr | <a href="mentions.php" class="text-white">Mentions légales</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
