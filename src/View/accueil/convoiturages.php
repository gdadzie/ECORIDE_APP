<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

// ✅ Empêche la double ouverture de session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Exemple de pseudo si connecté
$pseudo = $_SESSION['pseudo'] ?? null;
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #f4fdf6;
        font-family: 'Segoe UI', sans-serif;
    }

    h1 {
        font-weight: 700;
        color: #2e7d32;
        margin-bottom: 0.5rem;
    }

    .welcome {
        color: #388e3c;
        font-weight: 500;
    }

    .search-section {
        max-width: 750px;
        background: #ffffff;
        border-radius: 16px;
        padding: 2rem;
        margin: 2rem auto;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: 1px solid #d0ebd0;
    }

    label {
        font-weight: 500;
        color: #2e7d32;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #bde0bd;
        box-shadow: none;
    }

    .form-control:focus {
        border-color: #66bb6a;
        box-shadow: 0 0 0 0.2rem rgba(102,187,106,0.2);
    }

    .btn-search {
        background-color: #4caf50;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.6rem 1.2rem;
        transition: 0.2s;
        font-weight: 500;
    }

    .btn-search:hover {
        background-color: #43a047;
        transform: translateY(-1px);
    }

    /* Sélecteur passagers circulaire */
    .passenger-select {
        display: flex;
        gap: 0.6rem;
        align-items: center;
        margin-top: 0.5rem;
    }

    .passenger-option {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid #81c784;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2e7d32;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        background: #fff;
    }

    .passenger-option.active,
    .passenger-option:hover {
        background-color: #4caf50;
        color: white;
        border-color: #388e3c;
    }
</style>

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

<?php include __DIR__ . '/../partials/footer.php'; ?>
