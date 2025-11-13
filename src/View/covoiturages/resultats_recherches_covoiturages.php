<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

// 🔹 Guard pour éviter "Undefined variable"
if (!isset($covoiturages)) {
    $covoiturages = [];
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container my-5">
    <h2 class="text-center mb-4 text-success fw-bold">Résultats de la recherche de covoiturages</h2>

    <?php if (!empty($covoiturages)): ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php foreach ($covoiturages as $c):
                if ($c['nb_places'] < 1) continue; // Filtrer les covoiturages sans places
                $ecologique = (int)$c['ecologique'] === 1;
                ?>
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-header d-flex align-items-center">
                            <img src="https://i.pravatar.cc/60?u=<?= $c['id_covoiturage'] ?>" class="rounded-circle me-3" alt="Avatar conducteur">
                            <div>
                                <strong><?= htmlspecialchars($c['pseudo'] ?? 'Conducteur') ?></strong>
                                <div class="small text-muted">
                                    <?= htmlspecialchars($c['ville_depart_nom']) ?>
                                    <i class="bi bi-arrow-right"></i>
                                    <?= htmlspecialchars($c['ville_arrivee_nom']) ?>
                                </div>
                                <div class="small">Note : <?= htmlspecialchars($c['note'] ?? 'N/A') ?> / 5</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><i class="bi bi-calendar-event"></i> <?= htmlspecialchars($c['date_depart']) ?></p>
                            <p class="mb-1"><i class="bi bi-clock"></i> <?= htmlspecialchars($c['heure_depart']) ?> - <?= htmlspecialchars($c['heure_arrivee'] ?? 'N/A') ?></p>
                            <p class="mb-1"><i class="bi bi-people"></i> Places restantes : <?= htmlspecialchars($c['nb_places']) ?></p>
                            <p class="mb-1"><i class="bi bi-currency-euro"></i> Prix : <?= htmlspecialchars($c['prix']) ?> €</p>
                            <?php if($ecologique): ?>
                                <span class="badge bg-success mb-1"><i class="bi bi-leaf-fill"></i> Trajet écologique</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer text-center">
                            <a href="index.php?entity=covoiturages&action=detail_covoiturage&id=<?= $c['id_covoiturage'] ?>" class="btn btn-primary">
                                <i class="bi bi-info-circle"></i> Détail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center mt-4">
            😕 Aucun covoiturage disponible pour ces villes.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
