<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

// Vérification
if (!isset($conducteur) || empty($conducteur)) {
    echo '<div class="alert alert-danger text-center mt-5">Conducteur introuvable.</div>';
    return;
}

// Vérification avis
if (!isset($avis) || empty($avis)) {
    echo '<div class="alert alert-warning text-center mt-5">Aucun avis pour ce conducteur.</div>';
    return;
}

// Conducteur
$pseudo = $conducteur->getPseudo() ?? 'Utilisateur';
$photoConducteur = $conducteur->getPhoto() ?? '/uploads/photos/default-avatar.jpg';
$nbAvis = count($avis);
$noteMoyenne = $conducteur->getNote() ?? 0;

// Fonction helper pour afficher étoiles
function renderStars(float $note): string {
    $full = floor($note);
    $half = $note - $full >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    return str_repeat('<i class="bi bi-star-fill text-warning"></i>', $full)
        . str_repeat('<i class="bi bi-star-half text-warning"></i>', $half)
        . str_repeat('<i class="bi bi-star text-warning"></i>', $empty);
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container my-5 d-flex justify-content-center">
    <div class="card shadow-sm border-0 h-100" style="max-width:650px;">
        <!-- Bouton Retour -->
        <div class="p-3">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left-circle me-1"></i> Retour
            </a>
        </div>

        <!-- Conducteur -->
        <div class="d-flex align-items-center mb-3 gap-3 p-3 border-bottom">
            <img src="<?= htmlspecialchars($photoConducteur) ?>" class="rounded-circle" width="50" height="50" alt="Avatar <?= htmlspecialchars($pseudo) ?>">
            <div>
                <div class="fw-bold fs-6"><?= htmlspecialchars($pseudo) ?></div>
                <div class="text-muted small">
                    <?= $nbAvis ?> avis • Note moyenne : <?= number_format($noteMoyenne,1) ?>
                    <?= renderStars($noteMoyenne) ?>
                </div>
            </div>
        </div>

        <!-- Liste des avis -->
        <div class="p-3">
            <?php foreach ($avis as $a):
                $auteur = $a->getAuteur(); // objet Utilisateur ou null
                $pseudoAuteur = $auteur?->getPseudo() ?? 'Utilisateur';
                $photoAuteur = $auteur?->getPhoto() ?? '/uploads/photos/default-avatar.jpg';
                $message = $a->getMessage() ?? '';
                $note = $a->getNote() ?? 0;
                $date = $a->getDate() ? (new DateTime($a->getDate()))->format('d/m/Y H:i') : '';
                ?>
                <div class="d-flex mb-3">
                    <img src="<?= htmlspecialchars($photoAuteur) ?>" class="rounded-circle me-3" width="40" height="40" alt="Avatar <?= htmlspecialchars($pseudoAuteur) ?>">
                    <div class="flex-grow-1">
                        <div class="fw-semibold"><?= htmlspecialchars($pseudoAuteur) ?> <?= renderStars($note) ?></div>
                        <div class="text-muted small mb-1"><?= htmlspecialchars($date) ?></div>
                        <div class="text-dark"><?= nl2br(htmlspecialchars($message)) ?></div>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    body { font-family: 'Inter', sans-serif; }
    .card { border-radius: 0.8rem; }
</style>
