<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';
?>

<div class="container my-5" style="max-width:700px;">
    <h2 class="mb-4">Modifier le covoiturage</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <form method="POST">
        <!-- Ville de départ -->
        <div class="mb-3">
            <label for="ville_depart" class="form-label">Ville de départ</label>
            <input type="text" class="form-control" id="ville_depart" name="ville_depart"
                   value="<?= htmlspecialchars($covoiturage->getVilleDepart()) ?>" required>
        </div>

        <!-- Ville d'arrivée -->
        <div class="mb-3">
            <label for="ville_arrivee" class="form-label">Ville d'arrivée</label>
            <input type="text" class="form-control" id="ville_arrivee" name="ville_arrivee"
                   value="<?= htmlspecialchars($covoiturage->getVilleArrivee()) ?>" required>
        </div>

        <!-- Date et heure départ -->
        <div class="row mb-3">
            <div class="col">
                <label for="date_depart" class="form-label">Date de départ</label>
                <input type="date" class="form-control" id="date_depart" name="date_depart"
                       value="<?= htmlspecialchars($covoiturage->getDateDepart()) ?>" required>
            </div>
            <div class="col">
                <label for="heure_depart" class="form-label">Heure de départ</label>
                <input type="time" class="form-control" id="heure_depart" name="heure_depart"
                       value="<?= htmlspecialchars($covoiturage->getHeureDepart()) ?>" required>
            </div>
        </div>

        <!-- Durée en minutes -->
        <div class="mb-3">
            <label for="duree_minutes" class="form-label">Durée du trajet (minutes)</label>
            <input type="number" class="form-control" id="duree_minutes" name="duree_minutes"
                   value="<?= htmlspecialchars($covoiturage->getDureeMinutes()) ?>" min="1" required>
        </div>

        <!-- Prix et places -->
        <div class="row mb-3">
            <div class="col">
                <label for="prix" class="form-label">Prix (€)</label>
                <input type="number" class="form-control" id="prix" name="prix"
                       value="<?= htmlspecialchars($covoiturage->getPrix()) ?>" min="0" step="0.01" required>
            </div>
            <div class="col">
                <label for="nb_places" class="form-label">Nombre de places</label>
                <input type="number" class="form-control" id="nb_places" name="nb_places"
                       value="<?= htmlspecialchars($covoiturage->getNbPlaces()) ?>" min="1" required>
            </div>
        </div>

        <!-- Véhicule -->
        <div class="mb-3">
            <label for="vehicule" class="form-label">Véhicule</label>
            <select class="form-select" id="vehicule" name="id_vehicule">
                <?php foreach ($vehiculesDisponibles as $vehicule): ?>
                    <option value="<?= $vehicule->getIdVehicule() ?>"
                        <?= $vehicule->getIdVehicule() === $covoiturage->getIdVehicule() ? 'selected' : '' ?>>
                        <?= htmlspecialchars($vehicule->getNomMarque() . ' ' . $vehicule->getModele()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Écologique -->
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="ecologique" name="ecologique"
                <?= $covoiturage->isEcologique() ? 'checked' : '' ?>>
            <label class="form-check-label" for="ecologique">Covoiturage écologique</label>
        </div>

        <!-- Préférences -->
        <div class="mb-3">
            <label class="form-label">Préférences</label>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="fumeur" name="fumeur"
                    <?= $covoiturage->getFumeur() ? 'checked' : '' ?>>
                <label class="form-check-label" for="fumeur">Fumeur accepté</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="animaux" name="animaux"
                    <?= $covoiturage->getAnimaux() ? 'checked' : '' ?>>
                <label class="form-check-label" for="animaux">Animaux acceptés</label>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($covoiturage->getDescription()) ?></textarea>
        </div>

        <!-- Boutons -->
        <div class="mb-3 text-end">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="index.php?entity=covoiturages&action=mes_covoiturages" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<style>
    body { font-family: 'Inter', sans-serif; }
</style>
