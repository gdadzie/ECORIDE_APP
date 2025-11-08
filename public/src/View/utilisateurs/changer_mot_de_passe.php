<!-- MOT DE PASSE -->
<div class="tab-pane fade" id="password">
    <form method="POST">
        <input type="password" name="mdp_current" class="form-control mb-2" placeholder="Mot de passe actuel" required>
        <input type="password" name="mdp_new" class="form-control mb-2" placeholder="Nouveau mot de passe" required>
        <input type="password" name="mdp_confirm" class="form-control mb-2" placeholder="Confirmer mot de passe" required>
        <button type="submit" name="update_password" class="btn btn-primary">Changer</button>
    </form>
</div>