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
        .profil-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0, 100, 0, 0.1);
        }
        h2 {
            color: #2e7d32;
            text-align: center;
            margin-bottom: 25px;
        }
        button {
            background-color: #4caf50;
            border: none;
        }
        button:hover {
            background-color: #388e3c;
        }
        .preview-img {
            display: block;
            max-width: 150px;
            margin: 10px auto;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="profil-container">
    <h2>🌿 Mise à jour du profil</h2>

    <form action="../index.php?entity=utilisateurs&action=mise_a_jour_profil" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom :</label>
            <input type="text" class="form-control" name="nom" id="nom" value="<?= htmlspecialchars($utilisateur->getNom()) ?>" required>
        </div>

        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom :</label>
            <input type="text" class="form-control" name="prenom" id="prenom" value="<?= htmlspecialchars($utilisateur->getPrenom()) ?>" required>
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone :</label>
            <input type="tel" class="form-control" name="telephone" id="telephone" value="<?= htmlspecialchars($utilisateur->getTelephone()) ?>" placeholder="Ex : 0612345678" required>
        </div>

        <div class="mb-3 text-center">
            <label for="photo" class="form-label">Photo de profil :</label>
            <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
            <img id="preview" class="preview-img" src="<?= htmlspecialchars($utilisateur->getPhoto() ?: '/uploads/photos/default-avatar.jpg') ?>" alt="Prévisualisation">
        </div>

        <div class="mb-3">
            <label for="type_utilisateur" class="form-label">Rôle :</label>
            <select class="form-select" name="type_utilisateur" id="type_utilisateur" required>
                <option value="passager" <?= $utilisateur->getTypeUtilisateur() === 'passager' ? 'selected' : '' ?>>Passager</option>
                <option value="conducteur" <?= $utilisateur->getTypeUtilisateur() === 'conducteur' ? 'selected' : '' ?>>Conducteur</option>
                <option value="PC" <?= $utilisateur->getTypeUtilisateur() === 'PC' ? 'selected' : '' ?>>Passager & Conducteur</option>
            </select>
        </div>

        <button type="submit" name="valider" class="btn btn-success w-100">Enregistrer les modifications</button>
    </form>
</div>

<script>
    const photoInput = document.getElementById('photo');
    const previewImg = document.getElementById('preview');

    photoInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if(file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>

</body>
</html>
