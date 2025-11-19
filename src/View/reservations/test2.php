<?php
session_start();
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

use Entity\Reservation;

// Vérification covoiturage
if (!isset($covoiturage) || empty($covoiturage)) {
    echo '<div class="alert alert-danger text-center mt-5">Covoiturage introuvable.</div>';
    return;
}

// Vérification des variables nécessaires
if (!isset($creditsController, $covoituragesController, $reservationsRepo)) {
    echo '<div class="alert alert-danger text-center mt-5">
        Erreur : les controllers/repositories nécessaires ne sont pas disponibles.
    </div>';
    return;
}

// Utilisateur connecté
$user = $_SESSION['user'] ?? null;
$creditsUser = $user ? $creditsController->getCredits($user) : 0.0;

// Nombre de places et coût crédits
$nbPlacesRestantes = $covoiturage->getNbPlaces() ?? 0;
$coutCredits = $covoiturage->getPrix() ?? 1;

$errorMessage = '';
$successMessage = '';
$confirmNeeded = false;

// Traitement formulaire réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['participer'])) {
    if (!$user) {
        header('Location: /login.php');
        exit;
    }

    if ($creditsUser < $coutCredits) {
        $errorMessage = "Crédits insuffisants pour réserver ce covoiturage.";
    } elseif ($nbPlacesRestantes <= 0) {
        $errorMessage = "Plus de place disponible pour ce trajet.";
    } else {
        if (isset($_POST['confirm']) && $_POST['confirm'] === 'oui') {
            // Débiter crédits
            $creditsController->updateCredits($user, $creditsUser - $coutCredits);

            // Décrémenter places et changer statut
            $covoituragesController->updatePlacesAndStatut(
                $covoiturage,
                $nbPlacesRestantes - 1,
                'en cours'
            );

            // Créer réservation
            $reservation = new Reservation();
            $reservation->setIdUtilisateur($user->getIdUtilisateur());
            $reservation->setIdCovoiturage($covoiturage->getId());
            $reservation->setDateReservation((new DateTime())->format('Y-m-d H:i:s'));
            $reservation->setStatut('en cours');
            $reservation->setConfirmation($coutCredits);

            $reservationsRepo->save($reservation);

            $successMessage = "Votre réservation a été confirmée ! $coutCredits crédits ont été débités.";
        } else {
            $confirmNeeded = true;
        }
    }
}
?>

<div class="container my-5 d-flex justify-content-center">
    <div class="card shadow-sm border-0 h-100" style="max-width:650px;">
        <div class="p-3">
            <a href="index.php?entity=covoiturages&action=resultats_recherche" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left-circle me-1"></i> Retour
            </a>
        </div>

        <!-- Messages -->
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger mx-3"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success mx-3"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <!-- Confirmation -->
        <?php if ($confirmNeeded): ?>
            <div class="alert alert-warning mx-3">
                Vous allez utiliser <?= $coutCredits ?> crédits pour participer à ce trajet. Confirmez-vous ?
                <form method="POST" class="mt-2">
                    <input type="hidden" name="participer" value="1">
                    <button type="submit" name="confirm" value="oui" class="btn btn-success btn-sm">Confirmer</button>
                    <button type="submit" name="confirm" value="non" class="btn btn-secondary btn-sm">Annuler</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Bouton Réserver -->
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
                <a href="/login.php" class="btn btn-outline-primary w-100">Se connecter pour réserver</a>
            <?php endif; ?>
        </div>
    </div>
</div>
