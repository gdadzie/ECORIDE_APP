<?php include __DIR__ . '/../layout.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php
$message = '';
$success = false;
$couleurs = ['Noir','Blanc','Gris','Rouge','Bleu','Vert','Jaune','Autre'];
$energies = ['Essence','Diesel','Électrique','Hybride','GPL','Autre'];
$marques = $this->repo->getAllMarques();
// Variables attendues : $message, $success, $marques, $couleurs, $energies, $vehicules
?>

<div class="container mt-4">

    <!-- Affichage du message de succès ou d'erreur -->
    <?php if (!empty($message)): ?>
        <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?> shadow-sm" role="alert">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire d'ajout de véhicule -->
    <div class="card shadow-sm p-4 mt-3">
        <h4 class="mb-4 text-success">Ajouter un véhicule</h4>

        <form method="POST" class="mb-4">
            <input type="hidden" name="add_vehicle" value="1">

            <div class="mb-3">
                <label for="id_marque" class="form-label">Marque</label>
                <select name="id_marque" id="id_marque" class="form-select" required>
                    <option value="">-- Sélectionnez une marque --</option>
                    <?php foreach ($marques as $marque): ?>
                        <option value="<?= htmlspecialchars($marque['id_marque']) ?>"><?= htmlspecialchars($marque['nom_marque']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="modele" class="form-label">Modèle</label>
                <input type="text" name="modele" id="modele" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="couleur" class="form-label">Couleur</label>
                <select name="couleur" id="couleur" class="form-select" required>
                    <?php foreach ($couleurs as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="energie" class="form-label">Énergie</label>
                <select name="energie" id="energie" class="form-select" required>
                    <?php foreach ($energies as $e): ?>
                        <option value="<?= htmlspecialchars($e) ?>"><?= htmlspecialchars($e) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="nb_places" class="form-label">Nombre de places</label>
                <input type="number" name="nb_places" id="nb_places" class="form-control" min="1" value="4">
            </div>

            <div class="mb-3">
                <label for="immatriculation" class="form-label">Immatriculation</label>
                <input type="text" name="immatriculation" id="immatriculation" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="date_premiere_immatriculation" class="form-label">Date première immatriculation</label>
                <input type="date" name="date_premiere_immatriculation" id="date_premiere_immatriculation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Ajouter le véhicule</button>
        </form>
    </div>

    <!-- Liste des véhicules de l'utilisateur -->
    <?php if (!empty($vehicules)): ?>
        <div class="card shadow-sm p-4 mt-4">
            <h5 class="mb-3 text-primary">Vos véhicules</h5>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Couleur</th>
                    <th>Énergie</th>
                    <th>Places</th>
                    <th>Immatriculation</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($vehicules as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars($v->getNomMarque()) ?></td>
                        <td><?= htmlspecialchars($v->getModele()) ?></td>
                        <td><?= htmlspecialchars($v->getCouleur()) ?></td>
                        <td><?= htmlspecialchars($v->getEnergie()) ?></td>
                        <td><?= htmlspecialchars($v->getNbPlaces()) ?></td>
                        <td><?= htmlspecialchars($v->getImmatriculation()) ?></td>
                        <td>
                            <a href="index.php?entity=vehicules&action=delete&id_vehicule=<?= $v['id_vehicule'] ?>"
                               class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce véhicule ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?><?php foreach ($vehicules as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars($v->getNomMarque()) ?></td>
                        <td><?= htmlspecialchars($v->getModele()) ?></td>
                        <td><?= htmlspecialchars($v->getCouleur()) ?></td>
                        <td><?= htmlspecialchars($v->getEnergie()) ?></td>
                        <td><?= htmlspecialchars($v->getNbPlaces()) ?></td>
                        <td><?= htmlspecialchars($v->getImmatriculation()) ?></td>

                        <td>
                            <a href="index.php?entity=vehicules&action=delete&id_vehicule=<?= $v->getIdVehicule() ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Supprimer ce véhicule ?')">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
