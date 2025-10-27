<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - EcoRide</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Créer un utilisateur</h1>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Pseudo</label>
                <input type="text" name="pseudo" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="mdp" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Rôle</label>
                <select name="role" class="form-select">
                    <option value="1">Utilisateur</option>
                    <option value="2">Employé</option>
                    <option value="3">Administrateur</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Type de covoiturage</label>
                <select name="type_covoiturage" class="form-select">
                    <option value="PC">PC</option>
                    <option value="conducteur">Conducteur</option>
                    <option value="passager">Passager</option>
                </select>
            </div>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="actif" class="form-check-input" id="actif">
            <label class="form-check-label" for="actif">Actif</label>
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
        <a  class="btn btn-success" href="http://localhost:8000/index.php">Retour</a>

    </form>
</div>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
