<main class="flex-grow-1 container my-5">
    <h2 class="text-center theme-green mb-4">🚗 Covoiturages disponibles</h2>

    <?php if (empty($covoiturages)): ?>
        <div class="alert alert-warning text-center">
            Aucun covoiturage disponible pour le moment.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($covoiturages as $covoit): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-lg h-100">
                        <div class="card-body d-flex flex-column">
                            <!-- Header avec villes -->
                            <h5 class="card-title theme-green mb-2">
                                <?= htmlspecialchars($covoit['ville_depart']) ?> ➜ <?= htmlspecialchars($covoit['ville_arrivee']) ?>
                            </h5>
                            <p class="card-subtitle mb-3 text-muted">
                                Départ : <?= htmlspecialchars($covoit['date_depart']) ?> à <?= htmlspecialchars($covoit['heure_depart']) ?>
                            </p>

                            <!-- Infos covoiturage -->
                            <ul class="list-unstyled flex-grow-1 mb-3">
                                <li>
                                    <i class="bi bi-currency-euro"></i>
                                    <strong>Prix :</strong> <?= htmlspecialchars($covoit['prix']) ?> €
                                </li>
                                <li>
                                    <i class="bi bi-clock"></i>
                                    <strong>Durée :</strong> <?= htmlspecialchars($covoit['duree_minutes'] ?? 'N/A') ?> min
                                </li>
                                <li>
                                    <i class="bi bi-car-front"></i>
                                    <strong>Distance :</strong> <?= htmlspecialchars($covoit['distance_km']) ?> km
                                </li>
                                <li>
                                    <i class="bi bi-people"></i>
                                    <strong>Places :</strong> <?= htmlspecialchars($covoit['nb_places']) ?>
                                </li>
                                <?php if ($covoit['ecologique']): ?>
                                    <li class="text-success">
                                        🌱 Trajet écologique
                                    </li>
                                <?php endif; ?>
                            </ul>

                            <!-- Conducteur -->
                            <div class="mt-3 d-flex align-items-center">
                                <?php
                                $avatarPath = "/assets/images/avatars/" . htmlspecialchars($covoit['id_utilisateur']) . ".png";
                                if(file_exists(__DIR__ . "/../../assets/images/avatars/" . $covoit['id_utilisateur'] . ".png")):
                                    ?>
                                    <img src="<?= $avatarPath ?>" alt="Avatar conducteur" class="rounded-circle me-2" width="40" height="40">
                                <?php else: ?>
                                    <div class="avatar-fallback me-2">
                                        <?= strtoupper(substr($covoit['nom_utilisateur'] ?? 'A',0,1)) ?>
                                    </div>
                                <?php endif; ?>
                                <span><?= htmlspecialchars($covoit['nom_utilisateur'] ?? 'Anonyme') ?></span>
                            </div>

                            <!-- Bouton réservation / détails -->
                            <div class="mt-3 text-center">
                                <a href="index.php?entity=covoiturages&action=details&id=<?= $covoit['id_covoiturage'] ?>"
                                   class="btn btn-theme-green w-75">
                                    Voir le covoiturage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
