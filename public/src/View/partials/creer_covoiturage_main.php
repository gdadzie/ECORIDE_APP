<main class="flex-grow-1 container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg p-4">
                <h2 class="text-center mb-4 theme-green">➕ Créer un covoiturage</h2>

                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="index.php" method="post" class="g-3">
                    <input type="hidden" name="entity" value="covoiturages">
                    <input type="hidden" name="action" value="creer_covoiturage">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ville_depart" class="form-label">Ville de départ</label>
                            <input type="text" id="ville_depart" name="ville_depart" class="form-control" placeholder="Paris" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ville_arrivee" class="form-label">Ville d'arrivée</label>
                            <input type="text" id="ville_arrivee" name="ville_arrivee" class="form-control" placeholder="Lyon" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date_depart" class="form-label">Date de départ</label>
                            <input type="date" id="date_depart" name="date_depart" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="heure_depart" class="form-label">Heure de départ</label>
                            <input type="time" id="heure_depart" name="heure_depart" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="distance_km" class="form-label">Distance (km)</label>
                            <input type="number" id="distance_km" name="distance_km" class="form-control" placeholder="200" required>
                        </div>
                        <div class="col-md-4">
                            <label for="nb_places" class="form-label">Nombre de places</label>
                            <input type="number" id="nb_places" name="nb_places" class="form-control" placeholder="3" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" value="1" id="ecologique" name="ecologique">
                                <label class="form-check-label" for="ecologique">
                                    🌱 Écologique
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="prix" class="form-label">Prix (€)</label>
                        <input type="number" id="prix" name="prix" class="form-control" placeholder="25" required>
                    </div>

                    <div class="mb-3">
                        <label for="vehicule" class="form-label">Véhicule</label>
                        <select name="id_vehicule" id="vehicule" class="form-select" required>
                            <option value="">Sélectionner un véhicule</option>
                            <?php foreach ($vehicules as $vehicule): ?>
                                <option value="<?= htmlspecialchars($vehicule['id_vehicule']) ?>">
                                    <?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="duree_minutes" class="form-label">Durée (minutes)</label>
                        <input type="number" id="duree_minutes" name="duree_minutes" class="form-control" placeholder="120" required>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-theme-green w-50">Créer le covoiturage</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
