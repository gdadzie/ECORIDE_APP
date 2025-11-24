<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EcoRide - Résultats de recherche covoiturage</title>
    <meta name="description"
          content="Comparez les covoiturages EcoRide : prix, conducteur, durée, note, voiture écologique, distance et disponibilité.">

    <meta name="robots" content="index, follow">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/theme/theme.css">
    <link rel="stylesheet" href="assets/css/covoiturage/resultats_recherches_covoiturages.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
</head>

<body>
<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

/* --- Gestion erreurs --- */
if (!isset($covoiturages) || empty($covoiturages)) {
    echo '<div class="alert alert-danger text-center mt-5">Aucun covoiturage trouvé.</div>';
    return;
}

/* --- Localisation FR --- */
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

<!-- H1 SEO principal -->
<h1 class="mt-5 mb-3 text-center">
    Résultats de votre recherche de covoiturage
</h1>

<!-- H2 détaillant la recherche -->
<h2 class="text-center text-muted mb-4">
    <?= htmlspecialchars($covoiturages[0]->getVilleDepartNom() ?? 'Départ') ?>
    → <?= htmlspecialchars($covoiturages[0]->getVilleArriveeNom() ?? 'Arrivée') ?>
</h2>

<!-- FORMULAIRE DE FILTRE ÉPURÉ -->
<div class="container mt-4">
    <div class="card p-3 shadow-sm" aria-label="Formulaire de filtres">
        <div class="d-flex flex-wrap gap-3 align-items-end">

            <div class="flex-grow-1" style="min-width:150px;">
                <label class="form-label mb-1">Écologique</label>
                <select id="filterEco" class="form-select form-select-sm">
                    <option value="">Indifférent</option>
                    <option value="1">Voiture électrique</option>
                </select>
            </div>

            <div class="flex-grow-1" style="min-width:150px;">
                <label class="form-label mb-1">Prix min (€)</label>
                <input type="number" id="filterMinPrice" class="form-control form-control-sm" min="0" placeholder="0">
            </div>

            <div class="flex-grow-1" style="min-width:150px;">
                <label class="form-label mb-1">Prix max (€)</label>
                <input type="number" id="filterMaxPrice" class="form-control form-control-sm" min="0" placeholder="100">
            </div>

            <div class="flex-grow-1" style="min-width:150px;">
                <label class="form-label mb-1">Durée max (minutes)</label>
                <input type="number" id="filterMaxDuration" class="form-control form-control-sm" min="1" placeholder="300">
            </div>

            <div style="min-width:120px;">
                <label class="form-label mb-1">Note min</label>
                <select id="filterMinNote" class="form-select form-select-sm">
                    <option value="">Indifférent</option>
                    <?php for($i=1;$i<=5;$i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> ★</option>
                    <?php endfor; ?>
                </select>
            </div>

        </div>
        <div id="counter" class="mt-2 fw-bold text-end"></div>
    </div>
</div>

<!-- LISTE DES RÉSULTATS -->
<div class="container my-5">
    <div class="row g-4" id="trajets">
        <?php foreach ($covoiturages as $covoiturage):
            $ecologique = $covoiturage->isEcologique();
            $dateStr = $covoiturage->getDateDepart() . ' ' . ($covoiturage->getHeureDepart() ?? '00:00');
            $affichageDateLong = formatDateFR($dateStr);
            $avatar = $covoiturage->getConducteur()->getPhoto();
            $initial = $avatar ? '' : strtoupper(substr($covoiturage->getConducteur()->getPseudo() ?? '', 0, 1));
            $note = $covoiturage->getNote() ?? 0;
            ?>
            <div class="col-md-6 col-lg-4 trajet"
                 data-eco="<?= $ecologique ? '1' : '0' ?>"
                 data-prix="<?= $covoiturage->getPrix() ?>"
                 data-duree="<?= $covoiturage->getDureeMinutes() ?>"
                 data-note="<?= $note ?>">
                <div class="card shadow-sm h-100 border-0 rounded-3 hover-card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <?php if($avatar): ?>
                                <img src="<?= htmlspecialchars($avatar) ?>" alt="Photo conducteur" class="rounded-circle" width="50" height="50">
                            <?php else: ?>
                                <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center text-dark"
                                     style="width:50px; height:50px; font-weight:600;">
                                    <?= htmlspecialchars($initial) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h3 class="h6 mb-1"><?= htmlspecialchars($covoiturage->getConducteur()->getPseudo()) ?>
                                    <?php if($ecologique): ?>
                                        <span class="badge bg-success-subtle text-success">Éco</span>
                                    <?php endif; ?>
                                </h3>
                                <small class="text-muted">
                                    <?php for ($i=1;$i<=5;$i++): ?>
                                        <i class="bi <?= $i <= floor($note)?'bi-star-fill text-warning':'bi-star text-muted' ?>"></i>
                                    <?php endfor; ?>
                                    <?= number_format($note,1) ?>
                                </small>
                            </div>
                        </div>

                        <h3 class="h5 mb-2"><i class="bi bi-geo-alt-fill text-success me-1"></i>
                            <?= htmlspecialchars($covoiturage->getVilleDepartNom()) ?> → <?= htmlspecialchars($covoiturage->getVilleArriveeNom()) ?>
                        </h3>

                        <ul class="list-unstyled mb-3">
                            <li><i class="bi bi-calendar3 text-success me-2"></i> <?= htmlspecialchars($affichageDateLong) ?></li>
                            <li><i class="bi bi-clock text-success me-2"></i> Départ : <?= htmlspecialchars($covoiturage->getHeureDepart()) ?></li>
                            <li><i class="bi bi-currency-euro text-success me-2"></i> Prix : <?= htmlspecialchars($covoiturage->getPrix()) ?> €</li>
                            <li><i class="bi bi-hourglass-split text-success me-2"></i> Durée : <?= htmlspecialchars($covoiturage->getDureeMinutes()) ?> min</li>
                        </ul>

                        <div class="mt-auto text-end">
                            <a href="index.php?entity=covoiturages&action=detail_covoiturage&id=<?= $covoiturage->getIdCovoiturage() ?>"
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

<script>
    function filtrerTrajets() {
        const eco = document.getElementById('filterEco').value;
        const minPrix = parseFloat(document.getElementById('filterMinPrice').value) || 0;
        const maxPrix = parseFloat(document.getElementById('filterMaxPrice').value) || 1000;
        const maxDuree = parseInt(document.getElementById('filterMaxDuration').value) || 10000;
        const minNote = parseFloat(document.getElementById('filterMinNote').value) || 0;

        const trajets = document.querySelectorAll('.trajet');
        let compteur = 0;

        trajets.forEach(trajet => {
            const tEco = trajet.dataset.eco;
            const prix = parseFloat(trajet.dataset.prix);
            const duree = parseInt(trajet.dataset.duree);
            const note = parseFloat(trajet.dataset.note);

            let visible = true;
            if(eco && tEco !== eco) visible = false;
            if(prix < minPrix || prix > maxPrix) visible = false;
            if(duree > maxDuree) visible = false;
            if(note < minNote) visible = false;

            trajet.style.display = visible ? 'block' : 'none';
            if(visible) compteur++;
        });

        document.getElementById('counter').innerText = compteur + " trajet(s) trouvé(s)";
    }

    document.querySelectorAll('#filterEco,#filterMinPrice,#filterMaxPrice,#filterMaxDuration,#filterMinNote')
        .forEach(el => el.addEventListener('input', filtrerTrajets));

    filtrerTrajets();
</script>

</body>
</html>
