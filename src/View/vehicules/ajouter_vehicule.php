
<?php
// Affichage du message
if ($message !== ''): ?>
    <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?> mt-3">
        <?= $message ?>
    </div>
<?php endif; ?>
<div class="tab-pane fade" id="add-vehicule">

    <!-- Inclure le formulaire d'ajout de véhicule -->
    <div class="card shadow-sm p-4 mt-3">
        <h4 class="mb-4 text-success">Ajouter un véhicule</h4>
        <form method="POST" class="mb-4">
            <input type="hidden" name="add_vehicle" value="1">

            <div class="mb-3">
                <label for="id_marque">Marque</label>
                <select name="id_marque" id="id_marque" class="form-select" required>
                    <option value="">-- Sélectionnez une marque --</option>
                    <?php foreach ($marques as $marque): ?>
                        <option value="<?= $marque['id_marque'] ?>"><?= htmlspecialchars($marque['nom_marque']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="modele">Modèle</label>
                <input type="text" name="modele" id="modele" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="couleur">Couleur</label>
                <select name="couleur" id="couleur" class="form-select" required>
                    <?php foreach ($couleurs as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="energie">Énergie</label>
                <select name="energie" id="energie" class="form-select" required>
                    <?php foreach ($energies as $e): ?>
                        <option value="<?= htmlspecialchars($e) ?>"><?= htmlspecialchars($e) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="nb_places">Nombre de places</label>
                <input type="number" name="nb_places" id="nb_places" class="form-control" min="1" value="4">
            </div>

            <div class="mb-3">
                <label for="immatriculation">Immatriculation</label>
                <input type="text" name="immatriculation" id="immatriculation" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="date_premiere_immatriculation">Date première immatriculation</label>
                <input type="date" name="date_premiere_immatriculation" id="date_premiere_immatriculation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Ajouter le véhicule</button>
        </form>



</div>
