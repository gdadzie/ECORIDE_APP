<?php

// Inclure le fichier principal du layout (structure générale de la page)
include __DIR__ . '/../layout.php';

// Inclure l’en-tête de la page (menu, logo, barre de navigation, etc.)
include __DIR__ . '/../partials/header.php';

// Initialiser les variables pour éviter les erreurs si elles ne sont pas encore définies
$success = false; // Servira à indiquer si l’envoi du formulaire a réussi ou non
$message = '';    // Contiendra le message de retour (succès ou erreur)
$marques = [];    // Tableau vide pour stocker les marques (si besoin plus tard)

// Vérifier si le formulaire a bien été soumis (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérifier si le champ "nom" n’est pas vide
    if (!empty($_POST['nom'])) {
        // Si le champ est rempli, indiquer que tout s’est bien passé
        $success = true;
        $message = 'Formulaire envoyé avec succès.';
    } else {
        // Sinon, afficher un message d’erreur pour inviter à remplir le champ
        $message = 'Veuillez remplir le champ "nom".';
    }
}
?>
<div class="container py-5">

    <h2 class="text-primary mb-4">⚙️ Mon Profil</h2>

    <!-- Afficher le message de succès ou d'erreur si la variable $message n'est pas vide -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $success ? 'success' : 'danger' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Créer les onglets pour naviguer entre les sections du profil -->
    <ul class="nav nav-tabs" id="profilTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="infos-tab" data-bs-toggle="tab" data-bs-target="#infos" type="button">
                Mes Informations
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="vehicule-tab" data-bs-toggle="tab" data-bs-target="#vehicule" type="button">
                Mes Véhicules
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="covoit-tab" data-bs-toggle="tab" data-bs-target="#covoit" type="button">
                Mes Covoiturages
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button">
                Changer Mot de Passe
            </button>
        </li>
    </ul>

    <!-- Créer le contenu des onglets -->
    <div class="tab-content mt-4">

        <!-- Inclure la section "Mes informations personnelles" -->
        <?php include __DIR__ . '/../utilisateurs/mes_informations_personnelles.php'; ?>

        <!-- Inclure la section pour ajouter des véhicules -->
        <?php include __DIR__ . '/../utilisateurs/ajouter_vehicule.php'; ?>

        <!-- Inclure la section pour gérer les covoiturages du conducteur -->
        <?php include __DIR__ . '/../utilisateurs/covoiturages_conducteur.php'; ?>

        <!-- Inclure la section pour changer le mot de passe -->
        <?php include __DIR__ . '/../utilisateurs/changer_mot_de_passe.php'; ?>

    </div>

</div>

<!-- Inclure jQuery et jQuery UI pour l'autocomplétion -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
    // Préparer les marques pour l'autocomplétion
    var marques = <?= json_encode(array_map(fn($m)=>['label'=>$m['nom_marque'],'id'=>$m['id_marque']], $marques)) ?>;

    // Activer l'autocomplétion sur le champ #nom_marque
    $(function() {
        $('#nom_marque').autocomplete({
            source: marques,      // Définir les suggestions
            minLength: 1,         // Commencer à suggérer après 1 caractère
            select: function(event, ui) { // Mettre à jour les champs quand une marque est sélectionnée
                $('#nom_marque').val(ui.item.label);
                $('#id_marque').val(ui.item.id);
                return false;
            }
        });
    });
</script>

<!-- Inclure les scripts Bootstrap 5 pour gérer les composants JS comme les onglets -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Inclure le pied de page -->
<?php include __DIR__ . '/../partials/footer.php'; ?>
