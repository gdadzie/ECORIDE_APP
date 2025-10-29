<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Utilisateur - EcoRide</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FFFDF5; /* Fond principal charte */
            margin: 0;
            padding: 20px;
            color: #212121; /* Texte principal */
        }

        h2 {
            text-align: center;
            color: #00C853; /* Vert émeraude */
            margin-bottom: 30px;
        }

        form {
            max-width: 400px;
            margin: auto;
            padding: 30px;
            background-color: #FFFFFF; /* Fond formulaire */
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #E0E0E0; /* Bordure charte */
            border-radius: 8px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #00C853;
            outline: none;
            box-shadow: 0 0 5px rgba(0,200,83,0.4);
        }

        button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            background-color: #00C853;
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #66FF99;
            color: #212121;
        }

        .error {
            color: #FF4C4C; /* Rouge léger pour erreurs */
            margin-top: 10px;
            font-weight: 500;
        }

        .success {
            color: #66FF99; /* Vert clair */
            margin-top: 10px;
            font-weight: 500;
        }

        .btn-home {
            display: inline-block;
            margin: 20px auto;
            background-color: #B9F6CA; /* Vert pastel */
            color: #212121;
            padding: 10px 20px;
            border-radius: 12px;
            text-decoration: none;
            text-align: center;
            transition: 0.3s;
        }

        .btn-home:hover {
            background-color: #00C853;
            color: #FFFFFF;
        }

    </style>
</head>
<body>

<h2>Créer un compte utilisateur</h2>

<?php
// Affichage des messages d'erreur ou de succès
if (!empty($message)) {
    echo '<div class="'.($success ? 'success' : 'error').'">'.$message.'</div>';
}
?>

<form method="post" action="index.php?entity=utilisateurs&action=creer_compte">
    <label for="pseudo">Pseudo :</label>
    <input type="text" name="pseudo" id="pseudo" required minlength="3" maxlength="20" pattern="[a-zA-Z0-9_]+" placeholder="Ex : John_Doe">

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required placeholder="exemple@email.com">

    <label for="mdp">Mot de passe :</label>
    <input type="password" name="mdp" id="mdp" required minlength="6" placeholder="6 caractères minimum">

    <button type="submit">S’inscrire</button>
</form>

<!-- Bouton vers la page d'accueil -->
<div style="text-align:center;">
    <a href="index.php" class="btn-home">Retour à l'accueil</a>
</div>

</body>
</html>
