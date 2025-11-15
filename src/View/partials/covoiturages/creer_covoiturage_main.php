<?php
include __DIR__ . '/../../layout.php';
include __DIR__ . '/../../partials/header.php';

// 🔹 Guard pour éviter "Undefined variable"
if (!isset($villes)) {
    $villes = [];
}
?>

<style>
    body { background-color: #f5f8f6; font-family: 'Inter', sans-serif; }
    .form-container { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 40px; margin-top: 40px; }
    .form-title { text-align: center; font-weight: 700; color: #198754; margin-bottom: 30px; }
    .btn-submit { background-color: #198754; border: none; color: #fff; border-radius: 8px; padding: 10px 25px; font-weight: 600; transition: all 0.2s ease-in-out; }
    .btn-submit:hover { background-color: #157347; transform: translateY(-2px); }
    .message { text-align: center; margin-top: 20px; font-weight: 600; }
    .message.error { color: #d9534f; }
    .message.success { color: #198754; }
</style>

<div class="container">
    <div class="form-container">
        <h2 class="form-title">Créer un nouveau covoiturage</h2>

        <?php if (!empty($error)): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php elseif (!empty($successMsg)): ?>
            <p class="message success"><?= htmlspecialchars($successMsg) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="row g-3">
                <!-- Villes -->
                <div class="col-md-6">
                    <label class="form-label">Ville de départ</label>
                    <select name="ville_depart" id="ville_depart" class="form-select" required>
                        <option value="">-- Sélectionnez une ville --</option>
                        <?php foreach ($villes as $v): ?>
                            <option value="<?= (int) $v['id_ville'] ?>"><?= htmlspecialchars($v['nom_ville']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ville d'arrivée</label>
                    <select name="ville_arrivee" id="ville_arrivee" class="form-select" required>
                        <option value="">-- Sélectionnez une ville --</option>
                        <?php foreach ($villes as $v): ?>
                            <option value="<?= (int) $v['id_ville'] ?>"><?= htmlspecialchars($v['nom_ville']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date / heure / durée -->
                <div class="col-md-4">
                    <label class="form-label">Date de départ</label>
                    <input type="date" name="date_depart" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Heure de départ</label>
                    <input type="time" name="heure_depart" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Durée estimée (minutes)</label>
                    <input type="number" name="duree_minutes" class="form-control" min="0" placeholder="Ex : 90">
                </div>

                <!-- Distance / prix / places -->
                <div class="col-md-4">
                    <label class="form-label">Distance estimée (km)</label>
                    <input type="number" name="distance_km" step="0.1" class="form-control" placeholder="Ex : 250">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Prix (€)</label>
                    <input type="number" name="prix" step="0.1" class="form-control" placeholder="Ex : 25.00">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nombre de places</label>
                    <input type="number" name="nb_places" class="form-control" min="1" value="1">
                </div>

                <!-- Sélection du véhicule -->
                <div class="col-md-6">
                    <label class="form-label">Véhicule</label>
                    <select name="id_vehicule" class="form-select" required>
                        <option value="">-- Sélectionnez un véhicule --</option>
                        <?php if (!empty($vehicules)): ?>
                            <?php foreach ($vehicules as $v): ?>
                                <option value="<?= (int) $v->getIdVehicule() ?>">
                                    <?= htmlspecialchars($v->getNomMarque()  . $v->getModele() . ' (' . $v->getImmatriculation() . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option disabled>Aucun véhicule enregistré</option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Option écologique -->
                <div class="col-md-6 d-flex align-items-center mt-4">
                    <div class="form-check">
                        <input type="checkbox" name="ecologique" id="ecologique" class="form-check-input">
                        <label for="ecologique" class="form-check-label">
                            Trajet écologique (véhicule électrique ou hybride)
                        </label>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-submit">
                    <i class="bi bi-plus-circle"></i> Créer le covoiturage
                </button>
                <a href="index.php?entity=covoiturages&action=liste_covoiturages" class="btn btn-secondary ms-2">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </form>
    </div>
</div>


