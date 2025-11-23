<?php
// Vérification de la session utilisateur
if (!isset($_SESSION['user'])) {
    header('Location: /?action=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Covoiturage</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Fond moderne -->
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            min-height: 100vh;
        }
        .card-large {
            max-width: 850px;
            border-radius: 20px;
        }
        .hover-soft:hover {
            transform: translateY(-4px);
            transition: 0.3s ease;
        }
    </style>
</head>

<body>

<div class="container py-4 d-flex justify-content-center">

    <!-- CARTE GLOBALE -->
    <div class="card shadow-lg card-large mx-auto p-4 hover-soft bg-white">

        <!-- Bouton Retour À L'INTÉRIEUR DE LA CARTE -->
        <div class="mb-3">
            <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill d-inline-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Retour
            </a>
        </div>

        <div class="card-body">

            <!-- INFORMATION CONDUCTEUR -->
            <div class="d-flex align-items-center gap-4 mb-4">
                <img src="<?= htmlspecialchars($avatarConducteur ?? '/uploads/photos/default-avatar.jpg') ?>"
                     class="rounded-circle border shadow-sm"
                     width="110" height="110" alt="Photo conducteur">

                <div>
                    <h3 class="fw-bold mb-1">
                        <?= htmlspecialchars($pseudoConducteur ?? 'Conducteur') ?>
                        <?php if ($ecologique): ?>
                            <span class="badge bg-success-subtle text-success">Éco</span>
                        <?php endif; ?>
                    </h3>

                    <div class="text-muted small mb-2">
                        ⭐ <?= number_format($noteUtilisateur ?? 0, 1) ?>/5
                    </div>

                    <div class="badge bg-light text-success border border-success px-3 py-2 fs-5">
                        <i class="bi bi-currency-euro me-1"></i><?= htmlspecialchars($prix) ?>
                    </div>
                </div>
            </div>

            <!-- TRAJET -->
            <div class="border-start border-3 border-success ps-3 mb-4">
                <h4 class="fw-semibold mb-1">
                    <i class="bi bi-geo-alt-fill text-success me-1"></i>
                    <?= htmlspecialchars($covoiturage->getVilleDepartNom()) ?>
                    →
                    <?= htmlspecialchars($covoiturage->getVilleArriveeNom()) ?>
                </h4>

                <div class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?= htmlspecialchars($affichageDate) ?> <br>
                    <i class="bi bi-clock me-1"></i>
                    <?= htmlspecialchars($heureDepart) ?> — <?= htmlspecialchars($heureArrivee) ?>
                </div>
            </div>

            <!-- BLOCS INFO -->
            <div class="row g-4 mb-4">

                <!-- Trajet -->
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <h6 class="fw-bold text-success mb-2">
                            <i class="bi bi-info-circle me-1"></i> Trajet
                        </h6>
                        <ul class="list-unstyled small mb-0">
                            <li><i class="bi bi-hourglass-split text-success me-2"></i>Durée :
                                <?= htmlspecialchars($duree) ?> min</li>
                            <li><i class="bi bi-signpost-split text-success me-2"></i>Distance :
                                <?= htmlspecialchars($distance) ?> km</li>
                            <li><i class="bi bi-people-fill text-success me-2"></i>Places :
                                <?= htmlspecialchars($nbPlaces) ?></li>
                        </ul>
                    </div>
                </div>

                <!-- Véhicule -->
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <h6 class="fw-bold text-success mb-2">
                            <i class="bi bi-car-front-fill me-1"></i> Véhicule
                        </h6>
                        <ul class="list-unstyled small mb-0">
                            <li><?= htmlspecialchars($vehiculeNom) ?> — <?= htmlspecialchars($vehiculeModele) ?></li>
                            <li><i class="bi bi-leaf me-1"></i> Écologique :
                                <?= $ecologique ? 'Oui' : 'Non' ?></li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- PRÉFÉRENCES -->
            <h5 class="fw-bold mb-3">Préférences</h5>
            <div class="d-flex gap-3 flex-wrap mb-4">
                <span class="badge bg-light text-dark p-2">
                    <i class="bi bi-person-x me-1"></i> Fumeur :
                    <?= $preferences['fumeur'] ? 'Oui' : 'Non' ?>
                </span>
                <span class="badge bg-light text-dark p-2">
                    <i class="bi bi-emoji-smile me-1"></i> Animaux :
                    <?= $preferences['animaux'] ? 'Oui' : 'Non' ?>
                </span>
                <span class="badge bg-light text-dark p-2">
                    <i class="bi bi-leaf me-1"></i> Écologique :
                    <?= $ecologique ? 'Oui' : 'Non' ?>
                </span>
            </div>

            <!-- AVIS -->
            <h5 class="fw-bold mb-3">Avis reçus</h5>
            <?php if (!empty($avisRecus)): ?>
                <?php foreach ($avisRecus as $avis): ?>
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <strong>Note : <?= htmlspecialchars($avis['note']) ?>/5</strong>
                        <p class="mb-1"><?= htmlspecialchars($avis['commentaire']) ?></p>
                        <span class="text-muted small">Par utilisateur #<?= htmlspecialchars($avis['id_emetteur']) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-muted">Aucun avis disponible.</div>
            <?php endif; ?>

            <!-- PARTICIPATION -->
            <div class="text-end mt-4">
                <?php if ($confirmNeeded): ?>
                    <form method="POST" class="d-inline-flex gap-2">
                        <button type="submit" name="confirm" value="oui"
                                class="btn btn-success rounded-pill">
                            Confirmer
                        </button>
                        <button type="submit" name="confirm" value="non"
                                class="btn btn-secondary rounded-pill">
                            Annuler
                        </button>
                    </form>
                <?php else: ?>
                    <form method="POST" class="d-inline-block">
                        <button type="submit" name="participer"
                                class="btn btn-success btn-lg rounded-pill px-4"
                                <?= ($nbPlaces <= 0 ? 'disabled' : '') ?>>
                            Participer
                        </button>
                    </form>
                <?php endif; ?>
            </div>

        </div>
    </div>

</div>

</body>
</html>
