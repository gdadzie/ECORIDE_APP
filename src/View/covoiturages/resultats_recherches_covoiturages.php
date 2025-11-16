<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

if (!isset($covoiturages) || empty($covoiturages)) {
    echo '<div class="alert alert-danger text-center mt-5">Aucun covoiturage trouvé.</div>';
    return;
}

$joursFRComplet = ['Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi','Thursday'=>'Jeudi','Friday'=>'Vendredi','Saturday'=>'Samedi','Sunday'=>'Dimanche'];
$moisFRComplet = ['January'=>'janvier','February'=>'février','March'=>'mars','April'=>'avril','May'=>'mai','June'=>'juin','July'=>'juillet','August'=>'août','September'=>'septembre','October'=>'octobre','November'=>'novembre','December'=>'décembre'];

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
            $dateStr = $covoiturage->getDateDepart() . ' ' . $covoiturage->getHeureDepart();
            $affichageDateLong = formatDateFR($dateStr);
            $heureArrivee = $covoiturage->getHeureArrivee();
            $avatar = $covoiturage->getConducteur()->getPhoto();
            $initial = $avatar ? '' : strtoupper(substr($covoiturage->getConducteur()->getPseudo(), 0, 1));
            $note = $covoiturage->getNote() ?? 0;
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card p-3">
                    <div class="date-depart text-center text-muted"><?= htmlspecialchars($affichageDateLong) ?></div>

                    <div class="driver-info d-flex align-items-center gap-2 my-2">
                        <?php if (!empty($avatar)): ?>
                            <img src="<?= htmlspecialchars($avatar) ?>" class="rounded-circle" width="50" height="50">
                        <?php else: ?>
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-dark"
                                 style="width:50px; height:50px; font-weight:600; background:#DAFAE6FF;">
                                <?= htmlspecialchars($initial) ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($covoiturage->getConducteur()->getPseudo()) ?>
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

                    <div class="trip-info mb-3">
                        <div class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-arrow-up-right"></i>
                            <div><?= htmlspecialchars($covoiturage->getVilleDepartNom()) ?></div>
                            <span class="badge badge-depart">Départ</span>
                        </div>
                        <div class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-arrow-down-left"></i>
                            <div><?= htmlspecialchars($covoiturage->getVilleArriveeNom()) ?></div>
                            <span class="badge badge-arrivee">Arrivée</span>
                        </div>
                        <div class="d-flex gap-2 mt-2 fs-7">
                            <div class="badge bg-light text-dark"><?= htmlspecialchars($covoiturage->getHeureDepart()) ?></div>
                            <div class="badge bg-light text-dark"><?= htmlspecialchars($heureArrivee) ?></div>
                            <div class="badge bg-light text-dark passenger-info">
                                <i class="bi bi-people-fill"></i> <?= htmlspecialchars($covoiturage->getNbPlaces()) ?>
                            </div>
                            <div class="badge bg-light text-dark"><?= htmlspecialchars($covoiturage->getDistanceKm()) ?> km</div>
                            <div class="badge bg-light text-dark"><?= htmlspecialchars($covoiturage->getDureeMinutes()) ?> min</div>
                        </div>
                    </div>

                    <div class="eco-card-footer d-flex justify-content-between">
                        <a href="index.php?entity=covoiturages&action=detail_covoiturage&id=<?= $covoiturage->getIdCovoiturage() ?>" class="btn btn-warning">
                            <i class="bi bi-eye"></i> Détail
                        </a>
                        <a href="#" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Accepter
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
