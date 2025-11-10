<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

// 🔹 Guard pour éviter "Undefined variable"
if (!isset($covoiturages)) {
    $covoiturages = [];
}
?>

<!-- Bootstrap CSS & Icons CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container my-5">
    <h2 class="text-center mb-4 text-success fw-bold">Liste globale des covoiturages ECORIDE</h2>

    <?php if (!empty($covoiturages)): ?>
        <div class="row g-4">
            <?php foreach ($covoiturages as $c): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <!-- Avatar et villes -->
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <img src="https://i.pravatar.cc/60?u=<?= $c['id_covoiturage'] ?>"
                                 class="rounded-circle me-3" alt="Avatar conducteur">
                            <div>
                                <div class="fw-bold text-success">
                                    <?= htmlspecialchars($c['ville_depart_nom']) ?>
                                    <i class="bi bi-arrow-right"></i>
                                    <?= htmlspecialchars($c['ville_arrivee_nom']) ?>
                                </div>
                                <small class="text-muted"><?= htmlspecialchars($c['date_depart']) ?> à <?= htmlspecialchars($c['heure_depart']) ?></small>
                            </div>
                        </div>

                        <!-- Body card -->
                        <div class="card-body">
                            <?php if(!empty($c['duree_minutes'])): ?>
                                <p class="mb-1"><i class="bi bi-clock"></i> Durée : <?= htmlspecialchars($c['duree_minutes']) ?> min</p>
                            <?php endif; ?>
                            <?php if(!empty($c['distance_km'])): ?>
                                <p class="mb-1"><i class="bi bi-signpost-2"></i> Distance : <?= htmlspecialchars($c['distance_km']) ?> km</p>
                            <?php endif; ?>
                            <p class="mb-1"><i class="bi bi-currency-euro"></i> Prix : <?= htmlspecialchars($c['prix']) ?> €</p>
                            <p class="mb-1"><i class="bi bi-people"></i> Places disponibles : <?= htmlspecialchars($c['nb_places']) ?></p>
                            <?php if(!empty($c['ecologique'])): ?>
                                <span class="badge bg-success">Trajet écologique</span>
                            <?php endif; ?>
                            <p class="mt-2 mb-0"><strong>Statut :</strong> <?= htmlspecialchars($c['statut']) ?></p>
                        </div>

                        <!-- Footer avec actions -->
                        <div class="card-footer d-flex justify-content-between">
                            <a href="../utilisateurs/index.php?entity=covoiturages&action=edit&id=<?= $c['id_covoiturage'] ?>" class="btn btn-success btn-sm">
                                Modifier
                            </a>
                            <a href="../utilisateurs/index.php?entity=covoiturages&action=delete&id=<?= $c['id_covoiturage'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Voulez-vous vraiment supprimer ce covoiturage ?');">
                                Supprimer
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center fs-5 mt-5">Aucun covoiturage disponible.</p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
