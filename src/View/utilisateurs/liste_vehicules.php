<?php include __DIR__ . '/../layout.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-5">
    <h3 class="mb-4 text-center" style="color: #00C853; font-weight: 700;">Mes Véhicules</h3>

    <!-- Messages suppression -->
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-dismissible fade show message-alert" role="alert"
             style="background-color: <?= $_GET['deleted']==1 ? '#B9F6CA' : '#FFCDD2' ?>; color: #212121;">
            <?= $_GET['deleted']==1 ? '✅ Le(s) véhicule(s) a/ont été supprimé(s) avec succès !' : '❌ Impossible de supprimer le(s) véhicule(s).' ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>

    <!-- Boutons Ajouter et Retour + suppression multiple -->
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
        <div class="d-flex gap-2">
            <a href="index.php?entity=vehicules&action=ajouter_vehicule" class="btn btn-danger">
                <i class="bi bi-plus"></i> Ajouter un véhicule
            </a>
            <a href="index.php?entity=utilisateurs&action=tableau_de_bord" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour au tableau de bord
            </a>
        </div>
        <button id="delete-selected" class="btn btn-danger fade-btn" style="display:none;">
            Supprimer la sélection
        </button>
    </div>

    <!-- Filtres -->
    <div class="row mb-3 g-2">
        <div class="col-md-3"><input type="text" id="filter-marque" class="form-control" placeholder="Filtrer par marque"></div>
        <div class="col-md-3"><input type="text" id="filter-modele" class="form-control" placeholder="Filtrer par modèle"></div>
        <div class="col-md-3"><input type="text" id="filter-couleur" class="form-control" placeholder="Filtrer par couleur"></div>
        <div class="col-md-3">
            <select id="filter-energie" class="form-select">
                <option value="">Filtrer par énergie</option>
                <option value="Essence">Essence</option>
                <option value="Diesel">Diesel</option>
                <option value="Électrique">Électrique</option>
                <option value="Hybride">Hybride</option>
                <option value="GPL">GPL</option>
            </select>
        </div>
    </div>

    <!-- Tableau des véhicules -->
    <?php if (!empty($vehicules)): ?>
        <form id="vehiculesForm" method="get" action="index.php">
            <input type="hidden" name="entity" value="vehicules">
            <input type="hidden" name="action" value="supprimer_vehicule_multiple">

            <div class="table-responsive shadow-sm p-3 mb-5 bg-white rounded">
                <table id="vehiculesTable" class="table table-hover align-middle text-center">
                    <thead style="background-color: #00C853; color: #FFFFFF;">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th class="fw-bold">#</th>
                        <th class="fw-bold">Marque</th>
                        <th class="fw-bold">Modèle</th>
                        <th class="fw-bold">Couleur</th>
                        <th class="fw-bold">Énergie</th>
                        <th class="fw-bold">Détails</th>
                        <th class="fw-bold">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($vehicules as $index => $v): ?>
                        <tr style="color: #212121;">
                            <td><input type="checkbox" class="select-row" name="ids[]" value="<?= $v->getIdVehicule() ?>"></td>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($v->getNomMarque()) ?></td>
                            <td><?= htmlspecialchars($v->getModele()) ?></td>
                            <td>
                                <span class="badge rounded-pill"
                                      style="background-color: <?= htmlspecialchars(strtolower($v->getCouleur())) ?>; color: #212121;">
                                    <?= htmlspecialchars($v->getCouleur()) ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $energie = $v->getEnergie() ?? '';
                                $badgeColor = match($energie) {
                                    'Essence' => 'background-color: #00C853; color: #FFFFFF;',
                                    'Diesel' => 'background-color: #66FF99; color: #212121;',
                                    'Électrique' => 'background-color: #B9F6CA; color: #212121;',
                                    'Hybride' => 'background-color: #66FF99; color: #212121;',
                                    'GPL' => 'background-color: #B9F6CA; color: #212121;',
                                    default => 'background-color: #B9F6CA; color: #212121;'
                                };
                                ?>
                                <span class="badge rounded-pill" style="<?= $badgeColor ?>"><?= htmlspecialchars($energie) ?></span>
                            </td>
                            <td>
                                <a href="index.php?entity=vehicules&action=detail&id_vehicule=<?= $v->getIdVehicule() ?>"
                                   class="btn btn-warning btn-sm" style="min-width: 80px;">
                                    Détails
                                </a>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center flex-wrap gap-1">
                                    <a href="index.php?entity=vehicules&action=edit&id=<?= $v->getIdVehicule() ?>"
                                       class="btn btn-sm" style="background-color: #00C853; color: #FFFFFF; min-width: 80px;">
                                        Modifier
                                    </a>
                                    <a href="index.php?entity=vehicules&action=supprimer_vehicule&id_vehicule=<?= $v->getIdVehicule() ?>"
                                       class="btn btn-sm btn-outline-danger" style="min-width: 80px;"
                                       onclick="return confirm('Voulez-vous vraiment supprimer ce véhicule ?')">
                                        Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-info text-center" style="background-color: #B9F6CA; color: #212121;">
            Vous n'avez aucun véhicule enregistré.
        </div>
    <?php endif; ?>

    <!-- Formulaire d'ajout ou modification (optionnel) -->
    <div class="card shadow-sm p-3 mb-5 bg-white rounded">
        <div class="card-body">
            <h5 class="card-title" style="color: #00C853;">Ajouter un nouveau véhicule</h5>
            <form method="post" action="index.php?entity=vehicules&action=ajouter_vehicule">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="marque" class="form-label">Marque</label>
                        <input type="text" class="form-control" id="marque" name="marque" required>
                    </div>
                    <div class="col-md-4">
                        <label for="modele" class="form-label">Modèle</label>
                        <input type="text" class="form-control" id="modele" name="modele" required>
                    </div>
                    <div class="col-md-4">
                        <label for="couleur" class="form-label">Couleur</label>
                        <input type="text" class="form-control" id="couleur" name="couleur" required>
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label for="energie" class="form-label">Énergie</label>
                        <select class="form-select" id="energie" name="energie" required>
                            <option value="">Sélectionner</option>
                            <option value="Essence">Essence</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Électrique">Électrique</option>
                            <option value="Hybride">Hybride</option>
                            <option value="GPL">GPL</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="nb_places" class="form-label">Nombre de places</label>
                        <input type="number" class="form-control" id="nb_places" name="nb_places">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
