<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/js/covoiturages/formulaire_recherche_covoiturages.js" defer></script>

    <style>
        body {
            background-color: #f5fff5;
            font-family: 'Poppins', sans-serif;
        }
        .card-profile {
            max-width: 600px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .profile-img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto 15px auto;
        }
        .edit-btn {
            background: transparent;
            border: none;
            color: #4caf50;
            font-size: 16px;
            cursor: pointer;
        }
        .edit-btn:hover {
            color: #2e7d32;
        }
        .inline-form {
            margin-top: 8px;
        }
    </style>
</head>

<body>

<div class="container py-5">
    <div class="card-profile">
        <img src="<?= htmlspecialchars($utilisateur->getPhoto() ?: '/uploads/photos/default-avatar.jpg') ?>"
             class="profile-img">

        <h3 class="text-center mb-4 text-success"><?= htmlspecialchars($utilisateur->getPseudo()) ?></h3>

        <!-- NOM -->
        <div class="mb-3">
            <strong>Nom :</strong> <?= htmlspecialchars($utilisateur->getNom()) ?>
            <button class="edit-btn" onclick="toggleEdit('nom')">✏️</button>

            <form id="form-nom" class="inline-form d-none" method="POST"
                  action="index.php?entity=utilisateurs&action=mise_a_jour_profil">
                <input type="text" name="nom" class="form-control form-control-sm mb-2"
                       value="<?= htmlspecialchars($utilisateur->getNom()) ?>" required>
                <button class="btn btn-success btn-sm">Enregistrer</button>
            </form>
        </div>

        <!-- PRENOM -->
        <div class="mb-3">
            <strong>Prénom :</strong> <?= htmlspecialchars($utilisateur->getPrenom()) ?>
            <button class="edit-btn" onclick="toggleEdit('prenom')">✏️</button>

            <form id="form-prenom" class="inline-form d-none" method="POST"
                  action="index.php?entity=utilisateurs&action=mise_a_jour_profil">
                <input type="text" name="prenom" class="form-control form-control-sm mb-2"
                       value="<?= htmlspecialchars($utilisateur->getPrenom()) ?>" required>
                <button class="btn btn-success btn-sm">Enregistrer</button>
            </form>
        </div>

        <!-- TELEPHONE -->
        <div class="mb-3">
            <strong>Téléphone :</strong> <?= htmlspecialchars($utilisateur->getTelephone()) ?>
            <button class="edit-btn" onclick="toggleEdit('telephone')">✏️</button>

            <form id="form-telephone" class="inline-form d-none" method="POST"
                  action="index.php?entity=utilisateurs&action=mise_a_jour_profil">
                <input type="text" name="telephone" class="form-control form-control-sm mb-2"
                       value="<?= htmlspecialchars($utilisateur->getTelephone()) ?>" required>
                <button class="btn btn-success btn-sm">Enregistrer</button>
            </form>
        </div>

        <!-- TYPE UTILISATEUR -->
        <div class="mb-3">
            <strong>Type :</strong> <?= htmlspecialchars($utilisateur->getTypeUtilisateur()) ?>
            <button class="edit-btn" onclick="toggleEdit('type')">✏️</button>

            <form id="form-type" class="inline-form d-none" method="POST"
                  action="index.php?entity=utilisateurs&action=mise_a_jour_profil">
                <select name="type_utilisateur" class="form-select form-select-sm mb-2">
                    <option value="passager"   <?= $utilisateur->getTypeUtilisateur()==='passager'?'selected':'' ?>>Passager</option>
                    <option value="conducteur" <?= $utilisateur->getTypeUtilisateur()==='conducteur'?'selected':'' ?>>Conducteur</option>
                    <option value="PC"         <?= $utilisateur->getTypeUtilisateur()==='PC'?'selected':'' ?>>Passager & Conducteur</option>
                </select>
                <button class="btn btn-success btn-sm">Enregistrer</button>
            </form>
        </div>

        <!-- PHOTO -->
        <div class="mb-3">
            <strong>Photo :</strong>
            <button class="edit-btn" onclick="toggleEdit('photo')">✏️</button>

            <form id="form-photo" class="inline-form d-none" method="POST"
                  enctype="multipart/form-data"
                  action="index.php?entity=utilisateurs&action=mise_a_jour_profil">
                <input type="file" name="photo" class="form-control form-control-sm mb-2" accept="image/*" required>
                <button class="btn btn-success btn-sm">Enregistrer</button>
            </form>
        </div>

    </div>
</div>

<script>
    function toggleEdit(field) {
        document.querySelectorAll(".inline-form").forEach(f => f.classList.add("d-none"));
        document.getElementById("form-" + field).classList.toggle("d-none");
    }
</script>

</body>
</html>
