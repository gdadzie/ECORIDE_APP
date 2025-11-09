<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des avis à valider</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .header { background-color: #00796B; color: white; padding: 20px; border-radius: 10px 10px 0 0; margin-bottom: 20px; text-align: center; }
        .badge-pending { background-color: #ffb300; }
        .badge-validated { background-color: #4caf50; }
        .badge-refused { background-color: #f44336; }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="header">
        <h2>Avis en attente de validation</h2>
        <p>Liste des avis soumis par les passagers</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Passager</th>
                    <th>Chauffeur</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Date dépôt</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Julie Durand</td>
                    <td>Pierre Martin</td>
                    <td>⭐⭐⭐⭐⭐</td>
                    <td>Super trajet, très ponctuel.</td>
                    <td>05/11/2025</td>
                    <td><span class="badge badge-pending">En attente</span></td>
                    <td>
                        <button class="btn btn-success btn-sm">Valider</button>
                        <button class="btn btn-danger btn-sm">Refuser</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Karim B.</td>
                    <td>Sophie Leroy</td>
                    <td>⭐⭐☆☆☆</td>
                    <td>Beaucoup de retard, trajet stressant.</td>
                    <td>03/11/2025</td>
                    <td><span class="badge badge-pending">En attente</span></td>
                    <td>
                        <button class="btn btn-success btn-sm">Valider</button>
                        <button class="btn btn-danger btn-sm">Refuser</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Emma L.</td>
                    <td>David R.</td>
                    <td>⭐⭐⭐⭐</td>
                    <td>Trajet agréable, conducteur sympathique.</td>
                    <td>04/11/2025</td>
                    <td><span class="badge badge-pending">En attente</span></td>
                    <td>
                        <button class="btn btn-success btn-sm">Valider</button>
                        <button class="btn btn-danger btn-sm">Refuser</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
