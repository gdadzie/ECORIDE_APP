{% if not utilisateurs %}
<p class="text-danger">Aucun utilisateur à afficher.</p>
{% else %}

{# Calcul des attributs selected avant le HTML #}
{% set selected_role1 = utilisateurs['role'] == 1 ? 'selected' : '' %}
{% set selected_role2 = utilisateurs['role'] == 2 ? 'selected' : '' %}
{% set selected_role3 = utilisateurs['role'] == 3 ? 'selected' : '' %}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Modifier un utilisateur</h1>

    <form method="POST">
        <input type="hidden" id="id_utilisateur" name="id_utilisateur" value="{{ utilisateurs['id_utilisateur'] }}">

        <div class="mb-3">
            <label class="form-label" for="nom">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" value="{{ utilisateurs['nom']|e }}">
        </div>

        <div class="mb-3">
            <label class="form-label" for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" class="form-control" value="{{ utilisateurs['prenom']|e }}">
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ utilisateurs['email']|e }}">
        </div>

        <div class="mb-3">
            <label class="form-label" for="telephone">Téléphone</label>
            <input type="text" id="telephone" name="telephone" class="form-control" value="{{ utilisateurs['telephone']|e }}">
        </div>

        <div class="mb-3">
            <label class="form-label" for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" class="form-control" value="{{ utilisateurs['pseudo']|e }}">
        </div>

        <div class="mb-3">
            <label class="form-label" for="role">Rôle</label>
            <select id="role" name="role" class="form-select">
                {% if utilisateurs['role'] == 1 %}
                <option value="1" selected>Utilisateur</option>
                {% else %}
                <option value="1">Utilisateur</option>
                {% endif %}

                {% if utilisateurs['role'] == 2 %}
                <option value="2" selected>Employé</option>
                {% else %}
                <option value="2">Employé</option>
                {% endif %}

                {% if utilisateurs['role'] == 3 %}
                <option value="3" selected>Administrateur</option>
                {% else %}
                <option value="3">Administrateur</option>
                {% endif %}
            </select>
        </div>


        <div class="mb-3">
            <label class="form-label" for="type_covoiturage">Type de covoiturage</label>
            <input type="text" id="type_covoiturage" name="type_covoiturage" class="form-control" value="{{ utilisateurs['type_covoiturage']|e }}">
        </div>

        <button type="submit" class="btn btn-success">💾 Enregistrer</button>
        <a href="index.html.twig" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>

{% endif %}
