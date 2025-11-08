<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - EcoRide</title>

    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- CSS perso -->
    <link rel="stylesheet" href="assets/css/header/header.css">
    <link rel="stylesheet" href="assets/css/main/main.css">
    <link rel="stylesheet" href="assets/css/footer/header.css">
    <link rel="stylesheet" href="assets/css/theme/theme.css">

    <!-- DataTables CSS Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">



<!-- MAIN -->
<main class="flex-grow-1">
    <?php /* Ton contenu dynamique, ex: liste_vehicules.php */ ?>
</main>



<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/main.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Initialisation DataTables -->
<script>
    $(document).ready(function() {
        $('#vehiculesTable').DataTable({
            paging: true,
            pageLength: 10,
            lengthChange: false,
            ordering: true,
            order: [[1, 'asc']], // Tri par Marque par défaut
            info: true,
            autoWidth: false,
            language: {
                search: "Recherche :",
                paginate: {
                    previous: "Précédent",
                    next: "Suivant"
                },
                info: "Affichage de _START_ à _END_ sur _TOTAL_ véhicules",
                infoEmpty: "Aucun véhicule à afficher",
                zeroRecords: "Aucun résultat trouvé"
            }
        });
    });
</script>
</body>
</html>
