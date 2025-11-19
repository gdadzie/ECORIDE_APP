<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

if (!isset($covoiturages) || empty($covoiturages)) {
    echo '<div class="alert alert-danger text-center mt-5">Aucun covoiturage trouvé.</div>';
    return;
}

$joursFRComplet = [
        'Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi','Thursday'=>'Jeudi',
        'Friday'=>'Vendredi','Saturday'=>'Samedi','Sunday'=>'Dimanche'
];
$moisFRComplet = [
        'January'=>'janvier','February'=>'février','March'=>'mars','April'=>'avril','May'=>'mai',
        'June'=>'juin','July'=>'juillet','August'=>'août','September'=>'septembre',
        'October'=>'octobre','November'=>'novembre','December'=>'décembre'
];

function formatDateFR($dateStr) {
    global $joursFRComplet, $moisFRComplet;
    try { $dt = new DateTime($dateStr); } catch (Exception $e) { $dt = new DateTime(); }
    $jourFR = $joursFRComplet[$dt->format('l')] ?? $dt->format('l');
    $moisFR = $moisFRComplet[$dt->format('F')] ?? $dt->format('F');
    return sprintf('%s %d %s %s - %s', $jourFR, (int)$dt->format('d'), $moisFR, $dt->format('Y'), $dt->format('H:i'));
}
?>

<link rel="stylesheet" href="assets/css/covoiturage/resultats_recherches_covoiturages.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container my-5">
    <div class="row g-4">
        <?php foreach ($covoiturages as $covoiturage):
            $ecologique = $covoiturage->isEcologique();
            $dateStr = $covoiturage->getDateDepart() . ' ' . ($covoiturage->getHeureDepart() ?? '00:00');
            $affichageDateLong = formatDateFR($dateStr);
            $heureArrivee = $covoiturage->getHeureArrivee() ?? '';
            $avatar = $covoiturage->getConducteur()->getPhoto();
            $initial = $avatar ? '' : strtoupper(substr($covoiturage->getConducteur()->getPseudo() ?? '', 0, 1));
            $note = $covoiturage->getNote() ?? 0;
            $villeDepart = $covoiturage->getVilleDepartNom() ?? '-';
            $villeArrivee = $covoiturage->getVilleArriveeNom() ?? '-';
            $nbPlaces = $covoiturage->getNbPlaces() ?? 0;
            $prix = $covoiturage->getPrix() ?? 0;
            $distanceKm = $covoiturage->getDistanceKm() ?? 0;
            $dureeMinutes = $covoiturage->getDureeMinutes() ?? 0;
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <!-- Date -->
                    <div class="card-header text-center text-muted bg-light">
                        <?= htmlspecialchars($affichageDateLong) ?>
                    </div>

                    <!-- Conducteur -->
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <?php if (!empty($avatar)): ?>
                                <img src="<?= htmlspecialchars($avatar) ?>" class="rounded-circle" width="50" height="50">
                            <?php else: ?>
                                <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center text-dark"
                                     style="width:50px; height:50px; font-weight:600;">
                                    <?= htmlspecialchars($initial) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($covoiturage->getConducteur()->getPseudo() ?? '') ?>
                                    <?php if ($ecologique): ?>
                                        <span class="badge bg-success-subtle text-success">Éco</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-muted small">
                                    <?php for ($i=1; $i<=5; $i++): ?>
                                        <i class="bi <?= $i <= floor($note) ? 'bi-star-fill text-warning' : 'bi-star text-muted' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Trajet -->
                        <div class="mb-3">
                            <p class="mb-1"><i class="bi bi-arrow-up-right me-2 text-success"></i> <?= htmlspecialchars($villeDepart) ?> <span class="badge bg-success-subtle text-success">Départ</span></p>
                            <p class="mb-1"><i class="bi bi-arrow-down-left me-2 text-danger"></i> <?= htmlspecialchars($villeArrivee) ?> <span class="badge bg-danger-subtle text-danger">Arrivée</span></p>
                        </div>

                        <!-- Infos supplémentaires -->
                        <div class="d-flex flex-wrap gap-2 fs-7 mb-3">
                            <div class="badge bg-light text-dark"><i class="bi bi-clock me-1"></i> <?= htmlspecialchars($covoiturage->getHeureDepart() ?? '') ?></div>
                            <div class="badge bg-light text-dark"><i class="bi bi-stopwatch me-1"></i> <?= htmlspecialchars($heureArrivee) ?></div>
                            <div class="badge bg-light text-dark"><i class="bi bi-people-fill me-1"></i> <?= htmlspecialchars($nbPlaces) ?> places</div>
                            <div class="badge bg-light text-dark"><i class="bi bi-currency-euro me-1"></i> <?= htmlspecialchars($prix) ?></div>
                            <div class="badge bg-light text-dark"><i class="bi bi-signpost-split me-1"></i> <?= htmlspecialchars($distanceKm) ?> km</div>
                            <div class="badge bg-light text-dark"><i class="bi bi-hourglass-split me-1"></i> <?= htmlspecialchars($dureeMinutes) ?> min</div>
                        </div>

                        <!-- Bouton Détails -->
                        <div class="d-flex justify-content-end">
                            <a href="index.php?entity=covoiturages&action=detail_covoiturage&id=<?= htmlspecialchars($covoiturage->getIdCovoiturage()) ?>"
                               class="btn btn-outline-success btn-sm">
                                <i class="bi bi-eye me-1"></i> Détails
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
