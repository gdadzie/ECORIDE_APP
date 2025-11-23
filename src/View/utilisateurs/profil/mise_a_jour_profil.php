<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour profil - EcoRide</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5fff5;
            font-family: 'Poppins', sans-serif;
        }
        .card-user, .card-form {
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0, 100, 0, 0.1);
            background: white;
        }
        .card-form {
            max-width: 350px;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
            margin: 0 auto 15px auto;
        }
        .btn-modifier {
            background: #4caf50;
            color: white;
            border-radius: 10px;
        }
        .btn-modifier:hover {
            background: #388e3c;
        }
    </style>
</head>

<body>
<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="container py-5">
    <h1 class="text-center mb-5">Mon profil</h1>
    <div class="row g-4">
        <!-- ==================== CARTE INFO UTILISATEUR ==================== -->
        <div class="col-md-7">
            <div class="card-user">

                <img src="<?= htmlspecialchars($utilisateur->getPhoto() ?: '/uploads/photos/default-avatar.jpg') ?>"
                     class="profile-img" alt="Photo utilisateur">

                <h3 class="text-center text-success mb-3"><?= htmlspecialchars($utilisateur->getPseudo()) ?></h3>

                <p><strong>Nom :</strong> <?= htmlspecialchars($utilisateur->getNom()) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($utilisateur->getPrenom()) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($utilisateur->getEmail()) ?></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($utilisateur->getTelephone()) ?></p>
                <p><strong>Type utilisateur :</strong> <?= htmlspecialchars($utilisateur->getTypeUtilisateur()) ?></p>

                <div class="text-center mt-3">
                    <button class="btn btn-modifier" id="btnModifier">Modifier le profil</button>
                </div>

            </div>
        </div>

        <!-- ==================== CARTE FORMULAIRE (MASQUÉE AU DÉBUT) ==================== -->
        <div class="col-md-5 d-none" id="carteFormulaire">
            <div class="card-form">

                <h4 class="text-center text-success mb-3">Mettre à jour</h4>

                <form action="../index.php?entity=utilisateurs&action=mise_a_jour_profil"
                      method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label">Nom :</label>
                        <input type="text" class="form-control" name="nom"
                               value="<?= htmlspecialchars($utilisateur->getNom()) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prénom :</label>
                        <input type="text" class="form-control" name="prenom"
                               value="<?= htmlspecialchars($utilisateur->getPrenom()) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Téléphone :</label>
                        <input type="tel" class="form-control" name="telephone"
                               value="<?= htmlspecialchars($utilisateur->getTelephone()) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Photo :</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type utilisateur :</label>
                        <select class="form-select" name="type_utilisateur">
                            <option value="passager" <?= $utilisateur->getTypeUtilisateur() === 'passager' ? 'selected' : '' ?>>Passager</option>
                            <option value="conducteur" <?= $utilisateur->getTypeUtilisateur() === 'conducteur' ? 'selected' : '' ?>>Conducteur</option>
                            <option value="PC" <?= $utilisateur->getTypeUtilisateur() === 'PC' ? 'selected' : '' ?>>Passager & Conducteur</option>
                        </select>
                    </div>

                    <button class="btn btn-success w-100">Enregistrer</button>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById("btnModifier").addEventListener("click", function() {
        document.getElementById("carteFormulaire").classList.remove("d-none");
        this.style.display = "none";
    });
</script>

</body>
</html>
