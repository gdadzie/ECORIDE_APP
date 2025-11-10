<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

// 🔹 Guard pour éviter les erreurs si $covoiturage n'est pas défini
if (!isset($covoiturage) || empty($covoiturage)) {
    echo '<div class="alert alert-danger text-center mt-5">Covoiturage introuvable.</div>';
    return;
}

$ecologique = (int)$covoiturage['ecologique'] === 1;
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container my-5">
    <?php if ($covoiturage): ?>
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center">
                <img src="<?= htmlspecialchars($covoiturage['photo_conducteur'] ?? 'https://i.pravatar.cc/60') ?>"
                     class="rounded-circle me-3" alt="Photo conducteur" width="60" height="60">
                <h5 class="mb-0"><?= htmlspecialchars($covoiturage['conducteur']) ?></h5>
            </div>
            <div class="card-body">
                <p><strong>Départ :</strong> <?= htmlspecialchars($covoiturage['ville_depart_nom']) ?></p>
                <p><strong>Arrivée :</strong> <?= htmlspecialchars($covoiturage['ville_arrivee_nom']) ?></p>
                <p><strong>Date :</strong> <?= htmlspecialchars($covoiturage['date_depart']) ?></p>
                <p><strong>Heure :</strong> <?= htmlspecialchars($covoiturage['heure_depart']) ?></p>
                <p><strong>Places disponibles :</strong> <?= htmlspecialchars($covoiturage['nb_places']) ?></p>
                <p><strong>Distance :</strong> <?= htmlspecialchars($covoiturage['distance_km']) ?> km</p>
                <p><strong>Prix :</strong> <?= htmlspecialchars($covoiturage['prix']) ?> €</p>
                <p><strong>Durée :</strong> <?= htmlspecialchars($covoiturage['duree_minutes']) ?> min</p>
                <p><strong>Note conducteur :</strong> <?= htmlspecialchars($covoiturage['note_conducteur'] ?? 'Non noté') ?></p>
                <?php if (!empty($covoiturage['ecologique'])): ?>
                    <span class="badge bg-success">Trajet écologique</span>
                <?php endif; ?>
            </div>
            <!-- Footer avec actions -->
            <div class="card-footer d-flex justify-content-between">
                <a href="index.php?entity=covoiturages&action=detail_covoiturage&id=<?= $covoiturage['id_covoiturage'] ?>"
                   class="btn btn-primary btn-sm">
                    <i class="bi bi-eye"></i> Détail
                </a>
            </div>

        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            <h4>Covoiturage introuvable</h4>
            <p>Le covoiturage que vous recherchez n'existe pas ou n'est plus disponible.</p>
            <a href="index.php?entity=covoiturages&action=recherche_covoiturages" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left"></i> Retour à la recherche
            </a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
