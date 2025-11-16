<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../menu/menu.php';

// ✅ Empêche la double ouverture de session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Exemple de pseudo si connecté
$pseudo = $_SESSION['pseudo'] ?? null;
?>

<head>
    <title>Covoiturages - Ecoride</title>
    <link rel="stylesheet" href="assets/css/covoiturage/covoiturage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>



<div class="container text-center mt-5">
    <?php if ($pseudo): ?>
        <p class="welcome">👋 Bonjour, <strong><?= htmlspecialchars($pseudo) ?></strong></p>
    <?php endif; ?>

    <h1>Où allez-vous ?</h1>
    <p class="text-muted mb-4">Trouvez facilement votre covoiturage, rapide et écologique 🌿</p>

    <section class="search-section">
        <form id="form-recherche" method="GET" action="index.php">
            <!-- Champs cachés pour le routeur -->
            <input type="hidden" name="entity" value="covoiturages">
            <input type="hidden" name="action" value="resultats_recherche">

            <div class="row g-3">
                <div class="col-md-6">
                    <label>Ville de départ</label>
                    <input type="text" name="ville_depart" id="ville_depart" class="form-control" placeholder="Ex : Paris" required>
                </div>
                <div class="col-md-6">
                    <label>Ville d’arrivée</label>
                    <input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control" placeholder="Ex : Lyon" required>
                </div>
                <div class="col-md-6">
                    <label>Date</label>
                    <input type="date" name="date_depart" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Heure</label>
                    <input type="time" name="heure_depart" class="form-control">
                </div>

                <div class="col-12">
                    <label>Nombre de passagers</label>
                    <div class="passenger-select justify-content-center">
                        <div class="passenger-option" data-value="1">1</div>
                        <div class="passenger-option" data-value="2">2</div>
                        <div class="passenger-option" data-value="3">3</div>
                        <div class="passenger-option" data-value="4">4</div>
                    </div>
                    <input type="hidden" name="nb_passagers" id="nb_passagers" value="1">
                </div>

                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-search">
                        <i class="bi bi-search"></i> Rechercher
                    </button>
                </div>
            </div>
        </form>
    </section>
</div>

<script>
    // Sélection dynamique du nombre de passagers
    const options = document.querySelectorAll('.passenger-option');
    const hiddenInput = document.getElementById('nb_passagers');

    options.forEach(opt => {
        opt.addEventListener('click', () => {
            options.forEach(o => o.classList.remove('active'));
            opt.classList.add('active');
            hiddenInput.value = opt.dataset.value;
        });
    });

    // Active par défaut le premier rond
    document.querySelector('.passenger-option[data-value="1"]').classList.add('active');
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
