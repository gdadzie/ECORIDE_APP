<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';
?>

<div class="container my-5 d-flex justify-content-center">
    <div class="card shadow-sm border-0 h-100" style="max-width:650px;">

        <!-- RETOUR -->
        <div class="p-3">
            <a href="index.php?entity=covoiturages&action=resultats_recherche" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left-circle me-1"></i> Retour
            </a>
        </div>

        <!-- TITRE -->
        <h3 class="text-center mb-3">
            <?= htmlspecialchars($covoiturage->getVilleDepart()) ?> → <?= htmlspecialchars($covoiturage->getVilleArrivee()) ?>
        </h3>

        <!-- INFOS TRAJET -->
        <div class="px-4">
            <p><strong>Date :</strong> <?= (new DateTime($covoiturage->getDateDepart()))->format('d/m/Y') ?></p>
            <p><strong>Heure de départ :</strong> <?= htmlspecialchars($covoiturage->getHeureDepart()) ?></p>
            <p><strong>Heure d'arrivée estimée :</strong> <?= htmlspecialchars($covoiturage->getHeureArrivee()) ?></p>
            <p><strong>Places restantes :</strong> <?= htmlspecialchars($nbPlacesRestantes) ?></p>
            <p><strong>Coût :</strong> <?= htmlspecialchars($coutCredits) ?> crédits</p>
        </div>

        <hr>

        <!-- CONDUCTEUR -->
        <div class="px-4">
            <h5>Conducteur</h5>
            <p><strong><?= htmlspecialchars($covoiturage->getConducteur()->getNom()) ?></strong></p>
        </div>

        <hr>

        <!-- VEHICULE -->
        <div class="px-4 mb-3">
            <h5>Véhicule</h5>
            <p><?= htmlspecialchars($covoiturage->getVehiculeNom() ?? "Non renseigné") ?>
                (<?= htmlspecialchars($covoiturage->getVehiculeModele() ?? "-") ?>)
            </p>
            <p><strong>Énergie :</strong> <?= htmlspecialchars($covoiturage->getVehiculeEnergie() ?? "-") ?></p>
        </div>

        <!-- MESSAGES -->
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger mx-3"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success mx-3"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <!-- CONFIRMATION -->
        <?php if ($confirmNeeded): ?>
            <div class="alert alert-warning mx-3">
                Vous allez utiliser <?= htmlspecialchars($coutCredits) ?> crédits pour réserver ce trajet. Confirmez-vous ?
                <form method="POST" class="mt-2">
                    <input type="hidden" name="participer" value="1">
                    <button type="submit" name="confirm" value="oui" class="btn btn-success btn-sm">Confirmer</button>
                    <button type="submit" name="confirm" value="non" class="btn btn-secondary btn-sm">Annuler</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- BOUTON RESERVER -->
        <div class="d-flex justify-content-end mt-3 mx-3">
            <?php if ($user && !$confirmNeeded): ?>
                <?php if ($creditsUser >= $coutCredits && $nbPlacesRestantes > 0): ?>
                    <form method="POST" class="w-100">
                        <input type="hidden" name="participer" value="1">
                        <button type="submit" class="btn btn-success w-100">Réserver ce covoiturage</button>
                    </form>
                <?php elseif ($creditsUser < $coutCredits): ?>
                    <button class="btn btn-secondary w-100" disabled>Crédits insuffisants</button>
                <?php elseif ($nbPlacesRestantes <= 0): ?>
                    <button class="btn btn-secondary w-100" disabled>Trajet complet</button>
                <?php endif; ?>
            <?php elseif (!$user): ?>
                <a href="index.php?entity=accueil&action=connexion" class="btn btn-outline-primary w-100">Se connecter pour réserver</a>
            <?php endif; ?>
        </div>

    </div>
</div>
