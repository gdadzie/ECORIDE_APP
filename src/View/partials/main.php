<main class="flex-grow-1 container my-5">
    <div class="row align-items-start">
        <!-- Présentation entreprise -->
        <div class="col-lg-6 mb-4 presentation">
            <div class="p-4">
                <h2 class="mb-3">Présentation de l'entreprise</h2>
                <p>EcoRide vous propose une solution de covoiturage simple, économique et écologique. Rejoignez notre communauté et voyagez responsable !</p>
                <div class="row mt-4">
                    <div class="col-6 mb-3">
                        <img src="assets/images/car1.jpg" class="img-fluid" alt="Covoiturage écologique">
                    </div>
                    <div class="col-6 mb-3">
                        <img src="assets/images/car2.jpg" class="img-fluid" alt="Voyage économique">
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte de recherche -->
        <div class="col-lg-6 mb-4 d-flex justify-content-end">
            <div class="search-card">
                <h3 class="card-title mb-4 text-center">Trouvez un itinéraire</h3>
                <form action="index.php" method="get" class="g-3">
                    <input type="hidden" name="entity" value="utilisateurs">
                    <input type="hidden" name="action" value="search">
                    <div class="mb-3">
                        <input type="text" name="depart" class="form-control" placeholder="Ville de départ" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="arrivee" class="form-control" placeholder="Ville d'arrivée" required>
                    </div>
                    <button type="submit" class="btn btn-theme-green w-100">Rechercher</button>
                </form>
            </div>
        </div>
    </div>
</main>
