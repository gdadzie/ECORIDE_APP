<!-- INFOS PERSONNELLES -->
<div class="tab-pane fade show active" id="infos">
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Pseudo</label>
            <input type="text" name="pseudo" class="form-control" value="<?= htmlspecialchars($user->getPseudo()) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user->getEmail()) ?>" required>
        </div>
        <button type="submit" name="update_info" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
