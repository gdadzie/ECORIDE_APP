<?php include __DIR__ . '/../layout.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">
    <h2 class="text-primary mb-4">⚙️ Mon Profil</h2>

    <!-- Message de succès ou d'erreur -->
    <?php if (isset($message) && $message !== ''): ?>
        <div class="alert alert-<?= ($success ?? false) ? 'success' : 'danger' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="profilTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="infos-tab" data-bs-toggle="tab" data-bs-target="#infos" type="button" role="tab">Mes Informations</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="vehicule-tab" data-bs-toggle="tab" data-bs-target="#vehicule" type="button" role="tab">Mes Véhicules</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="covoit-tab" data-bs-toggle="tab" data-bs-target="#covoit" type="button" role="tab">Mes Covoiturages</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">Changer Mot de Passe</button>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- INFOS PERSONNELLES -->
        <div class="tab-pane fade show active" id="infos" role="tabpanel">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="pseudo" class="form-label">Pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= htmlspecialchars($user->getPseudo() ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user->getEmail() ?? '') ?>" required>
                </div>
                <button type="submit" name="update_info" class="btn btn-primary">Mettre à jour mes informations</button>
            </form>
        </div>

        <!-- VEHICULE -->
        <div class="tab-pane fade" id="vehicule" role="tabpanel">
            <h5 class="mb-3">Mes Véhicules</h5>
            <?php if (!empty($vehicules)): ?>
                <ul class="list-group mb-4">
                    <?php foreach ($vehicules as $v): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($v->getNomMarque() . ' ' . $v->getModele() . ' (' . $v->getImmatriculation() . ')') ?>
                            <div>
                                <a href="index.php?entity=vehicules&action=edit&id=<?= $v->getIdVehicule() ?>" class="btn btn-sm btn-warning">Modifier</a>
                                <a href="index.php?entity=vehicules&action=delete&id=<?= $v->getIdVehicule() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce véhicule ?')">Supprimer</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Vous n'avez aucun véhicule enregistré.</p>
            <?php endif; ?>

            <h5 class="mb-3">Ajouter un véhicule</h5>
            <form method="POST" action="">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="nom_marque" class="form-label">Marque</label>
                        <input type="text" id="nom_marque" class="form-control" required>
                        <input type="hidden" name="marque" id="id_marque">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="modele" class="form-label">Modèle</label>
                        <input type="text" name="modele" id="modele" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="couleur" class="form-label">Couleur</label>
                        <input type="text" name="couleur" id="couleur" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="energie" class="form-label">Énergie</label>
                        <select name="energie" id="energie" class="form-select" required>
                            <?php $energies = ['Essence','Diesel','GPL','Hybride','Electrique']; ?>
                            <?php foreach ($energies as $e): ?>
                                <option value="<?= $e ?>"><?= $e ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="nb_places" class="form-label">Nombre de places</label>
                        <input type="number" name="nb_places" id="nb_places" class="form-control" min="1" max="9" value="1" required>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="immatriculation" class="form-label">Immatriculation</label>
                        <input type="text" name="immatriculation" id="immatriculation" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="date_premiere" class="form-label">Date 1ère immatriculation</label>
                        <input type="date" name="date_premiere" id="date_premiere" class="form-control" required>
                    </div>
                </div>
                <button type="submit" name="add_vehicule" class="btn btn-success">Ajouter le véhicule</button>
            </form>
        </div>

        <!-- COVOITURAGES -->
        <div class="tab-pane fade" id="covoit" role="tabpanel">
            <?php if (!empty($covoiturages)): ?>
                <ul class="list-group">
                    <?php foreach ($covoiturages as $c): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($c['ville_depart']) ?> → <?= htmlspecialchars($c['ville_arrivee']) ?>
                            le <?= htmlspecialchars($c['date_depart']) ?> à <?= htmlspecialchars($c['heure_depart']) ?>
                            (<?= htmlspecialchars($c['nb_places']) ?> places)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Vous n'avez créé aucun covoiturage pour le moment.</p>
            <?php endif; ?>
        </div>

        <!-- CHANGER MOT DE PASSE -->
        <div class="tab-pane fade" id="password" role="tabpanel">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="mdp_current" class="form-label">Mot de passe actuel</label>
                    <input type="password" name="mdp_current" id="mdp_current" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="mdp_new" class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="mdp_new" id="mdp_new" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="mdp_confirm" class="form-label">Confirmer nouveau mot de passe</label>
                    <input type="password" name="mdp_confirm" id="mdp_confirm" class="form-control" required>
                </div>
                <button type="submit" name="update_password" class="btn btn-primary">Changer le mot de passe</button>
            </form>
        </div>
    </div>
</div>

<!-- jQuery & jQuery UI pour l'autocomplétion -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
    $(function() {
        var marques = <?= json_encode($marquesJS ?? []) ?>;

        $('#nom_marque').autocomplete({
            source: marques,
            minLength: 1,
            select: function(event, ui) {
                $('#nom_marque').val(ui.item.value);
                $('#id_marque').val(ui.item.id_marque);
                return false;
            }
        });
    });
</script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
