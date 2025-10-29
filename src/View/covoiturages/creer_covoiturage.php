<?php include __DIR__ . '/../layout.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">
    <h2 class="text-primary mb-4">🛣️ Créer un Covoiturage</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $success ? 'success' : 'danger' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Véhicule</label>
            <select name="vehicule_id" class="form-select" required>
                <option value="">-- Sélectionnez votre véhicule --</option>
                <?php foreach ($vehicules as $v): ?>
                    <option value="<?= $v->getIdVehicule() ?>">
                        <?= htmlspecialchars($v->getNomMarque() . ' ' . $v->getModele() . ' (' . $v->getImmatriculation() . ')') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Ville de départ</label>
            <input type="text" name="ville_depart" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ville d'arrivée</label>
            <input type="text" name="ville_arrivee" class="form-control" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Date de départ</label>
                <input type="date" name="date_depart" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Heure de départ</label>
                <input type="time" name="heure_depart" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre de places disponibles</label>
            <input type="number" name="nb_places" class="form-control" min="1" max="9" value="1" required>
        </div>

        <button type="submit" class="btn btn-success">Créer le covoiturage</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include __DIR__ . '/../partials/footer.php'; ?>
