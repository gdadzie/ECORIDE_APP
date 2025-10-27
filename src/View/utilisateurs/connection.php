<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - EcoRide</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
<!-- View: login.php -->
<div class="d-flex justify-content-center align-items-center vh-100 bg-success" >
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 420px; width: 100%; background-color: rgba(255,255,255,0.95);">
        <h2 class="text-center mb-4 fw-light text-success">Connexion EcoRide</h2>

        <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?entity=utilisateurs&action=login">
            <div class="mb-3">
                <label for="login" class="form-label fw-light">Email ou Pseudo</label>
                <input type="text" class="form-control form-control-lg" id="login" name="login" placeholder="Votre email ou pseudo" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-light">Mot de passe</label>
                <input type="password" class="form-control form-control-lg" id="password" name="mot_de_passe" placeholder="Votre mot de passe" required>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-success btn-lg fw-light">Se connecter</button>
            </div>
        </form>

        <div class="text-center">
            <p class="mb-0 fw-light">Pas encore de compte ?
                <a href="http://localhost:8000/index.php?entity=utilisateurs&action=creer_compte" class="text-success fw-semibold">Créer un compte</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
