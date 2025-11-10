<div class="container mt-5">
    <h2 class="mb-4 text-center">Liste des covoiturages</h2>

    <?php if (empty($covoiturages)): ?>
        <div class="alert alert-warning text-center">
            😕 Aucun covoiturage trouvé.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($covoiturages as $c): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= htmlspecialchars($c['ville_depart_nom'] ?? 'N/A') ?>
                                →
                                <?= htmlspecialchars($c['ville_arrivee_nom'] ?? 'N/A') ?>
                            </h5>
                            <p class="card-text"><strong>Date :</strong> <?= htmlspecialchars($c['date_depart'] ?? 'N/A') ?></p>
                            <p class="card-text"><strong>Heure :</strong> <?= htmlspecialchars($c['heure_depart'] ?? 'N/A') ?></p>
                            <p class="card-text"><strong>Prix :</strong> <?= htmlspecialchars($c['prix'] ?? '0') ?> €</p>
                            <p class="card-text"><strong>Places :</strong> <?= htmlspecialchars($c['nb_places'] ?? '0') ?></p>
                            <p class="card-text"><strong>Distance :</strong> <?= htmlspecialchars($c['distance_km'] ?? '0') ?> km</p>
                            <p class="card-text"><strong>Durée :</strong> <?= htmlspecialchars($c['duree_minutes'] ?? '0') ?> min</p>
                            <p class="card-text"><strong>Écologique :</strong> <?= isset($c['ecologique']) && $c['ecologique'] ? 'Oui' : 'Non' ?></p>
                            <p class="card-text"><strong>Statut :</strong> <?= htmlspecialchars($c['statut'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
