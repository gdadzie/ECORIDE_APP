<!-- View/utilisateurs/index.php -->
<div class="container my-5">
    <h1 class="text-center mb-4">Liste des utilisateurs</h1>

    <?php if (!empty($users)): ?>
        <div class="row justify-content-center">
            <?php foreach ($users as $user): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($user->getPseudo()) ?></h5>
                            <p class="card-text">
                                <?= htmlspecialchars($user->getNom()) ?> <?= htmlspecialchars($user->getPrenom()) ?><br>
                                <?= htmlspecialchars($user->getEmail()) ?><br>
                                Rôle : <?= $user->getRole() ?><br>
                                Type : <?= $user->getTypeCovoiturage() ?><br>
                                Actif : <?= $user->getActif() ? 'Oui' : 'Non' ?>
                            </p>
                            <a href="index.php?action=edit&id=<?= $user->getIdUtilisateur() ?>" class="btn btn-primary btn-sm">Modifier</a>
                            <a href="index.php?action=destroy&id=<?= $user->getIdUtilisateur() ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center">Aucun utilisateur trouvé.</p>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="index.php?action=create" class="btn btn-success">Créer un nouvel utilisateur</a>
    </div>
</div>
