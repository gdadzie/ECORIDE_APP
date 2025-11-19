<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

// Vérification covoiturage
if (!isset($covoiturage) || empty($covoiturage)) {
    echo '<div class="alert alert-danger text-center mt-5">Covoiturage introuvable.</div>';
    return;
}

// Définir si trajet écologique
$ecologique = $covoiturage->isEcologique();

// Date et heure
$dateStr = $covoiturage->getDateDepart() . ' ' . ($covoiturage->getHeureDepart() ?? '00:00');
try {
    $dateTime = new DateTime($dateStr);
} catch (Exception $e) {
    $dateTime = new DateTime();
}

// Jours et mois français
$joursFR = ['Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi','Thursday'=>'Jeudi',
    'Friday'=>'Vendredi','Saturday'=>'Samedi','Sunday'=>'Dimanche'];
$moisFR = ['January'=>'janvier','February'=>'février','March'=>'mars','April'=>'avril','May'=>'mai',
    'June'=>'juin','July'=>'juillet','August'=>'août','September'=>'septembre','October'=>'octobre',
    'November'=>'novembre','December'=>'décembre'];

$jourFR  = $joursFR[$dateTime->format('l')] ?? $dateTime->format('l');
$moisFR  = $moisFR[$dateTime->format('F')] ?? $dateTime->format('F');
$affichageDate = sprintf('%s %d %s %s - %s', $jourFR, (int)$dateTime->format('d'), $moisFR, $dateTime->format('Y'), $dateTime->format('H:i'));

// Conducteur
$conducteur = $covoiturage->getConducteur();
$pseudo = $conducteur?->getPseudo() ?? 'Conducteur';
$photoConducteur = $conducteur?->getPhoto() ?? '/uploads/photos/default-avatar.jpg';
$nbAvis = $conducteur?->getNbAvis() ?? 0;

// Covoiturage
$note = $covoiturage->getNote() ?? 0;
$nbPlaces = $covoiturage->getNbPlaces() ?? 0;
$prix = $covoiturage->getPrix() ?? 0;
$villeDepart = $covoiturage->getVilleDepartNom() ?? $covoiturage->getVilleDepart() ?? '-';
$villeArrivee = $covoiturage->getVilleArriveeNom() ?? $covoiturage->getVilleArrivee() ?? '-';
$heureDepart = $covoiturage->getHeureDepart() ?? '-';
$heureArrivee = $covoiturage->getHeureArrivee() ?? '-';
$duree = $covoiturage->getDureeMinutes() ?? 0;
$distance = $covoiturage->getDistanceKm() ?? 0;
$modePaiement = $covoiturage->getModePaiement() ?? 'Carte / Cash';

// Véhicule
$vehiculeNom = $covoiturage->getVehiculeNom() ?? 'Véhicule inconnu';
$vehiculeModele = $covoiturage->getVehiculeModele() ?? '-';
$vehiculeEnergie = $covoiturage->getVehiculeEnergie() ?? '-';

// Préférences conducteur
$preferences = [
    'fumeur' => $covoiturage->getFumeur() ?? false,
    'animaux' => $covoiturage->getAnimaux() ?? false,
];
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container my-5 d-flex justify-content-center">
    <div class="card shadow-sm border-0 h-100" style="max-width:650px;">
        <!-- Bouton Retour -->
        <div class="p-3">
            <a href="index.php?entity=covoiturages&action=resultats_recherche" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left-circle me-1"></i> Retour
            </a>
        </div>

        <!-- Date -->
        <div class="text-center text-muted mb-3"><?= htmlspecialchars($affichageDate) ?></div>

        <!-- Conducteur -->
        <div class="d-flex align-items-center mb-3 gap-3">
            <img src="<?= htmlspecialchars($photoConducteur) ?>" class="rounded-circle" width="50" height="50" alt="Avatar <?= htmlspecialchars($pseudo) ?>">
            <div>
                <div class="fw-bold fs-6"><?= htmlspecialchars($pseudo) ?>
                    <?php if ($ecologique): ?>
                        <span class="badge bg-success-subtle text-success">Éco</span>
                    <?php endif; ?>
                </div>
                <div class="text-muted small">
                    <a href="index.php?entity=avis&conducteur=<?= $conducteur?->getIdUtilisateur() ?? 0 ?>">
                        <?= $nbAvis ?> avis
                    </a> • <i class="bi bi-people-fill"></i> <?= htmlspecialchars($nbPlaces) ?> places
                </div>
            </div>
        </div>

        <!-- Véhicule -->
        <div class="mb-2">
            <span class="fw-semibold text-muted small">Véhicule :</span>
            <div class="text-dark small"><?= htmlspecialchars($vehiculeNom) ?> <?= htmlspecialchars($vehiculeModele) ?> (<?= htmlspecialchars($vehiculeEnergie) ?>)</div>
        </div>

        <!-- Préférences conducteur -->
        <div class="mb-3">
            <span class="fw-semibold text-muted small">Préférences :</span>
            <ul class="list-unstyled mb-0 small">
                <li><i class="bi bi-x-circle me-1"></i> Fumeur : <?= $preferences['fumeur'] ? 'Oui' : 'Non' ?></li>
                <li><i class="bi bi-x-circle me-1"></i> Animaux : <?= $preferences['animaux'] ? 'Oui' : 'Non' ?></li>
            </ul>
        </div>

        <!-- Trajet -->
        <div class="d-flex mb-3">
            <div class="d-flex flex-column align-items-center me-3">
                <span class="bg-success rounded-circle" style="width:10px;height:10px;"></span>
                <div class="flex-grow-1 bg-success-subtle my-1" style="width:2px;"></div>
                <span class="bg-success rounded-circle" style="width:10px;height:10px;"></span>
            </div>
            <div>
                <div class="mb-1">
                    <div class="fw-semibold fs-6"><?= htmlspecialchars($villeDepart) ?></div>
                    <span class="badge bg-secondary-subtle">Départ</span>
                </div>
                <div class="mb-1">
                    <div class="fw-semibold fs-6"><?= htmlspecialchars($villeArrivee) ?></div>
                    <span class="badge bg-secondary-subtle">Arrivée</span>
                </div>
                <div class="d-flex gap-2 mt-1">
                    <div class="badge bg-light text-dark"><i class="bi bi-clock me-1"></i> <?= htmlspecialchars($heureDepart) ?></div>
                    <div class="badge bg-light text-dark"><i class="bi bi-stopwatch me-1"></i> <?= htmlspecialchars($heureArrivee) ?></div>
                    <div class="badge bg-light text-dark"><i class="bi bi-hourglass-split me-1"></i> <?= htmlspecialchars($duree) ?> min</div>
                    <div class="badge bg-light text-dark"><i class="bi bi-signpost-split me-1"></i> <?= htmlspecialchars($distance) ?> km</div>
                    <div class="badge bg-light text-dark"><i class="bi bi-currency-euro me-1"></i> <?= htmlspecialchars($prix) ?></div>
                </div>
            </div>
        </div>

        <!-- Bouton Participer -->
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-success">Participer</button>
        </div>
    </div>
</div>

<style>
    body { font-family: 'Inter', sans-serif; }
    .card { border-radius: 0.8rem; }
    ul.list-unstyled li i { width: 16px; display: inline-block; color: #28a745; }
</style>
