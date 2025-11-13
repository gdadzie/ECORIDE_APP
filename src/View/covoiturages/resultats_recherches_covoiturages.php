<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

// 🔹 Sécurité
if (!isset($covoiturages)) $covoiturages = [];
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #f4fdf6;
        font-family: 'Segoe UI', sans-serif;
    }

    /* === FORMULAIRE DE RECHERCHE === */
    .search-box {
        background: #ffffff;
        border: 1px solid #cce8cc;
        border-radius: 12px;
        padding: 1.2rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        margin-bottom: 2.5rem;
    }

    .search-box h5 {
        color: #2e7d32;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .search-box .form-control, .search-box .form-select {
        border-radius: 8px;
        border: 1px solid #cce8cc;
        font-size: 0.9rem;
    }

    .search-box .btn-search {
        background-color: #43a047;
        color: white;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .search-box .btn-search:hover {
        background-color: #2e7d32;
    }

    /* === CARDS DE RESULTATS === */
    .eco-card {
        border-radius: 10px;
        border: 1px solid #cce8cc;
        background: #fff;
        box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        transition: all 0.2s ease;
    }

    .eco-card:hover { transform: translateY(-3px); }

    .eco-card-header {
        background-color: #e8f8e8;
        border-bottom: 1px solid #cce8cc;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        padding: 0.6rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        color: #2e7d32;
        font-weight: 500;
    }

    .eco-card-body {
        display: flex;
        align-items: center;
        padding: 0.9rem 1rem;
    }

    .eco-card-body img {
        border-radius: 50%;
        width: 55px;
        height: 55px;
        object-fit: cover;
        margin-right: 0.8rem;
    }

    .eco-card-body .info { flex: 1; }

    .eco-card-body .info h6 {
        margin: 0;
        font-weight: 600;
        color: #1b5e20;
    }

    .eco-card-body .stars {
        color: #fbc02d;
        font-size: 0.85rem;
    }

    .eco-card-body .car-model {
        font-size: 0.85rem;
        color: #555;
    }

    .eco-tag {
        font-size: 0.75rem;
        color: #2e7d32;
        font-weight: 300;
        margin-left: 0.4rem;
    }

    .eco-card-footer {
        background-color: #f5fbf5;
        border-top: 1px solid #cce8cc;
        padding: 0.5rem 0.8rem;
        display: flex;
        justify-content: space-between;
    }

    .btn-eco {
        border-radius: 6px;
        font-size: 0.85rem;
        padding: 0.35rem 0.7rem;
    }

    .btn-eco-yellow {
        background-color: #ffeb3b;
        color: #333;
        border: none;
    }

    .btn-eco-yellow:hover { background-color: #fdd835; }
</style>

<div class="container my-5">
    <!-- 🔹 FORMULAIRE DE RECHERCHE INLINE -->
    <div class="search-box">
        <h5><i class="bi bi-search"></i> Nouvelle recherche de covoiturage</h5>
        <form method="GET" action="index.php" class="row g-3 align-items-end">
            <input type="hidden" name="entity" value="covoiturages">
            <input type="hidden" name="action" value="resultats_recherche">

            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-geo-alt-fill text-success"></i> Départ</label>
                <input type="text" name="ville_depart" class="form-control" placeholder="Ex : Paris" required>
            </div>

            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-flag-fill text-success"></i> Arrivée</label>
                <input type="text" name="ville_arrivee" class="form-control" placeholder="Ex : Lyon" required>
            </div>

            <div class="col-md-2">
                <label class="form-label"><i class="bi bi-calendar-date text-success"></i> Date</label>
                <input type="date" name="date_depart" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label class="form-label"><i class="bi bi-clock text-success"></i> Heure</label>
                <input type="time" name="heure_depart" class="form-control">
            </div>

            <div class="col-md-1">
                <label class="form-label"><i class="bi bi-people-fill text-success"></i> Passagers</label>
                <select name="nb_passagers" class="form-select">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>

            <div class="col-md-1 text-center">
                <button type="submit" class="btn btn-search w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- 🔹 TITRE RESULTATS -->
    <h2 class="text-center mb-4 text-success fw-bold">🚗 Résultats de votre recherche</h2>

    <?php if (!empty($covoiturages)): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($covoiturages as $c):
                if ($c['nb_places'] < 1) continue;
                $ecologique = (int)$c['ecologique'] === 1;

                // Affichage "Maintenant" si l'heure de départ = heure actuelle
                $heureActuelle = date('H:i');
                $afficherHeure = ($c['heure_depart'] ?? '') === $heureActuelle ? 'Maintenant' : ($c['heure_depart'] ?? '-');
                ?>
                <div class="col">
                    <div class="eco-card">
                        <div class="eco-card-header">
                            <div><i class="bi bi-clock"></i> <?= htmlspecialchars($afficherHeure) ?></div>
                            <div><i class="bi bi-credit-card"></i> <?= htmlspecialchars($c['mode_paiement'] ?? 'Carte / Cash') ?> •
                                <strong><?= htmlspecialchars($c['prix'] ?? '-') ?>€</strong>
                            </div>
                        </div>

                        <div class="eco-card-body">
                            <img src="https://i.pravatar.cc/60?u=<?= $c['id_covoiturage'] ?>" alt="Conducteur">
                            <div class="info">
                                <h6>
                                    <?= htmlspecialchars($c['pseudo'] ?? 'Conducteur') ?>
                                    <?php if ($ecologique): ?>
                                        <span class="eco-tag">Écologique</span>
                                    <?php endif; ?>
                                </h6>
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                    <i class="bi bi-star"></i>
                                    <small class="text-muted">(12 avis)</small>
                                </div>
                                <div class="car-model"><i class="bi bi-car-front"></i> <?= htmlspecialchars($c['modele_voiture'] ?? 'Renault Zoe') ?></div>
                            </div>
                        </div>

                        <div class="eco-card-footer">
                            <a href="index.php?entity=covoiturages&action=detail_covoiturage&id=<?= $c['id_covoiturage'] ?>" class="btn btn-eco btn-eco-yellow">
                                <i class="bi bi-eye"></i> Détail
                            </a>
                            <a href="#" class="btn btn-success btn-eco">
                                <i class="bi bi-check-circle"></i> Accepter
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center mt-4">
            😕 Aucun covoiturage trouvé pour cette recherche.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
