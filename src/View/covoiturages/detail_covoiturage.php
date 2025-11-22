<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail du covoiturage</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">


    <!-- Messages -->
    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>


    <div class="container py-4 d-flex justify-content-center">

        ```
        <div class="card shadow-lg mb-4" style="max-width: 720px; width: 100%; border-radius: 15px;">
            <div class="card-body p-4 d-flex flex-column gap-3">

                <!-- Date -->
                <div class="text-center mb-2">
                    <h5 class="fw-bold text-dark mb-0"><?= htmlspecialchars($affichageDate) ?></h5>
                </div>

                <!-- Conducteur et prix -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                        <img src="<?= htmlspecialchars($avatarConducteur) ?>" class="rounded-circle border" width="80" height="80" alt="Avatar <?= htmlspecialchars($pseudoConducteur) ?>">
                        <div class="flex-grow-1">
                            <div class="fw-bold fs-5 mb-1">
                                <?= htmlspecialchars($pseudoConducteur) ?>
                                <?php if ($ecologique): ?>
                                    <span class="badge bg-success-subtle text-success">Éco</span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex align-items-center gap-2 text-muted small">
                                <div>⭐ <?= number_format($noteUtilisateur ?? 0, 1) ?>/5</div>
                                <div>• <i class="bi bi-people-fill"></i> <?= htmlspecialchars($nbPlaces) ?> places</div>
                            </div>
                        </div>
                    </div>

                    <div class="badge bg-light border border-success text-success fw-bold d-flex align-items-center justify-content-center" style="font-size:1.25rem; padding: 8px 15px; border-radius: 12px;">
                        <i class="bi bi-currency-euro me-1"></i> <?= htmlspecialchars($prix) ?>
                    </div>
                </div>

                <!-- Départ / Arrivée -->
                <div class="d-flex mb-3 gap-3">
                    <div class="d-flex flex-column align-items-center me-3">
                        <span class="bg-success rounded-circle" style="width:10px;height:10px;"></span>
                        <div class="flex-grow-1 bg-success-subtle my-1" style="width:2px;"></div>
                        <span class="bg-success rounded-circle" style="width:10px;height:10px;"></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="mb-2">
                            <div class="fw-semibold fs-6"><?= htmlspecialchars($covoiturage->getVilleDepartNom()) ?></div>
                            <span class="badge bg-secondary-subtle">Départ</span>
                        </div>
                        <div class="mb-2">
                            <div class="fw-semibold fs-6"><?= htmlspecialchars($covoiturage->getVilleArriveeNom()) ?></div>
                            <span class="badge bg-secondary-subtle">Arrivée</span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap mt-1">
                            <div class="badge bg-light text-dark"><i class="bi bi-clock me-1"></i> <?= htmlspecialchars($heureDepart) ?></div>
                            <div class="badge bg-light text-dark"><i class="bi bi-stopwatch me-1"></i> <?= htmlspecialchars($heureArrivee) ?></div>
                            <div class="badge bg-light text-dark"><i class="bi bi-hourglass-split me-1"></i> <?= htmlspecialchars($duree) ?> min</div>
                            <div class="badge bg-light text-dark"><i class="bi bi-signpost-split me-1"></i> <?= htmlspecialchars($distance) ?> km</div>
                        </div>
                    </div>
                </div>

                <!-- Véhicule -->
                <div class="mb-3">
                    <span class="fw-semibold text-muted small">Véhicule :</span>
                    <div class="text-dark small"><?= htmlspecialchars($vehiculeNom) ?> <?= htmlspecialchars($vehiculeModele) ?></div>
                </div>

                <!-- Préférences et écologie -->
                <div class="mb-3">
                    <span class="fw-semibold text-muted small">Préférences :</span>
                    <ul class="list-unstyled mb-0 small d-flex gap-3 flex-wrap">
                        <li><i class="bi bi-person-x me-1"></i> Fumeur : <?= $preferences['fumeur'] ? 'Oui' : 'Non' ?></li>
                        <li><i class="bi bi-emoji-smile me-1"></i> Animaux : <?= $preferences['animaux'] ? 'Oui' : 'Non' ?></li>
                        <li><i class="bi bi-leaf me-1"></i> Écologique : <?= $ecologique ? 'Oui' : 'Non' ?></li>
                    </ul>
                </div>

                <!-- Avis reçus -->
                <div class="mb-3">
                    <h6 class="fw-semibold text-muted">Avis reçus :</h6>
                    <?php if (!empty($avisRecus)): ?>
                        <?php foreach ($avisRecus as $avis): ?>
                            <div class="p-2 border rounded mb-2 bg-light">
                                <div class="fw-bold">Note : <?= htmlspecialchars($avis['note']) ?>/5</div>
                                <div><?= htmlspecialchars($avis['commentaire']) ?></div>
                                <div class="text-muted small">Émis par utilisateur #<?= htmlspecialchars($avis['id_emetteur']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                      <a href="index.php?entity=avis&action=avis"><div class="text-muted small">Aucun avis reçu.</div></a>
                    <?php endif; ?>
                </div>

                <!-- Bouton Participer -->
                <div class="d-flex justify-content-end mt-2">
                    <?php if ($confirmNeeded): ?>
                        <form method="POST" class="d-flex gap-2">
                            <input type="hidden" name="participer" value="1">
                            <button type="submit" name="confirm" value="oui" class="btn btn-success rounded-pill">Confirmer</button>
                            <button type="submit" name="confirm" value="non" class="btn btn-secondary rounded-pill">Annuler</button>
                        </form>
                    <?php else: ?>
                        <form method="POST">
                            <button type="submit" name="participer" class="btn btn-success rounded-pill" <?= ($nbPlaces <= 0 ? 'disabled' : '') ?>>
                                Participer
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        ```

    </div>



    <!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
