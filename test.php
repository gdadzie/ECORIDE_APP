<h4 class="text-success mt-5">Mes Véhicules</h4>
<?php if (!empty($vehicules)): ?>
    <table class="table table-striped mt-3">
        <thead>
        <tr>
            <th>Marque</th>
            <th>Modèle</th>
            <th>Couleur</th>
            <th>Énergie</th>
            <th>Places</th>
            <th>Immatriculation</th>
            <th>Date 1ère immat.</th>
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
                <td><?= htmlspecialchars($v->getDatePremiereImmatriculation()) ?></td>
                <td>
                    <a href="../utilisateurs/index.php?entity=vehicules&action=supprimer_vehicule&id=<?= $v->getIdVehicule() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce véhicule ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun véhicule enregistré.</p>
<?php endif; ?>
</div>*


=====================
<?php
include __DIR__ . '/../../layout.php';
include __DIR__ . '/../../../View/partials/header.php';
?>

<head>
    <title>Recherche de covoiturages</title>
    <link rel="stylesheet" href="assets/css/covoiturage/covoiturages.css">
    <link rel="stylesheet" href="assets/css/covoiturage/formulaire_recherche_covoiturages_details.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container my-5">
    <h2 class="text-center mb-4 text-success fw-bold"  >Rechercher un covoiturage ECORIDE</h2>

    <form id="form-recherche " class="search-form formulaire-recherche" >
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Ville de départ</label>
                <input type="text" name="ville_depart" id="ville_depart" class="form-control" placeholder="Ex : Paris" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Ville d'arrivée</label>
                <input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control" placeholder="Ex : Lyon" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date_depart" id="date_depart" class="form-control" required>
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-success mt-4">
                    <i class="bi bi-search"></i> Rechercher
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/covoiturages/formulaire_recherche_covoiturages.js"></script>
