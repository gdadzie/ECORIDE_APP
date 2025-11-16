<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

// Guard : s'assurer que $covoiturage est présent
if (!isset($covoiturage) || empty($covoiturage)) {
    echo '<div class="alert alert-danger text-center mt-5">Covoiturage introuvable.</div>';
    return;
}

// Définir si trajet écologique
$ecologique = $covoiturage->isEcologique();

// Construire la date en français
$dateStr = $covoiturage->getDateDepart() . ' ' . $covoiturage->getHeureDepart();
try {
    $dateTime = new DateTime($dateStr);
} catch (Exception $e) {
    $dateTime = new DateTime();
}

$joursFRComplet = [
        'Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi','Thursday'=>'Jeudi',
        'Friday'=>'Vendredi','Saturday'=>'Samedi','Sunday'=>'Dimanche'
];
$moisFRComplet = [
        'January'=>'janvier','February'=>'février','March'=>'mars','April'=>'avril','May'=>'mai',
        'June'=>'juin','July'=>'juillet','August'=>'août','September'=>'septembre','October'=>'octobre',
        'November'=>'novembre','December'=>'décembre'
];

$jourFR  = $joursFRComplet[$dateTime->format('l')] ?? $dateTime->format('l');
$moisFR  = $moisFRComplet[$dateTime->format('F')] ?? $dateTime->format('F');
$affichageDateLong = sprintf(
        '%s %d %s %s - %s',
        $jourFR,
        (int)$dateTime->format('d'),
        $moisFR,
        $dateTime->format('Y'),
        $dateTime->format('H:i')
);

// Récupération des valeurs
$pseudo = $covoiturage->getConducteur()->getPseudo() ?? 'Conducteur';
$photoConducteur = $covoiturage->getConducteur()->getPhoto() ?? 'https://i.pravatar.cc/60';
$note = $covoiturage->getNote(); // peut être null
$nbPlaces = $covoiturage->getNbPlaces() ?? '-';
$modePaiement = $covoiturage->getModePaiement() ?? 'Carte / Cash';
$prix = $covoiturage->getPrix() ?? '-';
$villeDepart = $covoiturage->getVilleDepartNom() ?? '-';
$villeArrivee = $covoiturage->getVilleArriveeNom() ?? '-';
$heureDepart = $covoiturage->getHeureDepart() ?? '-';
$duree = $covoiturage->getDureeMinutes() ?? '-';
$distance = $covoiturage->getDistanceKm() ?? '-';

// Nombre d'avis du conducteur (à créer dans l'entité Utilisateur : getNbAvis())
$nbAvis = $covoiturage->getConducteur()->getNbAvis() ?? 0;
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container my-5 d-flex justify-content-center">
    <div class="card shadow-sm border-0 p-4" style="max-width:600px; width:100%;">
        <!-- Date -->
        <div class="text-center text-muted mb-3"><?= htmlspecialchars($affichageDateLong) ?></div>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <img src="<?= htmlspecialchars($photoConducteur) ?>" class="rounded-circle" width="60" height="60">
                <div>
                    <div class="fw-bold"><?= htmlspecialchars($pseudo) ?>
                        <?php if ($ecologique): ?>
                            <span class="badge bg-success-subtle text-success">Écologique</span>
                        <?php endif; ?>
                    </div>

                    <!-- Notes étoiles et avis -->
                    <div class="d-flex align-items-center gap-1 mt-1">
                        <?php
                        $noteMax = 5;
                        $noteArrondie = $note !== null ? floor($note) : 0;
                        $demiEtoile = $note !== null && ($note - $noteArrondie) >= 0.5;
                        for ($i = 1; $i <= $noteMax; $i++):
                            if ($i <= $noteArrondie): ?>
                                <i class="bi bi-star-fill text-warning"></i>
                            <?php elseif ($i == $noteArrondie + 1 && $demiEtoile): ?>
                                <i class="bi bi-star-half text-warning"></i>
                            <?php else: ?>
                                <i class="bi bi-star text-secondary"></i>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($note !== null): ?>
                            <span class="ms-2 text-muted">(<?= htmlspecialchars($note) ?>)</span>
                        <?php endif; ?>

                        <span class="ms-2 text-muted">• <?= htmlspecialchars($nbPlaces) ?> place(s)</span>

                        <!-- Lien discret vers les avis -->
                        • <a href="index.php?entity=utilisateurs&action=avis&id=<?= $covoiturage->getConducteur()->getIdUtilisateur() ?>" class="text-decoration-underline">
                            <?= $nbAvis ?? 0 ?> avis
                            <?php if ($noteMoyenne !== null): ?>
                                - <?= number_format($noteMoyenne, 1) ?>/5
                            <?php endif; ?>
                        </a>


                    </div>
                </div>
            </div>
            <div class="text-end">
                <div class="text-muted small"><?= htmlspecialchars($modePaiement) ?></div>
                <div class="fw-semibold fs-5"><?= null !== $prix ? htmlspecialchars($prix) . ' €' : '-' ?></div>
            </div>
        </div>

        <!-- Trajet -->
        <div class="d-flex mb-4">
            <div class="d-flex flex-column align-items-center me-3">
                <span class="bg-success rounded-circle" style="width:12px;height:12px;"></span>
                <div class="flex-grow-1 bg-success-subtle my-1" style="width:2px;"></div>
                <span class="bg-success rounded-circle" style="width:12px;height:12px;"></span>
            </div>
            <div>
                <div class="mb-2">
                    <div class="fw-semibold fs-5"><?= htmlspecialchars($villeDepart) ?></div>
                    <span class="badge bg-secondary-subtle">Départ</span>
                </div>
                <div class="mb-2">
                    <div class="fw-semibold fs-5"><?= htmlspecialchars($villeArrivee) ?></div>
                    <span class="badge bg-secondary-subtle">Arrivée</span>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <div class="badge bg-light text-dark"><?= htmlspecialchars($heureDepart) ?></div>
                    <div class="badge bg-light text-dark"><?= htmlspecialchars($duree) ?> min</div>
                    <div class="badge bg-light text-dark"><?= htmlspecialchars($distance) ?> km</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="d-flex justify-content-between mt-4">
            <a href="javascript:history.back()" class="text-muted d-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Retour
            </a>
            <button class="btn btn-success px-4">Accepter</button>
        </div>
    </div>
</div>