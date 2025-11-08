<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convoite' - EcoRide</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .theme-green { color: #00C853; }
        .bg-light-gray { background-color: #f1f3f5; }
        .card-small { border:1px solid #E0E0E0; border-radius: 12px; overflow: hidden; }
        .card-header-small { background-color: #ffffff; padding: 0.5rem 1rem; font-weight: 500; display:flex; justify-content:space-between; align-items:center; font-size:0.9rem; }
        .card-body-small { padding: 0.8rem 1rem; font-size: 0.85rem; }
        .card-footer-small { background-color: #f1f3f5; display:flex; justify-content:space-between; align-items:center; padding:0.5rem 1rem; font-size:0.8rem; }
        .avatar-mini { width: 28px; height: 28px; border-radius:50%; object-fit:cover; margin-right:0.5rem; }
        .icon-person { display:flex; align-items:center; gap:0.2rem; }
        .icon-green { color:#00C853; }
        .dot-depart { font-weight:bold; color:#212121; margin-right:0.3rem; }
        .fa-location-dot { color:#00C853; margin-left:0.3rem; }
        .amount { font-weight:bold; }
        .hr-small { border:0.5px solid #e0e0e0; margin:0.2rem 0; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<header class="py-3 bg-theme-green bg-success-subtle text-center mb-4">
    <h1 class="fw-bold">Convoit'</h1>
</header>

<main class="container mb-5">
    <div class="row g-3">

        <!-- Carte exemple 1 -->
        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="card card-small shadow-sm">
                <div class="card-header-small">
                    <div>
                        Aujourd'hui 08:30
                    </div>
                    <div>
                        <i class="fa-solid fa-credit-card"></i> <span class="amount">12€</span>
                    </div>
                </div>
                <hr class="hr-small">
                <div class="card-body-small">
                    <div>
                        <span class="dot-depart">&bull;</span> Paris
                        <i class="fa-solid fa-location-dot fa-sm fa-fw"></i> Versailles
                    </div>
                </div>
                <div class="card-footer-small">
                    <div class="d-flex align-items-center">
                        <img src="/assets/images/user1.jpg" alt="Conducteur" class="avatar-mini">
                        <span>AliceD</span>
                    </div>
                    <div class="icon-person">
                        <i class="fa-solid fa-user"></i> 3
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte exemple 2 -->
        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="card card-small shadow-sm">
                <div class="card-header-small">
                    <div>
                        2025-11-06 09:00
                    </div>
                    <div>
                        <i class="fa-solid fa-money-bill"></i> <span class="amount">8€</span>
                    </div>
                </div>
                <hr class="hr-small">
                <div class="card-body-small">
                    <div>
                        <span class="dot-depart">&bull;</span> Nanterre
                        <i class="fa-solid fa-location-dot fa-sm fa-fw"></i> La Défense
                    </div>
                </div>
                <div class="card-footer-small">
                    <div class="d-flex align-items-center">
                        <img src="/assets/images/user2.jpg" alt="Conducteur" class="avatar-mini">
                        <span>BobM</span>
                    </div>
                    <div class="icon-person">
                        <i class="fa-solid fa-user"></i> 2
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte exemple 3 -->
        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="card card-small shadow-sm">
                <div class="card-header-small">
                    <div>
                        2025-11-05 07:45
                    </div>
                    <div>
                        <i class="fa-solid fa-credit-card"></i> <span class="amount">15€</span>
                    </div>
                </div>
                <hr class="hr-small">
                <div class="card-body-small">
                    <div>
                        <span class="dot-depart">&bull;</span> Saint-Denis
                        <i class="fa-solid fa-location-dot fa-sm fa-fw"></i> Paris
                    </div>
                </div>
                <div class="card-footer-small">
                    <div class="d-flex align-items-center">
                        <img src="/assets/images/user3.jpg" alt="Conducteur" class="avatar-mini">
                        <span>CharlieD</span>
                    </div>
                    <div class="icon-person">
                        <i class="fa-solid fa-user"></i> 1
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte exemple 4 -->
        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="card card-small shadow-sm">
                <div class="card-header-small">
                    <div>
                        Aujourd'hui 12:15
                    </div>
                    <div>
                        <i class="fa-solid fa-credit-card"></i> <span class="amount">10€</span>
                    </div>
                </div>
                <hr class="hr-small">
                <div class="card-body-small">
                    <div>
                        <span class="dot-depart">&bull;</span> Créteil
                        <i class="fa-solid fa-location-dot fa-sm fa-fw"></i> Paris
                    </div>
                </div>
                <div class="card-footer-small">
                    <div class="d-flex align-items-center">
                        <img src="/assets/images/user4.jpg" alt="Conducteur" class="avatar-mini">
                        <span>DavidP</span>
                    </div>
                    <div class="icon-person">
                        <i class="fa-solid fa-user"></i> 2
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte exemple 5 -->
        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="card card-small shadow-sm">
                <div class="card-header-small">
                    <div>
                        2025-11-07 14:00
                    </div>
                    <div>
                        <i class="fa-solid fa-money-bill"></i> <span class="amount">18€</span>
                    </div>
                </div>
                <hr class="hr-small">
                <div class="card-body-small">
                    <div>
                        <span class="dot-depart">&bull;</span> Boulogne
                        <i class="fa-solid fa-location-dot fa-sm fa-fw"></i> Issy-les-Moulineaux
                    </div>
                </div>
                <div class="card-footer-small">
                    <div class="d-flex align-items-center">
                        <img src="/assets/images/user5.jpg" alt="Conducteur" class="avatar-mini">
                        <span>EveL</span>
                    </div>
                    <div class="icon-person">
                        <i class="fa-solid fa-user"></i> 4
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte exemple 6 -->
        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="card card-small shadow-sm">
                <div class="card-header-small">
                    <div>
                        2025-11-08 10:00
                    </div>
                    <div>
                        <i class="fa-solid fa-credit-card"></i> <span class="amount">9€</span>
                    </div>
                </div>
                <hr class="hr-small">
                <div class="card-body-small">
                    <div>
                        <span class="dot-depart">&bull;</span> Levallois
                        <i class="fa-solid fa-location-dot fa-sm fa-fw"></i> Paris
                    </div>
                </div>
                <div class="card-footer-small">
                    <div class="d-flex align-items-center">
                        <img src="/assets/images/user6.jpg" alt="Conducteur" class="avatar-mini">
                        <span>FrankM</span>
                    </div>
                    <div class="icon-person">
                        <i class="fa-solid fa-user"></i> 3
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<footer class="text-center py-3 mt-auto bg-dark text-white">
    <p>Contact : <a href="mailto:contact@ecoride.fr" class="text-white">contact@ecoride.fr</a> | <a href="#" class="text-white">Mentions légales</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
