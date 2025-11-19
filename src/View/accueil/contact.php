<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Accueil</title>
    <link rel="stylesheet" href="assets/css/header/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e6f2e6; /* vert pâle */
            color: #2e7d32;
        }
        .btn-eco {
            background-color: #66bb6a;
            color: white;
        }
        .btn-eco:hover {
            background-color: #4caf50;
            color: white;
        }
        footer {
            background-color: #2e7d32;
            color: white;
            padding: 20px 0;
        }
        .hero {
            padding: 60px 20px;
            text-align: center;
            background-color: #d9f0d9;
            border-radius: 10px;
        }
        .search-bar input, .search-bar button {
            border-radius: 0;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>


<body>
<!-- Inclure le menu -->
<?php include __DIR__ . '/../partials/header.php'; ?>

<header class="hero">
    <h1 class="fw-light">Contactez <strong>EcoRide</strong></h1>
    <p class="lead">Une question, une suggestion ? Nous sommes à votre écoute !</p>
</header>
<main class="container my-5">
    <div class="row g-4 align-items-center">
        <div class="col-md-6">
            <h3 class="text-success fw-light mb-3">Envoyez-nous un message</h3>
            <form>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Votre nom" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" placeholder="Votre email" required>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" rows="5" placeholder="Votre message..." required></textarea>
                </div>
                <button type="submit" class="btn btn-ecoride px-4 py-2 text-white">Envoyer</button>
            </form>
        </div>

        <div class="col-md-6">
            <h3 class="text-success fw-light mb-3">Nos coordonnées</h3>
            <p><strong>Email :</strong> contact@ecoride.com</p>
            <p><strong>Téléphone :</strong> +33 1 23 45 67 89</p>
            <p><strong>Adresse :</strong> 42 rue de la Planète Verte, 75000 Paris</p>
            <div class="ratio ratio-16x9 rounded-4 shadow-sm mt-3">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18..."
                        style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</main>

</body>

<?php include __DIR__ . '/../partials/footer.php'; ?>



