<?php include __DIR__ . '/../layout.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4 text-primary fw-bold">
                        🔑 Connexion Utilisateur
                    </h3>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?= $success ?? false ? 'success' : 'danger' ?> text-center fw-semibold">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="needs-validation" novalidate>

                        <!-- Email / Pseudo -->
                        <div class="mb-3">
                            <label for="identifiant" class="form-label fw-semibold">Email ou pseudo</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="identifiant"
                                    name="identifiant"
                                    placeholder="Votre email ou pseudo"
                                    required
                            >
                            <div class="invalid-feedback">Veuillez renseigner votre email ou pseudo.</div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="mdp" class="form-label fw-semibold">Mot de passe</label>
                            <input
                                    type="password"
                                    class="form-control"
                                    id="mdp"
                                    name="mdp"
                                    placeholder="Votre mot de passe"
                                    required
                            >
                            <div class="invalid-feedback">Veuillez entrer votre mot de passe.</div>
                        </div>

                        <!-- Bouton Connexion -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                            </button>
                        </div>

                        <!-- Lien vers inscription -->
                        <p class="text-center mb-0">
                            Pas encore inscrit ?
                            <a href="index.php?entity=utilisateurs&action=creer_compte" class="text-decoration-none fw-semibold">
                                Créer un compte
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Bootstrap 5 validation
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
