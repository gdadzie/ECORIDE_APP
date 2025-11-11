<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../src/Entity/Utilisateur.php';
require_once __DIR__ . '/../src/Entity/Covoiturage.php';
require_once __DIR__ . '/../src/Entity/Ville.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//PDO
use Config\Database;

//REPOSITORY
use Controller\AvisController;
use Repository\AvisRepository;
use Repository\UtilisateursRepository;
use Repository\CovoituragesRepository;
use Repository\VehiculesRepository;
use Repository\VillesRepository;

//CONTROLLER
use Controller\UtilisateursController;
use Controller\CovoituragesController;
use Controller\AccueilController;
use Controller\VehiculesController;
use Controller\VillesController;

// =======================================
// Connexion à la base
// =======================================
$conn = Database::getConnection();
if (!$conn) {
    $error = Database::getLastError();
    require_once __DIR__ . '/../src/View/erreur/erreur_connexion.php';
    exit;
}

// =======================================
// Instanciation des repositories
// =======================================
$utilisateursRepo = new UtilisateursRepository($conn);
$covoituragesRepo = new CovoituragesRepository($conn);
$vehiculesRepo     = new VehiculesRepository($conn);
$villesRepo       = new VillesRepository($conn);
$AvisRepo   = new AvisRepository($conn);

// =======================================
// Instanciation des controllers
// =======================================
$utilisateursController  = new UtilisateursController($utilisateursRepo, $vehiculesRepo);

$covoituragesController = new CovoituragesController(
    $covoituragesRepo,
    $utilisateursRepo,
    $vehiculesRepo,
    $villesRepo
);

$villesController = new VillesController();
$vehiculesController = new VehiculesController($vehiculesRepo);
$accueilController = new AccueilController();
$avisController = new AvisController();

// =======================================
// Routage
// =======================================
$entity = $_GET['entity'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

switch ($entity) {
    case 'utilisateurs':
        switch ($action) {
            case 'creer_compte':$utilisateursController->register(); break;
            case 'tableau_de_bord':$utilisateursController->dashboard();break;
            case 'se_connecter':
                $utilisateursController->login();
                break;
            case 'liste_utilisateurs':
                $utilisateursController->liste();
                break;
            case 'supprimer':
                $utilisateursController->supprimer();
                break;
            case 'charte_graphique':
                $utilisateursController->charteGraphique();
                break;
            case 'mon_profil':
                $utilisateursController->profilUser();
                break;
            case 'profil_passager':
                $utilisateursController->profilPassager();
                break;
            case 'profil_conducteur':
                $utilisateursController->profilConducteur();
                break;
            case 'espace_employe':
                $utilisateursController->espaceEmploye();
                break;
            case 'espace_admin':
                $utilisateursController->espaceAdmin();
                break;
        }
        break;

    case 'covoiturages':
        switch ($action) {
            case 'creer_covoiturage':
                $covoituragesController->createCovoiturage();
                break;
            case 'recherche_covoiturages':
                $covoituragesController->rechercheLarge();
                break;
            case 'auto_completion':
                $covoituragesController->autocompleteVilles();
                break;
            case 'resultats_recherche':
                $covoituragesController->resultatsRecherche();
                break;
            case 'detail_covoiturage':
                $covoituragesController->showDetails();
                break;

            case 'liste_covoiturages_ecologique':
                $covoituragesController->listeCovoituragesByEcologique(1);
                break;
        }
        break;

    case 'vehicules':

        switch ($action) {
            case 'ajouter_vehicule':
                $vehiculesController->ajouter();
                break;
            case 'supprimer_vehicule':
                $vehiculesController->delete();
                break;
            case 'liste_vehicules':
                $vehiculesController->showVehicule();
                break;
            case 'supprimer_vehicule_multiple':
                $vehiculesController->deleteMultiple();
                break;
        }
        break;

    case 'accueil':
    default:
        switch ($action) {
            case 'index':
                $accueilController->index();
                break;
            case 'covoiturages':
                $accueilController->covoiturage();
                break;
            case 'contact':
                $accueilController->contact();
                break;
            case 'connexion':
                $accueilController->pageConnexion();
                break;
            case 'creer_compte':
                $accueilController->register();
                break;
            case 'logout':
                $accueilController->logout();
                break;
            case 'mentions_legales':
                $accueilController->mentionsLegales();
                break;
            case 'dashboard':
                $accueilController->grace();
                break;
        }
        break;

        case 'villes':
            switch ($action) {
                case 'liste_des_villes': $villesController->show(); break;
            }
            break;

        case 'avis':
                switch ($action) {
                    case 'avis':$avisController->showAvis();break;
                }
            }

