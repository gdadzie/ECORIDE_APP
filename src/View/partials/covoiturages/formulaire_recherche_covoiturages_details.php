<head>
    <title>Recherche covoiturages</title>
    <link rel="stylesheet" href="assets/css/covoiturage/covoiturages.css">
    <link rel="stylesheet" href="assets/css/covoiturage/formulaire_recherche_covoiturages.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<form id="form-recherche" method="GET" action="index.php" class="search-form formulaire-recherche">
    <!-- Champs cachés pour le routeur -->
    <input type="hidden" name="entity" value="covoiturages">
    <input type="hidden" name="action" value="resultats_recherche">

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Ville de départ</label>
            <input type="text" name="ville_depart" id="ville_depart" class="form-control" placeholder="Ex : Paris">
        </div>
        <div class="col-md-3">
            <label class="form-label">Ville d'arrivée</label>
            <input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control" placeholder="Ex : Lyon">
        </div>
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="date_depart" class="form-control">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-search me-2">
                <i class="bi bi-search"></i> Rechercher
            </button>
        </div>
    </div>
</form>

<script src="assets/js/covoiturages/formulaire_recherche_covoiturages.js"></script>
