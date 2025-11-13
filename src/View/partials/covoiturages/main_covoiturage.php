<main class="flex-grow-1 container my-5 ">

    <!-- COVOITURAGE PRESENTATION -->
    <h2 class='mb-3 text-success'>Covoiturages EcoRide</h2>
    <p class="page-description">
        Bienvenue sur EcoRide, votre plateforme de covoiturage écologique. Trouvez un trajet économique et respectueux de l'environnement.
        Explorez les trajets proposés par nos conducteurs ou partagez votre voyage pour réduire votre empreinte carbone !
    </p>

    <!-- FORMULAIRE DE RECHERCHE DE COVOITURAGES --->
    <div class="formulaire-recherche ">
        <?php include __DIR__ . '/../partials/formulaire_recherche_covoiturages.php'; ?>
    </div>



    <!-- AFFICHAGE RESULTAT DE LA RECHERCHE DES COVOITURAGES --->
    <div class="container">
        <?php if (!empty($covoiturages)): ?>
            <div class="row g-4">
                <?php foreach ($covoiturages as $covoiturage): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card covoiturage-card">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center driver-info mb-3">
                                    <img src="<?= htmlspecialchars($covoiturage->getPhotoConducteur() ?? 'https://i.pravatar.cc/70?img='.rand(1,70)) ?>" alt="Conducteur">
                                    <div>
                                        <strong><?= htmlspecialchars($covoiturage->getPseudo() ?? 'Conducteur inconnu') ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($covoiturage->getMarque() ?? 'Marque') ?> <?= htmlspecialchars($covoiturage->getModele() ?? 'Modèle') ?> (<?= htmlspecialchars($covoiturage->getEnergie() ?? 'Énergie') ?>)</small>
                                    </div>
                                </div>

                                <div class="trip-info mb-3">
                                    <p><i class="bi bi-arrow-up-right"></i> Départ : <?= htmlspecialchars($covoiturage->getVilleDepart()) ?></p>
                                    <p><i class="bi bi-arrow-down-left"></i> Arrivée : <?= htmlspecialchars($covoiturage->getVilleArrivee()) ?></p>
                                    <p><i class="bi bi-calendar-event"></i> Date : <?= htmlspecialchars($covoiturage->getDateDepart()) ?></p>
                                    <p><i class="bi bi-clock"></i> Heure : <?= htmlspecialchars($covoiturage->getHeureDepart()) ?></p>
                                    <p><i class="bi bi-people"></i> Places : <?= htmlspecialchars($covoiturage->getNbPlaces()) ?></p>
                                    <?php if ($covoiturage->isEcologique()): ?>
                                        <span class="eco-label"><i class="bi bi-leaf"></i> Voyage écologique</span>
                                    <?php endif; ?>
                                </div>

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <span class="price"><?= number_format($covoiturage->getPrix(), 2) ?> €</span>
                                    <button class="btn btn-details" onclick="location.href='details_covoiturage.php?id=<?= urlencode($covoiturage->getIdCovoiturage()) ?>'">
                                        Détails
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php if (!empty($_POST) && empty($covoiturages)): ?>
            <div class="alert alert-warning text-center">
                😕 Aucun covoiturage trouvé pour ces critères.
            </div>
        <?php endif; ?>

    </div>

</main>
<?php endif; ?>