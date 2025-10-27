{% extends 'layout.html.twig' %}

{% block title %}Inscription | EcoRide{% endblock %}

{% block content %}
    <h2>Inscription</h2>

    {% if error %}
        <p style="color:red;">{{ error }}</p>
    {% endif %}

    <form action="" method="POST">
        <input type="text" name="pseudo" placeholder="Pseudo" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br><br>
        <button type="submit">S’inscrire</button>
    </form>
{% endblock %}
