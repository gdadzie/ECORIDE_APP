<?php

// =======================================
// Configuration des erreurs
// =======================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// =======================================
// Autoload + classes nécessaires
// =======================================
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../src/Entity/Utilisateur.php';
require_once __DIR__ . '/../src/Entity/Covoiturage.php';
require_once __DIR__ . '/../src/Entity/Ville.php';

// =======================================
// Session sécurisée
// =======================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupère l'utilisateur s'il est connecté
$user = $_SESSION['user'] ?? null;

// ✅ Correction ligne 20 : on utilise type_utilisateur, pas type_covoiturage
if ($user instanceof \Entity\Utilisateur) {
    // on vérifie que la méthode existe avant de l’appeler
    if (method_exists($user, 'setTypeUtilisateur')) {
        $user->setTypeUtilisateur('passager');
        $_SESSION['user'] = $user;
    }
}

// =======================================
// Connexion BDD
// =======================================
use Config\Database;

$conn = Database::getConnection();
if (!$conn) {
    $error = Database::getLastError();
    require_once __DIR__ . '/../src/View/erreur/erreur_connexion.php';
    exit;
}

// =======================================
// Repositories
// =======================================
use Repository\UtilisateursRepository;
use Repository\CovoituragesRepository;
use Repository\VehiculesRepository;
use Repository\VillesRepository;
use Repository\AvisRepository;

$utilisateursRepo = new UtilisateursRepository($conn);
$covoituragesRepo = new CovoituragesRepository($conn);
$vehiculesRepo    = new VehiculesRepository($conn);
$villesRepo       = new VillesRepository($conn);
$avisRepo         = new AvisRepository($conn);

// =======================================
// Controllers
// =======================================
use Controller\UtilisateursController;
use Controller\CovoituragesController;
use Controller\AccueilController;
use Controller\VehiculesController;
use Controller\VillesController;
use Controller\AvisController;

$utilisateursController  = new UtilisateursController($utilisateursRepo, $vehiculesRepo);
$covoituragesController  = new CovoituragesController($covoituragesRepo, $utilisateursRepo, $vehiculesRepo, $villesRepo);
$vehiculesController     = new VehiculesController($vehiculesRepo);
$villesController        = new VillesController();
$accueilController       = new AccueilController();
$avisController          = new AvisController();

// =======================================
// Routage principal
// =======================================
$entity = $_GET['entity'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

switch ($entity) {

    case 'utilisateurs':
        switch ($action) {
            case 'creer_compte': $utilisateursController->register(); break;
            case 'tableau_de_bord': $utilisateursController->dashboard(); break;
            case 'se_connecter': $utilisateursController->login(); break;
            case 'deconnexion': $utilisateursController->logout(); break;
            case 'liste_utilisateurs': $utilisateursController->liste(); break;
            case 'supprimer': $utilisateursController->supprimer(); break;
            case 'charte_graphique': $utilisateursController->charteGraphique(); break;
            case 'mon_profil': $utilisateursController->profilUser(); break;
            case 'mise_a_jour_profil': $utilisateursController->updateProfilUtilisateur(); break;

            case 'profil_passager': $utilisateursController->profilPassager(); break;
            case 'profil_conducteur': $utilisateursController->profilConducteur(); break;
            case 'espace_employe': $utilisateursController->espaceEmploye(); break;
            case 'espace_admin': $utilisateursController->espaceAdmin(); break;
            default: $utilisateursController->dashboard(); break;
        }
        break;

    case 'covoiturages':
        switch ($action) {
            case 'creer_covoiturage': $covoituragesController->createCovoiturage(); break;
            case 'recherche_covoiturages': $covoituragesController->rechercheLarge(); break;
            case 'auto_completion': $covoituragesController->autocompleteVilles(); break;
            case 'resultats_recherche': $covoituragesController->resultatsRecherche(); break;
            case 'detail_covoiturage': $covoituragesController->showDetails(); break;
            case 'liste_covoiturages_ecologique': $covoituragesController->listeCovoituragesByEcologique(1); break;
            case 'formulaire_recherche_covoiturages': $covoituragesController->formRechercheCovoiturages(); break;
            default: $covoituragesController->rechercheLarge(); break;
        }
        break;

    case 'vehicules':
        switch ($action) {
            case 'ajouter_vehicule': $vehiculesController->ajouter(); break;
            case 'supprimer_vehicule': $vehiculesController->delete(); break;
            case 'liste_vehicules': $vehiculesController->showVehicule(); break;
            case 'supprimer_vehicule_multiple': $vehiculesController->deleteMultiple(); break;
            default: $vehiculesController->showVehicule(); break;
        }
        break;

    case 'villes':
        switch ($action) {
            case 'liste_des_villes': $villesController->show(); break;
            default: $villesController->show(); break;
        }
        break;

    case 'avis':
        switch ($action) {
            case 'avis': $avisController->showAvis(); break;
            default: $avisController->showAvis(); break;
        }
        break;

    case 'accueil':
    default:
        switch ($action) {
            case 'index': $accueilController->index(); break;
            case 'covoiturages': $accueilController->covoiturage(); break;
            case 'contact': $accueilController->contact(); break;
            case 'connexion': $accueilController->pageConnexion(); break;
            case 'creer_compte': $accueilController->register(); break;
            case 'logout': $accueilController->logout(); break;
            case 'mentions_legales': $accueilController->mentionsLegales(); break;
            case 'dashboard': $accueilController->grace(); break;
            default: $accueilController->index(); break;
        }
        break;
}

?>
