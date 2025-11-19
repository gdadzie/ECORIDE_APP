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


<!----
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Mes covoiturages sur ECORIDE - Gestion et affichage de vos trajets">
    <meta name="author" content="ECORIDE">

    <title>Mes Covoiturages</title>

    <!-- CSS Bootstrap & Icons -->
<link rel="stylesheet" href="/assets/css/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- CSS perso -->
<link rel="stylesheet" href="/assets/css/covoiturage/card_covoiturage.css">
<link rel="stylesheet" href="/assets/css/header/dashboard_header_mes_covoiturages.css">

<script src="/assets/js/bootstrap/bootstrap.js" defer></script>
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-5">

    <!-- Dashboard Header -->
    <div class="text-center mb-5">
        <img src="<?= htmlspecialchars($photoConducteur ?? '/uploads/photos/default-avatar.jpg') ?>"
             alt="Photo de profil" class="rounded-circle mb-3" width="100" height="100">
        <h2 class="fw-bold">Mes covoiturages</h2>
        <p class="text-muted">Bienvenue, <?= htmlspecialchars($userPseudo ?? 'Utilisateur') ?></p>
    </div>

    <!-- Liste des covoiturages -->
    <div class="row g-4">
        <?php if (!empty($covoiturages)): ?>
            <?php foreach ($covoiturages as $c):
                $conducteur = $c->getConducteur();
                $photo = $conducteur?->getPhoto() ?? '/uploads/photos/default-avatar.jpg';
                $pseudo = $conducteur?->getPseudo() ?? 'Conducteur';
                $note = $c->getNote() ?? 0;
                $ecologique = $c->isEcologique() ?? false;
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 border-0 rounded-3 hover-card">
                        <div class="card-body d-flex flex-column">

                            <!-- Conducteur & Note -->
                            <div class="d-flex align-items-center mb-3 gap-3">
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($pseudo) ?>
                                        <?php if ($ecologique): ?>
                                            <span class="badge bg-success-subtle text-success">Éco</span>
                                        <?php endif; ?>
                                    </h6>
                                    <small class="text-muted">
                                        <?php if ($note > 0): ?>
                                            <?php for ($i=1; $i<=5; $i++): ?>
                                                <i class="bi <?= $i <= floor($note) ? 'bi-star-fill text-warning' : 'bi-star text-muted' ?>"></i>
                                            <?php endfor; ?>
                                            <?= number_format($note,1) ?>
                                        <?php else: ?>
                                            Pas encore de note
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>

                            <!-- Trajet -->
                            <h5 class="mb-2">
                                <i class="bi bi-geo-alt-fill text-success me-1"></i>
                                <?= htmlspecialchars($c->getVilleDepartNom() ?? 'Départ') ?>
                                <span class="mx-1">→</span>
                                <?= htmlspecialchars($c->getVilleArriveeNom() ?? 'Arrivée') ?>
                            </h5>

                            <!-- Informations -->
                            <ul class="list-unstyled mb-3">
                                <li><i class="bi bi-calendar3 text-success me-2"></i> <?= htmlspecialchars($c->getDateDepart() ?? '—') ?></li>
                                <li><i class="bi bi-clock text-success me-2"></i> Départ : <?= htmlspecialchars($c->getHeureDepart() ?? '—') ?> | Arrivée : <?= htmlspecialchars($c->getHeureArrivee() ?? '—') ?></li>
                                <li><i class="bi bi-people-fill text-success me-2"></i> Places : <?= htmlspecialchars($c->getNbPlaces() ?? 0) ?></li>
                                <li><i class="bi bi-currency-euro text-success me-2"></i> Prix : <?= htmlspecialchars($c->getPrix() ?? 0) ?> €</li>
                                <li><i class="bi bi-hourglass-split text-success me-2"></i> Durée : <?= htmlspecialchars($c->getDureeMinutes() ?? 0) ?> min</li>
                                <li><i class="bi bi-car-front-fill text-success me-2"></i> Véhicule : <?= htmlspecialchars($c->getVehiculeNom() ?? '—') ?> - <?= htmlspecialchars($c->getVehiculeModele() ?? '—') ?></li>
                                <li><i class="bi bi-info-circle-fill text-success me-2"></i> Statut : <?= htmlspecialchars($c->getStatut() ?? '—') ?></li>
                            </ul>

                            <!-- Actions -->
                            <div class="mt-auto d-flex gap-2 flex-wrap">
                                <a href="index.php?entity=covoiturages&action=detail_covoiturage&id=<?= (int)$c->getIdCovoiturage() ?>"
                                   class="btn btn-outline-success btn-sm flex-fill">
                                    <i class="bi bi-eye me-1"></i> Détails
                                </a>
                                <a href="index.php?entity=covoiturages&action=modifier_covoiturage&id=<?= (int)$c->getIdCovoiturage() ?>"
                                   class="btn btn-outline-warning btn-sm flex-fill">
                                    <i class="bi bi-pencil-fill me-1"></i> Modifier
                                </a>
                                <a href="index.php?entity=covoiturages&action=supprimer&id=<?= (int)$c->getIdCovoiturage() ?>"
                                   class="btn btn-outline-danger btn-sm flex-fill"
                                   onclick="return confirm('Voulez-vous vraiment supprimer ce covoiturage ?');">
                                    <i class="bi bi-trash-fill me-1"></i> Supprimer
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted mt-5">
                Vous n’avez créé aucun covoiturage pour le moment.
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .hover-card:hover {
        transform: translateY(-4px);
        transition: all 0.3s ease;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
    ul.list-unstyled li i {
        width: 20px;
        display: inline-block;
    }
</style>

</body>
</html>

-->