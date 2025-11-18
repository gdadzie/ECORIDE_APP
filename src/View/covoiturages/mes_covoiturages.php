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
