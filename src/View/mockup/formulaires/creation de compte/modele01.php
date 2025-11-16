<?php include __DIR__ . '/../../../layout.php'; ?>
<?php include __DIR__ . '/../../../partials/header.php'; ?>
<?php if (isset($success) && isset($message) && $message !== ''): ?>
    <div class="mt-5<?= $success ? 'alert-success' : 'alert-danger' ?> alert-error-modern alert-sucess-modern">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<style>
    .alert-success-modern, .alert-error-modern {
        padding: 1rem 1.5rem;
        margin-bottom: 20px;
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .alert-success-modern {
        background-color: #E6F4EA;  /* Vert très clair */
        color: #007F3E;            /* Vert foncé */
        border-left: 5px solid #00C853; /* Ligne gauche verte */
    }

    .alert-error-modern {
        background-color: #FFE6E6;  /* Rouge très clair */
        color: #B00020;             /* Rouge foncé */
        border-left: 5px solid #FF4C4C; /* Ligne gauche rouge */
    }
    body {
        font-family: 'Inter', sans-serif;
        background-color: #FFFDF5;
        margin: 0;
        padding: 20px;
        color: #212121;
    }

    h2 {
        text-align: center;
        color: #00C853;
        margin-bottom: 30px;
    }

    .card {
        max-width: 500px;
        margin: auto;
        padding: 30px;
        background-color: #FFFFFF;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: 500;
        font-size: 0.875rem;
        color: #555;
    }

    input {
        width: 100%;
        padding: 0.75rem 1rem;
        margin-top: 5px;
        box-sizing: border-box;
        border: none;
        border-radius: 12px;
        background-color: #f0f0f0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        transition: 0.3s;
    }

    input:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0,200,83,0.25);
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
        color: #FF4C4C;
        margin-top: 10px;
        font-weight: 500;
    }

    .success {
        color: #66FF99;
        margin-top: 10px;
        font-weight: 500;
    }

    .btn-home {
        display: inline-block;
        margin: 20px auto;
        background-color: #B9F6CA;
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

<body>



<div class="card mt-5">
    <h2 class="mt-2">Créer un compte utilisateur</h2>

    <form method="post" action="">

        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" required minlength="3" maxlength="20" pattern="[a-zA-Z0-9_]+" placeholder="Ex : John_Doe">

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required placeholder="exemple@email.com">

        <label for="mdp">Mot de passe</label>
        <input type="password" name="mdp" id="mdp" required minlength="6" placeholder="6 caractères minimum">

        <button type="submit">S’inscrire</button>
    </form>
</div>

<!-- Bouton vers la page d'accueil -->
<div style="text-align:center;">
    <a href="../../../../../public/index.php" class="btn-home">Retour à l'accueil</a>
</div>

</body>
<?php include __DIR__ . '/../../../partials/footer.php'; ?>
