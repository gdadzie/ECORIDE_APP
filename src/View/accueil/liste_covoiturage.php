<?php include __DIR__ . '/../layout.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-5">
    <h3 class="mb-4 text-center" style="color: #198754; font-weight: 700;">Liste des Covoiturages</h3>

    <?php if (!empty($covoiturages)): ?>
        <form id="covoituragesForm" method="get" action="index.php">
            <input type="hidden" name="entity" value="covoiturages">
            <input type="hidden" name="action" value="supprimer_covoiturage_multiple">

            <div class="table-responsive shadow-sm p-3 mb-5 bg-white rounded">
                <table id="covoituragesTable" class="table table-hover align-middle text-center">
                    <thead style="background-color: #198754; color: #FFFFFF;">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Places</th>
                        <th>Écologique</th>
                        <th>Prix (€)</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($covoiturages as $index => $c): ?>
                        <tr>
                            <td><input type="checkbox" class="select-row" name="ids[]" value="<?= $c['id_covoiturage'] ?>"></td>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($c['ville_depart_nom'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['ville_arrivee_nom'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['date_depart'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['heure_depart'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['nb_places'] ?? '0') ?></td>
                            <td>
                                <?php if(!empty($c['ecologique'])): ?>
                                    <span class="badge rounded-pill" style="background-color: #d4edda; color: #155724;">Oui</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill" style="background-color: #f8d7da; color: #721c24;">Non</span>
                                <?php endif; ?>
                            </td>
                            <td><?= number_format((float)($c['prix'] ?? 0), 2) ?></td>
                            <td><?= htmlspecialchars($c['statut'] ?? '') ?></td>
                            <td>
                                <div class="d-flex justify-content-center flex-wrap gap-1">
                                    <a href="index.php?entity=covoiturages&action=edit&id=<?= $c['id_covoiturage'] ?>"
                                       class="btn btn-sm" style="background-color: #198754; color: #FFFFFF; min-width: 80px;">Modifier</a>
                                    <a href="index.php?entity=covoiturages&action=delete&id=<?= $c['id_covoiturage'] ?>"
                                       class="btn btn-sm btn-outline-danger" style="min-width: 80px;"
                                       onclick="return confirm('Voulez-vous vraiment supprimer ce covoiturage ?')">Supprimer</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-info text-center" style="background-color: #d1ecf1; color: #0c5460;">
            Aucun covoiturage disponible.
        </div>
    <?php endif; ?>
</div>

<style>
    #covoituragesTable thead th { text-align: center; font-weight: 700; }
    #covoituragesTable tbody tr:hover { background-color: #e2f0d9; transition: background 0.3s; }
    .badge { font-weight: 500; font-size: 0.85rem; }
    .selected-row { background-color: #c8e6c9 !important; }
</style>

<script>
    $(document).ready(function() {
        // Détruire si déjà initialisée
        if ($.fn.DataTable.isDataTable('#covoituragesTable')) {
            $('#covoituragesTable').DataTable().destroy();
        }

        var table = $('#covoituragesTable').DataTable({
            paging: true,
            pageLength: 10,
            lengthChange: false,
            ordering: true,
            order: [[2, 'asc']],
            info: true,
            autoWidth: false,
            language: {
                search: "",
                paginate: { previous: "Précédent", next: "Suivant" },
                info: "Affichage de _START_ à _END_ sur _TOTAL_ covoiturages",
                infoEmpty: "Aucun covoiturage à afficher",
                zeroRecords: "Aucun résultat trouvé"
            }
        });

        // Checkbox "Tout sélectionner"
        $('#select-all').on('click', function() {
            $('.select-row').prop('checked', this.checked).trigger('change');
        });

        // Sélection individuelle
        $('.select-row').on('change', function() {
            $(this).closest('tr').toggleClass('selected-row', this.checked);
        });
    });
</script>
