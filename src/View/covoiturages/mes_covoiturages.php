<?php
// $user et $covoiturages doivent être définis depuis le contrôleur
$user = $user ?? null;
$covoiturages = $covoiturages ?? [];

$photoPathWeb = ($user && method_exists($user, 'getPhoto') && $user->getPhoto())
        ? $user->getPhoto()
        : '/uploads/photos/default-avatar.jpg';
$userPseudo = ($user && method_exists($user, 'getPseudo'))
        ? $user->getPseudo()
        : 'Utilisateur';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Covoiturages</title>
    <link href="/assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f8f6;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 15px;
        }

        .dashboard-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 40px;
        }

        .dashboard-header h2 {
            color: #198754;
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .user-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #198754;
            margin-bottom: 10px;
        }

        .card-covoiturage {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            background-color: #fff;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-covoiturage:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .card-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .card-covoiturage h5 {
            font-weight: 600;
            color: #1b5e20;
            margin-bottom: 10px;
        }

        .card-covoiturage p {
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 5px;
        }

        .card-actions {
            margin-top: auto;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-eco { background-color: #4caf50; color: #fff; border-radius: 6px; border: none; padding: 8px 14px; font-size: 0.9rem; text-decoration: none; text-align: center; transition: background-color 0.2s, transform 0.2s; }
        .btn-eco:hover { background-color: #388e3c; transform: translateY(-1px); }

        .btn-modifier { background-color: #ffb74d; color: #212529; border-radius: 6px; padding: 8px 14px; font-size: 0.9rem; text-decoration: none; text-align: center; border: none; transition: background-color 0.2s, transform 0.2s; }
        .btn-modifier:hover { background-color: #fb8c00; transform: translateY(-1px); }

        .btn-supprimer { background-color: #e57373; color: #fff; border-radius: 6px; padding: 8px 14px; font-size: 0.9rem; text-decoration: none; text-align: center; border: none; transition: background-color 0.2s, transform 0.2s; }
        .btn-supprimer:hover { background-color: #d32f2f; transform: translateY(-1px); }

        .btn-eco, .btn-modifier, .btn-supprimer { display: inline-block; cursor: pointer; line-height: 1.5; }
        .btn-eco:focus, .btn-modifier:focus, .btn-supprimer:focus { outline: none; box-shadow: 0 0 0 3px rgba(76,175,80,0.4); }

        .row-cards { display: flex; flex-wrap: wrap; gap: 20px; }
        .col-card { flex: 1 1 100%; }
        @media (min-width: 576px) { .col-card { flex: 1 1 calc(50% - 20px); } }
        @media (min-width: 992px) { .col-card { flex: 1 1 calc(33.333% - 20px); } }
        @media (min-width: 1400px) { .col-card { flex: 1 1 calc(25% - 20px); } }
    </style>
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container dashboard-container">

    <div class="dashboard-header">
        <img src="<?= htmlspecialchars($photoPathWeb) ?>" alt="Photo de profil" class="user-photo">
        <h2>Mes covoiturages</h2>
    </div>

    <div class="row-cards">
        <?php if (!empty($covoiturages)): ?>
            <?php foreach ($covoiturages as $c): ?>
                <div class="col-card d-flex">
                    <div class="card card-covoiturage flex-fill d-flex flex-column">
                        <div class="card-body d-flex flex-column">
                            <h5><?= htmlspecialchars($c->getVilleDepartNom() ?? 'Départ') ?> → <?= htmlspecialchars($c->getVilleArriveeNom() ?? 'Arrivée') ?></h5>

                            <p><strong>Date :</strong> <?= htmlspecialchars($c->getDateDepart() ?? '—') ?> à <?= htmlspecialchars($c->getHeureDepart() ?? '—') ?></p>
                            <p><strong>Arrivée :</strong> <?= htmlspecialchars($c->getHeureArrivee() ?? '—') ?></p>
                            <p><strong>Places :</strong> <?= htmlspecialchars($c->getNbPlaces() ?? 0) ?></p>
                            <p><strong>Prix :</strong> <?= htmlspecialchars($c->getPrix() ?? 0) ?> €</p>
                            <p><strong>Durée :</strong> <?= htmlspecialchars($c->getDureeMinutes() ?? 0) ?> min</p>
                            <p><strong>Statut :</strong> <?= htmlspecialchars($c->getStatut() ?? '—') ?></p>

                            <div class="card-actions mt-auto">
                                <a href="index.php?entity=covoiturages&action=detail_covoiturage&id=<?= (int)$c->getIdCovoiturage() ?>" class="btn-eco">Détails</a>
                                <a href="index.php?entity=covoiturages&action=modifier_covoiturage&id=<?= (int)$c->getIdCovoiturage() ?>" class="btn-modifier">Modifier</a>
                                <a href="index.php?entity=covoiturages&action=supprimer&id=<?= (int)$c->getIdCovoiturage() ?>" class="btn-supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce covoiturage ?');">Supprimer</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">Vous n'avez encore créé aucun covoiturage.</p>
        <?php endif; ?>
    </div>
</div>

<script src="/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>gi