<?php include __DIR__ . '/../layout.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<h5 class="mb-3">Mes Véhicules</h5>
<?php if (!empty($vehicules)): ?>
    <ul class="list-group mb-4">
        <?php foreach ($vehicules as $v): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($v->getNomMarque() . ' ' . $v->getModele() . ' (' . $v->getImmatriculation() . ')') ?>
                <div>
                    <a href="index.php?entity=vehicules&action=edit&id=<?= $v->getIdVehicule() ?>" class="btn btn-sm btn-warning">Modifier</a>
                    <a href="index.php?entity=vehicules&action=supprimer_vehicule&id=<?= $v->getIdVehicule() ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Voulez-vous vraiment supprimer ce véhicule ?')">Supprimer</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Vous n'avez aucun véhicule enregistré.</p>
<?php endif; ?>
