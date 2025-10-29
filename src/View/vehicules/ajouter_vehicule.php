<?php include __DIR__ . '/../layout.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<?php
$marquesJS = [];
foreach ($marques as $m) {
    $marquesJS[] = [
            'label' => $m['nom_marque'],
            'value' => $m['nom_marque'],
            'id_marque' => $m['id_marque']
    ];
}
?>

<div class="container mt-5">
    <?php if (!empty($message)): ?>
        <div class="alert <?= strpos($message, 'succès') !== false ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nom_marque" class="form-label">Marque</label>
            <input type="text" id="nom_marque" class="form-control" required>
            <input type="hidden" name="marque" id="id_marque">
        </div>

        <div class="col-md-6">
            <label for="modele" class="form-label">Modèle</label>
            <input type="text" name="modele" id="modele" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="couleur" class="form-label">Couleur</label>
            <input type="text" name="couleur" id="couleur" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="energie" class="form-label">Énergie</label>
            <select name="energie" id="energie" class="form-select" required>
                <option value="essence">Essence</option>
                <option value="diesel">Diesel</option>
                <option value="electrique">Électrique</option>
                <option value="hybride">Hybride</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="immatriculation" class="form-label">Immatriculation</label>
            <input type="text" name="immatriculation" id="immatriculation" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="date_immatriculation" class="form-label">Date première immatriculation</label>
            <input type="date" name="date_immatriculation" id="date_immatriculation" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="nb_places" class="form-label">Nombre de places</label>
            <input type="number" name="nb_places" id="nb_places" class="form-control" min="1" max="9" required>
        </div>

        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">Ajouter le véhicule</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>

<!-- jQuery UI pour autocomplétion -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
    $(function() {
        var marques = <?= json_encode($marquesJS) ?>;

        $('#nom_marque').autocomplete({
            source: marques,
            minLength: 1,
            select: function(event, ui) {
                $('#nom_marque').val(ui.item.value);
                $('#id_marque').val(ui.item.id_marque);
                return false;
            }
        });
    });
</script>
