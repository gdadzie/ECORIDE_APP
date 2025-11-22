<form id="form-recherche" class="row justify-content-center g-2 search-bar" method="GET" action="resultat_recherches">
    <input type="hidden" name="entity" value="covoiturages">
    <input type="hidden" name="action" value="resultats_recherche">

    <!-- Départ -->
    <div class="col-md-3">
        <div class="autocomplete-container">
            <input type="text" name="ville_depart" id="ville_depart" class="form-control" placeholder="Adresse de départ" required>
            <div class="autocomplete-list list-group"></div>
        </div>
    </div>

    <!-- Arrivée -->
    <div class="col-md-3">
        <div class="autocomplete-container">
            <input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control" placeholder="Adresse d'arrivée" required>
            <div class="autocomplete-list list-group"></div>
        </div>
    </div>

    <!-- Date -->
    <div class="col-md-2">
        <input type="date" name="date_depart" class="form-control" id="date_depart" required>
    </div>


    <!-- Bouton -->
    <div class="col-md-2">
        <button type="submit" class="btn btn-eco w-100">Rechercher</button>
    </div>
    <div id="loader" style="display:none; text-align:center; margin-top:10px;">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>

</form>


<script src="assets/js/covoiturages/formulaire_recherche_covoiturages.js"></script>