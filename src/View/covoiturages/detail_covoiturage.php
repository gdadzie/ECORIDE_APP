<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

// 🔹 Guard pour éviter "Undefined variable"
if (!isset($covoiturage) || empty($covoiturage)) {
    echo '<div class="alert alert-danger text-center mt-5">Covoiturage introuvable.</div>';
    return;
}

// Définir la locale en français
setlocale(LC_TIME, 'fr_FR.utf8');

// Créer un objet DateTime à partir de la date et heure
$dateTime = new DateTime($covoiturage['date_depart'] . ' ' . $covoiturage['heure_depart']);

// Obtenir le jour abrégé en majuscule (JEU, LUN, MER...)
$jour = strtoupper($dateTime->format('D')); // format 'D' donne Mon, Tue...
// Convertir les mois anglais en français si besoin
$mois = strtoupper($dateTime->format('M')); // format 'M' donne Jan, Feb...
$jourMois = $dateTime->format('d'); // numéro du jour

// Tableau de correspondance anglais → français pour jours et mois
$joursFR = ['Mon'=>'LUN','Tue'=>'MAR','Wed'=>'MER','Thu'=>'JEU','Fri'=>'VEN','Sat'=>'SAM','Sun'=>'DIM'];
$moisFR  = ['Jan'=>'JAN','Feb'=>'FÉV','Mar'=>'MAR','Apr'=>'AVR','May'=>'MAI','Jun'=>'JUN','Jul'=>'JUL','Aug'=>'AOÛ','Sep'=>'SEP','Oct'=>'OCT','Nov'=>'NOV','Dec'=>'DÉC'];

$jour = $joursFR[$jour] ?? $jour;
$mois = $moisFR[$mois] ?? $mois;

$affichageDate = "$jour. $jourMois $mois";

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #f4fdf6;
        font-family: 'Segoe UI', sans-serif;
    }
    .detail-card {
        max-width: 500px;
        margin: 2rem auto;
        border-radius: 12px;
        border: 1px solid #cce8cc;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .detail-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background-color: #d4f1d4;
    }
    .header-info {
        display: flex;
        flex-direction: column;
    }
    .header-info h5 {
        margin: 0;
        font-weight: 600;
        color: #1b5e20;
    }
    .stars {
        font-size: 0.85rem;
        color: #fbc02d;
    }
    .header-photo img {
        border-radius: 50%;
        width: 60px;
        height: 60px;
        object-fit: cover;
    }
    .detail-card-body {
        padding: 1rem;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .trajet-line {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 1rem 0;
    }
    .trajet-line .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #4caf50;
        margin: 4px 0;
    }
    .trajet-line .dot.red { background-color: #e53935; }
    .trajet-info {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    .trajet-info p {
        margin: 0;
    }
    .eco-tag {
        font-size: 0.75rem;
        font-weight: 300;
        color: #2e7d32;
        margin-left: 0.4rem;
    }
    .detail-card-footer {
        padding: 0.75rem 1rem;
        display: flex;
        justify-content: space-between;
        background-color: #e8f8e8;
        border-top: 1px solid #cce8cc;
    }
    .btn-eco {
        font-size: 0.85rem;
        padding: 0.35rem 0.7rem;
        border-radius: 6px;
    }
    .btn-eco-yellow { background-color: #ffeb3b; color: #333; border: none; }
    .btn-eco-yellow:hover { background-color: #fdd835; }
</style>

<div class="container my-5">
    <h1 class="text-center text-success mb-4 fw-bold"><?= $affichageDate ?> - <?= htmlspecialchars($covoiturage['heure_depart'] ?? '-') ?></h1>

    <div class="detail-card">
        <!-- Header: pseudo, étoiles à gauche / photo à droite -->
        <div class="detail-card-header">
            <div class="header-info">
                <h5><?= htmlspecialchars($covoiturage['pseudo'] ?? 'Conducteur') ?>
                    <?php if($ecologique): ?>
                        <span class="eco-tag">Écologique</span>
                    <?php endif; ?>
                </h5>
                <div class="stars">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i>
                    <i class="bi bi-star"></i>
                    <small class="text-muted">(12 avis)</small>
                </div>
            </div>
            <div class="header-photo">
                <img src="<?= htmlspecialchars($covoiturage['photo_conducteur'] ?? 'https://i.pravatar.cc/60') ?>" alt="Conducteur">
            </div>
        </div>

        <!-- Body: trajet -->
        <div class="detail-card-body">
            <div class="trajet-info">
                <p><i class="bi bi-geo-alt-fill text-success"></i> <?= htmlspecialchars($covoiturage['ville_depart_nom'] ?? '-') ?></p>
                <p><i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($covoiturage['ville_arrivee_nom'] ?? '-') ?></p>
            </div>
            <div class="trajet-line">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot red"></div>
            </div>

            <p><i class="bi bi-people"></i> Passagers disponibles : <?= htmlspecialchars($covoiturage['nb_places'] ?? '-') ?></p>
            <p><i class="bi bi-credit-card"></i> Paiement : <?= htmlspecialchars($covoiturage['mode_paiement'] ?? '-') ?></p>
        </div>

        <!-- Footer: boutons -->
        <div class="detail-card-footer">
            <button onclick="history.back()" class="btn btn-eco btn-eco-yellow">
                <i class="bi bi-arrow-left"></i> Détail
            </button>
            <a href="#" class="btn btn-success btn-eco">
                <i class="bi bi-check-circle"></i> Accepter
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
