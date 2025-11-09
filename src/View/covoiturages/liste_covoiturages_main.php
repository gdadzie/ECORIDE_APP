<?php
include __DIR__ . '/../layout.php';
include __DIR__ . '/../partials/header.php';

// 🔹 Guard pour éviter "Undefined variable"
if (!isset($covoiturages)) {
    $covoiturages = [];
}
?>

<style>
    .covoiturages-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .covoiturage-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s ease-in-out;
    }

    .covoiturage-card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #198754;
    }

    .card-header .ville {
        text-transform: capitalize;
    }

    .card-body p {
        margin: 5px 0;
        font-size: 0.95rem;
    }

    .badge-eco {
        display: inline-block;
        background: #d4edda;
        color: #155724;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 8px;
        font-size: 0.85rem;
    }

    .card-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 15px;
    }

    .card-footer a {
        text-decoration: none;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #fff;
    }

    .btn-primary { background-color: #198754; }
    .btn-primary:hover { background-color: #157347; }
    .btn-danger { background-color: #d9534f; }
    .btn-danger:hover { background-color: #c9302c; }

    .page-title {
        text-align: center;
        font-weight: 700;
        margin-top: 30px;
        font-size: 1.8rem;
        color: #198754;
    }
</style>

<div class="container">
    <h2 class="page-title">Mes covoiturages</h2>

    <?php if (!empty($covoiturages)): ?>
        <div class="covoiturages-container">
            <?php foreach ($covoiturages as $c): ?>
                <div class="covoiturage-card">
                    <div class="card-header">
                        <span class="ville"><?= htmlspecialchars($c['ville_depart_nom']) ?></span>
                        <i class="bi bi-arrow-right"></i>
                        <span class="ville"><?= htmlspecialchars($c['ville_arrivee_nom']) ?></span>
                    </div>
                    <div class="card-body">
                        <p><strong>Date :</strong> <?= htmlspecialchars($c['date_depart']) ?> à <?= htmlspecialchars($c['heure_depart']) ?></p>
                        <?php if(!empty($c['duree_minutes'])): ?>
                            <p><strong>Durée :</strong> <?= htmlspecialchars($c['duree_minutes']) ?> min</p>
                        <?php endif; ?>
                        <?php if(!empty($c['distance_km'])): ?>
                            <p><strong>Distance :</strong> <?= htmlspecialchars($c['distance_km']) ?> km</p>
                        <?php endif; ?>
                        <p><strong>Prix :</strong> <?= htmlspecialchars($c['prix']) ?> €</p>
                        <p><strong>Places disponibles :</strong> <?= htmlspecialchars($c['nb_places']) ?></p>
                        <?php if(!empty($c['ecologique'])): ?>
                            <p class="badge-eco">Trajet écologique</p>
                        <?php endif; ?>
                        <p><strong>Statut :</strong> <?= htmlspecialchars($c['statut']) ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="../utilisateurs/index.php?entity=covoiturages&action=edit&id=<?= $c['id_covoiturage'] ?>" class="btn btn-primary">Modifier</a>
                        <a href="../utilisateurs/index.php?entity=covoiturages&action=delete&id=<?= $c['id_covoiturage'] ?>" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce covoiturage ?');">Supprimer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="text-align:center; margin-top:30px;">Aucun covoiturage disponible.</p>
    <?php endif; ?>
</div>


