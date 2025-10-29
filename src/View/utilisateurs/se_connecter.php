<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-top: 15px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        button {
            margin-top: 20px;
            padding: 10px;
            width: 100%;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h2>Connexion</h2>

<?php
// Affichage des messages d'erreur ou de succès
if (!empty($message)) {
    echo '<div class="'.($success ?? false ? 'success' : 'error').'">'.$message.'</div>';
}
?>

<form method="post" action="">
    <label for="identifiant">Email ou pseudo :</label>
    <input type="text" name="identifiant" id="identifiant" required placeholder="Votre email ou pseudo">

    <label for="mdp">Mot de passe :</label>
    <input type="password" name="mdp" id="mdp" required placeholder="Votre mot de passe">

    <button type="submit">Se connecter</button>
    <p>Pas encore inscrit ?
        <a href="index.php?entity=utilisateurs&action=creer_compte">Créer un compte !</a>

    </p>
</form>

</body>
</html>
