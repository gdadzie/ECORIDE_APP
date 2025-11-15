<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

// Guard : s'assurer que $covoiturages (liste) est présent
if (!isset($covoiturages) || empty($covoiturages)) {
    echo '<div class="alert alert-danger text-center mt-5">Aucun covoiturage trouvé.</div>';
    return;
}

// Fonctions utilitaires pour date
$joursFRComplet = ['Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi','Thursday'=>'Jeudi','Friday'=>'Vendredi','Saturday'=>'Samedi','Sunday'=>'Dimanche'];
$moisFRComplet = ['January'=>'janvier','February'=>'février','March'=>'mars','April'=>'avril','May'=>'mai','June'=>'juin','July'=>'juillet','August'=>'août','September'=>'septembre','October'=>'octobre','November'=>'novembre','December'=>'décembre'];
function formatDateFR($dateStr) {
    global $joursFRComplet, $moisFRComplet;
    try {
        $dt = new DateTime($dateStr);
    } catch (Exception $e) {
        $dt = new DateTime();
    }
    $jourFR = $joursFRComplet[$dt->format('l')] ?? $dt->format('l');
    $moisFR = $moisFRComplet[$dt->format('F')] ?? $dt->format('F');
    return sprintf('%s %d %s %s - %s', $jourFR, (int)$dt->format('d'), $moisFR, $dt->format('Y'), $dt->format('H:i'));
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

<div class="container my-5">
    <div class="row g-4">
        <?php foreach ($covoiturages as $covoiturage):
            $ecologique = isset($covoiturage['ecologique']) && (int)$covoiturage['ecologique'] === 1;
            $dateStr = ($covoiturage['date_depart'] ?? '') . ' ' . ($covoiturage['heure_depart'] ?? '');
            $affichageDateLong = formatDateFR($dateStr);
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100 p-3">
                    <!-- Date -->
                    <div class="text-center text-muted mb-2"><?= htmlspecialchars($affichageDateLong) ?></div>

                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?= htmlspecialchars($covoiturage['photo_conducteur'] ?? 'https://i.pravatar.cc/60') ?>" class="rounded-circle" width="50" height="50">
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($covoiturage['pseudo'] ?? 'Conducteur') ?>
                                    <?php if ($ecologique): ?>
                                        <span class="badge bg-success-subtle text-success">Écologique</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-muted small">Note <?= htmlspecialchars($covoiturage['note'] ?? '-') ?> • <?= htmlspecialchars($covoiturage['nb_places'] ?? '-') ?> place(s)</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small"><?= htmlspecialchars($covoiturage['mode_paiement'] ?? 'Carte / Cash') ?></div>
                            <div class="fw-semibold"><?= isset($covoiturage['prix']) ? htmlspecialchars($covoiturage['prix']) . ' €' : '-' ?></div>
                        </div>
                    </div>

                    <!-- Trajet -->
                    <div class="d-flex mb-3">
                        <div class="d-flex flex-column align-items-center me-2">
                            <span class="bg-success rounded-circle" style="width:10px;height:10px;"></span>
                            <div class="flex-grow-1 bg-success-subtle my-1" style="width:2px;"></div>
                            <span class="bg-success rounded-circle" style="width:10px;height:10px;"></span>
                        </div>
                        <div>
                            <div class="mb-1">
                                <div class="fw-semibold"><?= htmlspecialchars($covoiturage['ville_depart_nom'] ?? '-') ?></div>
                                <span class="badge bg-secondary-subtle">Départ</span>
                            </div>
                            <div class="mb-1">
                                <div class="fw-semibold"><?= htmlspecialchars($covoiturage['ville_arrivee_nom'] ?? '-') ?></div>
                                <span class="badge bg-secondary-subtle">Arrivée</span>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <div class="badge bg-light text-dark"><?= htmlspecialchars($covoiturage['heure_depart'] ?? '-') ?></div>
                                <div class="badge bg-light text-dark"><?= isset($covoiturage['duree_minutes']) ? htmlspecialchars($covoiturage['duree_minutes']).' min' : '-' ?></div>
                                <div class="badge bg-light text-dark"><?= isset($covoiturage['distance_km']) ? htmlspecialchars($covoiturage['distance_km']).' km' : '-' ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <a href="/covoiturage/<?= htmlspecialchars($covoiturage['id'] ?? '#') ?>" class="btn btn-success w-100">Voir détails</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
