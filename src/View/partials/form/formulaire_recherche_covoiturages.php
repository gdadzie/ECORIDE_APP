<!-- Formulaire de base, esthétique, avec logique métier intégrée -->
<form id="form-recherche" class="row justify-content-center g-2 search-bar" method="GET" action="resultat_recherches">
    <input type="hidden" name="entity" value="covoiturages">
    <input type="hidden" name="action" value="resultats_recherche">

    <div class="col-md-3">
        <input type="text" name="ville_depart" id="ville_depart" class="form-control" placeholder="Adresse de départ" required>
    </div>
    <div class="col-md-3">
        <input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control" placeholder="Adresse d'arrivée" required>
    </div>
    <div class="col-md-2">
        <input type="date" name="date_depart" class="form-control" id="date_depart" required>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-eco w-100">Rechercher</button>
    </div>
</form>

<script>
    // Définir la date minimale à aujourd'hui
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.getElementById('date_depart');
    dateInput.setAttribute('min', today);

    // Validation supplémentaire lors de la soumission
    $('#form-recherche').on('submit', function(e) {
        if (dateInput.value < today) {
            e.preventDefault(); // Empêche l'envoi du formulaire
            alert('Veuillez sélectionner une date valide (aujourd’hui ou ultérieure).');
        }
    });
</script>