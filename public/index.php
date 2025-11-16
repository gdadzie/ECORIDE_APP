<?php
/**
 * =======================================
 *  CONFIGURATION GÉNÉRALE
 * =======================================
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_URL', '/Ecoride_app/public/');

/**
 * =======================================
 *  AUTOLOAD & ENTITÉS
 * =======================================
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Config/Database.php';

// Entities
foreach (['Utilisateur', 'Covoiturage', 'Ville', 'Vehicule', 'Avis'] as $entity) {
    require_once __DIR__ . "/../src/Entity/$entity.php";
}

/**
 * =======================================
 *  SESSION
 * =======================================
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

if ($user instanceof \Entity\Utilisateur && method_exists($user, 'setTypeUtilisateur')) {
    if (!$user->getTypeUtilisateur()) {
        $user->setTypeUtilisateur('passager');
        $_SESSION['user'] = $user;
    }
}

/**
 * =======================================
 *  IMPORT DES NAMESPACES
 * =======================================
 */
use Config\Database;

use Repository\{
    AvisRepository,
    CovoituragesRepository,
    UtilisateursRepository,
    VehiculesRepository,
    VillesRepository
};

use Controller\Accueil\AccueilController;
use Controller\Avis\AvisController;
use Controller\Covoiturages\{
    CovoituragesController,
    CovoituragesRechercheController,
    CovoituragesReservationController,
    CovoituragesDisplayController
};
use Controller\Dashboard\DashboardController;
use Controller\Utilisateurs\{
    AuthController,
    AvatarController,
    ProfilController,
    ProfilRoleController,
    UtilisateurAdminController
};
use Controller\Vehicules\VehiculesController;
use Controller\Villes\VillesController;

/**
 * =======================================
 *  CONNEXION DB
 * =======================================
 */
$conn = Database::getConnection();
if (!$conn) {
    $error = Database::getLastError();
    require_once __DIR__ . '/../src/View/erreur/erreur_connexion.php';
    exit;
}

/**
 * =======================================
 *  REPOSITORIES
 * =======================================
 */
$utilisateursRepo = new UtilisateursRepository($conn);
$covoituragesRepo = new CovoituragesRepository($conn);
$vehiculesRepo    = new VehiculesRepository($conn);
$villesRepo       = new VillesRepository();
$avisRepo         = new AvisRepository($conn);

/**
 * =======================================
 *  CONTRÔLEURS
 * =======================================
 */
// Utilisateurs
$authController             = new AuthController($utilisateursRepo);
$dashboardController        = new DashboardController($utilisateursRepo);
$profileController          = new ProfilController($utilisateursRepo);
$avatarController           = new AvatarController($utilisateursRepo);
$utilisateurAdminController = new UtilisateurAdminController($utilisateursRepo);
$profileRoleController      = new ProfilRoleController();

// Covoiturages
$covoituragesController          = new CovoituragesController($covoituragesRepo, $utilisateursRepo, $vehiculesRepo, $villesRepo);
$covoituragesRechercheController = new CovoituragesRechercheController($covoituragesRepo, $villesRepo);
$covoituragesReservationController = new CovoituragesReservationController($covoituragesRepo, $utilisateursRepo);
$covoituragesDisplayController = new CovoituragesDisplayController($covoituragesRepo, $utilisateursRepo, $avisRepo);

// Autres
$vehiculesController = new VehiculesController($vehiculesRepo);
$villesController    = new VillesController($villesRepo);
$accueilController   = new AccueilController();
$avisController      = new AvisController($avisRepo);

/**
 * =======================================
 *  ROUTAGE
 * =======================================
 */
$entity = $_GET['entity'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

switch ($entity) {

    /**
     * UTILISATEURS
     */
    case 'utilisateurs':
        switch ($action) {
            case 'creer_compte':        $authController->register(); break;
            case 'se_connecter':        $authController->login(); break;
            case 'deconnexion':         $authController->logout(); break;

            case 'tableau_de_bord':     $dashboardController->dashboard(); break;
            case 'charte_graphique':    $dashboardController->charteGraphique(); break;

            case 'mon_profil':          $profileController->profilUser(); break;
            case 'mise_a_jour_profil':  $profileController->updateProfilUtilisateur(); break;

            case 'mise_a_jour_avatar':  $avatarController->updateAvatar(); break;

            case 'profil_passager':     $profileRoleController->profilPassager(); break;
            case 'profil_conducteur':   $profileRoleController->profilConducteur(); break;

            case 'espace_employe':      $utilisateurAdminController->espaceEmploye(); break;
            case 'espace_admin':        $utilisateurAdminController->espaceAdmin(); break;
            case 'liste_utilisateurs':  $utilisateurAdminController->liste(); break;
            case 'supprimer':           $utilisateurAdminController->supprimer(); break;

            default:                    $dashboardController->dashboard(); break;
        }
        break;

    /**
     * COVOITURAGES
     */
    case 'covoiturages':
        switch ($action) {
            case 'creer_covoiturage':             $covoituragesController->createCovoiturage(); break;
            case 'recherche_covoiturages':        $covoituragesRechercheController->rechercherCovoituragesSouples(); break;
            case 'mes_covoiturages':              $covoituragesDisplayController->mesCovoiturages(); break;
            case 'supprimer':                     $covoituragesDisplayController->supprimer(); break;
            case 'auto_completion':               $covoituragesRechercheController->autocompleteVilles(); break;
            case 'resultats_recherche':           $covoituragesRechercheController->resultatsRecherche(); break;
            case 'detail_covoiturage':            $covoituragesDisplayController->showDetails(); break;
            case 'liste_covoiturages_ecologique': $covoituragesDisplayController->listeCovoituragesByEcologique(1); break;
            case 'form_recherche_covoiturages':   $covoituragesRechercheController->formRechercheCovoiturages(); break;
            default:                              $covoituragesRechercheController->rechercheLarge(); break;
        }
        break;

    /**
     * VÉHICULES
     */
    case 'vehicules':
        switch ($action) {
            case 'ajouter_vehicule':            $vehiculesController->ajouter(); break;
            case 'supprimer_vehicule':          $vehiculesController->delete(); break;
            case 'liste_vehicules':             $vehiculesController->showVehicule(); break;
            case 'supprimer_vehicule_multiple': $vehiculesController->deleteMultiple(); break;
            default:                            $vehiculesController->showVehicule(); break;
        }
        break;

    /**
     * VILLES
     */
    case 'villes':
        switch ($action) {
            case 'liste_des_villes': $villesController->show(); break;
            default:                 $villesController->show(); break;
        }
        break;

    /**
     * AVIS
     */
    case 'avis':
        switch ($action) {
            case 'avis': $avisController->showAvis(); break;
            case 'ajouter': $avisController->ajouter(); break;
            case 'modifier': $avisController->modifier(); break;
            case 'note_moyenne': return $avisController->noteMoyenne($_SESSION['user_id']);
            default:     $avisController->showAvis(); break;
        }
        break;

    /**
     * ACCUEIL
     */
    case 'accueil':
    default:
        switch ($action) {
            case 'index':            $accueilController->index(); break;
            case 'covoiturages':     $accueilController->covoiturage(); break;
            case 'contact':          $accueilController->contact(); break;
            case 'connexion':        $accueilController->pageConnexion(); break;
            case 'creer_compte':     $accueilController->register(); break;
            case 'logout':           $accueilController->logout(); break;
            case 'mentions_legales': $accueilController->mentionsLegales(); break;
            case 'dashboard':        $accueilController->grace(); break;
            default:                 $accueilController->index(); break;
        }
        break;
}
