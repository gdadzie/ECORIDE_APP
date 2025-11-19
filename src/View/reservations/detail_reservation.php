<?php
/** @var \Entity\Covoiturage $covoiturage */
/** @var \Entity\Utilisateur $user */
/** @var string $errorMessage */
/** @var string $successMessage */
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container my-5">
    <div class="card p-4 shadow-sm" style="max-width:700px; margin:auto;">

        <h4 class="mb-3">Réservation du covoiturage</h4>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <strong>Trajet :</strong> <?= htmlspecialchars($covoiturage->getVilleDepartNom() ?? '-') ?> → <?= htmlspecialchars($covoiturage->getVilleArriveeNom() ?? '-') ?>
        </div>

        <div class="mb-3">
            <strong>Date / Heure :</strong> <?= htmlspecialchars($covoiturage->getDateDepart() . ' ' . $covoiturage->getHeureDepart()) ?>
        </div>

        <div class="mb-3">
            <strong>Places disponibles :</strong> <?= htmlspecialchars($covoiturage->getNbPlaces()) ?>
        </div>

        <div class="mb-3">
            <strong>Prix en crédits :</strong> <?= htmlspecialchars($covoiturage->getPrix()) ?>
        </div>

        <?php if ($covoiturage->getNbPlaces() > 0): ?>
            <form method="POST">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirm" name="confirm" value="oui" required>
                    <label class="form-check-label" for="confirm">
                        Je confirme que je souhaite utiliser <?= htmlspecialchars($covoiturage->getPrix()) ?> crédits pour réserver ce covoiturage
                    </label>
                </div>

                <button type="submit" class="btn btn-success">Confirmer la réservation</button>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">Aucune place disponible pour ce covoiturage.</div>
        <?php endif; ?>

        <div class="mt-3">
            <a href="index.php?entity=covoiturages&action=resultats_recherche" class="btn btn-secondary">Retour aux covoiturages</a>
        </div>
    </div>
</div>

<style>
    .card { border-radius: 0.8rem; }
</style>
