<!-- formulaire_recherche_covoiturages.php -->

<head>
    <title></title>
    <link rel="stylesheet" href="assets/css/covoiturage/covoiturages.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<form id="form-recherche" method="POST" action="index.php?entity=covoiturages&action=recherche_covoiturages" class="search-form formulaire-recherche">
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Ville de départ</label>
            <label for="ville_depart"></label><input type="text" name="ville_depart" id="ville_depart" class="form-control" placeholder="Ex : Paris" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Ville d'arrivée</label>
            <label for="ville_arrivee"></label><input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control" placeholder="Ex : Lyon" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <label>
                <input type="date" name="date_depart" class="form-control" required>
            </label>
        </div>
        <div class="col-md-3">
            <label class="form-label">Heure (optionnel)</label>
            <label>
                <input type="time" name="heure_depart" class="form-control">
            </label>
        </div>
    </div>

    <div class="text-center mt-4">
        <button type="submit" class="btn btn-search me-2">
            <i class="bi bi-search"></i> Rechercher
        </button>
        <button type="button" onclick="window.history.back()" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </button>
    </div>
</form>

<!-- Loader -->
<div id="loader" class="text-center my-3" style="display:none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Chargement...</span>
    </div>
    <p>Recherche en cours...</p>
</div>

<!-- Conteneur des résultats -->
<div id="resultats" class="mt-5"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/covoiturages/formulaire_recherche_covoiturages.js"></script>
