<?php include __DIR__ . '/../partials/header.php'; ?> <!-- Si tu as un header commun -->

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4 text-primary fw-bold">
                        🚗 Créer un nouveau covoiturage
                    </h3>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info text-center fw-semibold">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="needs-validation" novalidate>

                        <!-- Ville de départ -->
                        <div class="mb-3">
                            <label for="ville_depart" class="form-label fw-semibold">Ville de départ</label>
                            <select class="form-select" id="ville_depart" name="ville_depart" required>
                                <option value="">-- Choisissez une ville --</option>
                                <?php foreach ($villes as $ville): ?>
                                    <option value="<?= $ville['id_ville'] ?>">
                                        <?= htmlspecialchars($ville['nom_ville']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez choisir une ville de départ.</div>
                        </div>

                        <!-- Ville d'arrivée -->
                        <div class="mb-3">
                            <label for="ville_arrivee" class="form-label fw-semibold">Ville d’arrivée</label>
                            <select class="form-select" id="ville_arrivee" name="ville_arrivee" required>
                                <option value="">-- Choisissez une ville --</option>
                                <?php foreach ($villes as $ville): ?>
                                    <option value="<?= $ville['id_ville'] ?>">
                                        <?= htmlspecialchars($ville['nom_ville']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez choisir une ville d’arrivée.</div>
                        </div>

                        <!-- Date de départ -->
                        <div class="mb-3">
                            <label for="date_depart" class="form-label fw-semibold">Date de départ</label>
                            <input
                                    type="date"
                                    class="form-control"
                                    id="date_depart"
                                    name="date_depart"
                                    min="<?= date('Y-m-d') ?>"
                                    required
                            >
                            <div class="invalid-feedback">Veuillez indiquer une date valide.</div>
                        </div>

                        <!-- Heure de départ -->
                        <div class="mb-3">
                            <label for="heure_depart" class="form-label fw-semibold">Heure de départ</label>
                            <input
                                    type="time"
                                    class="form-control"
                                    id="heure_depart"
                                    name="heure_depart"
                                    required
                            >
                            <div class="invalid-feedback">Veuillez indiquer l’heure de départ.</div>
                        </div>

                        <!-- Nombre de places -->
                        <div class="mb-3">
                            <label for="nb_places" class="form-label fw-semibold">Nombre de places disponibles</label>
                            <input
                                    type="number"
                                    class="form-control"
                                    id="nb_places"
                                    name="nb_places"
                                    min="1"
                                    max="7"
                                    required
                            >
                            <div class="invalid-feedback">Indiquez un nombre de places valide (1–7).</div>
                        </div>

                        <!-- Distance (km) -->
                        <div class="mb-3">
                            <label for="distance_km" class="form-label fw-semibold">Distance estimée (km)</label>
                            <input
                                    type="number"
                                    class="form-control"
                                    id="distance_km"
                                    name="distance_km"
                                    min="1"
                                    step="0.1"
                                    required
                            >
                            <div class="invalid-feedback">Veuillez indiquer une distance en kilomètres.</div>
                        </div>

                        <!-- Écologique -->
                        <div class="form-check form-switch mb-4">
                            <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="ecologique"
                                    name="ecologique"
                                    value="1"
                            >
                            <label class="form-check-label" for="ecologique">
                                Trajet écologique 🌱
                            </label>
                        </div>

                        <!-- Bouton -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                <i class="bi bi-plus-circle me-2"></i>Créer le covoiturage
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Bootstrap 5 validation
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
