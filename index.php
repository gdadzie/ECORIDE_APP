<?php
/**
 * =======================================
 *  CONFIGURATION GÉNÉRALE
 * =======================================
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<<<<<<< HEAD
// =======================================
// AUTOLOAD & ENTITÉS NÉCESSAIRES
// =======================================
=======
define('BASE_URL', '/Ecoride_app/public/');

/**
 * =======================================
 *  AUTOLOAD & ENTITÉS
 * =======================================
 */
>>>>>>> function
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Config/Database.php';

<<<<<<< HEAD
// =======================================
// SESSION SÉCURISÉE
// =======================================
=======
// Entities
foreach (['Utilisateur', 'Covoiturage', 'Ville', 'Vehicule', 'Avis'] as $entity) {
    require_once __DIR__ . "/../src/Entity/$entity.php";
}

/**
 * =======================================
 *  SESSION
 * =======================================
 */
>>>>>>> function
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

<<<<<<< HEAD
// Récupère l’utilisateur connecté (si présent)
$user = $_SESSION['user'] ?? null;

// Correction type utilisateur par défaut
if ($user instanceof \Entity\Utilisateur && method_exists($user, 'setTypeUtilisateur')) {
    $user->setTypeUtilisateur('passager');
    $_SESSION['user'] = $user;
=======
$user = $_SESSION['user'] ?? null;

if ($user instanceof \Entity\Utilisateur && method_exists($user, 'setTypeUtilisateur')) {
    if (!$user->getTypeUtilisateur()) {
        $user->setTypeUtilisateur('passager');
        $_SESSION['user'] = $user;
    }
>>>>>>> function
}

/**
 * =======================================
<<<<<<< HEAD
 *  CONNEXION À LA BASE DE DONNÉES
=======
 *  IMPORT DES NAMESPACES
>>>>>>> function
 * =======================================
 */
use Config\Database;

<<<<<<< HEAD
// --- Namespaces des classes utilisées ---

use Repository\AvisRepository;
use Repository\CovoituragesRepository;
use Repository\UtilisateursRepository;
use Repository\VehiculesRepository;
use Repository\VillesRepository;

use Controller\Accueil\AccueilController;
use Controller\Avis\AvisController;
use Controller\Covoiturages\CovoituragesController;
use Controller\Covoiturages\CovoituragesRechercheController;
use Controller\Covoiturages\CovoituragesReservationController;
use Controller\Dashboard\DashboardController;
use Controller\Covoiturages\CovoituragesDisplayController;
use Controller\Utilisateurs\AuthController;
use Controller\Utilisateurs\AvatarController;
use Controller\Utilisateurs\ProfilController;
use Controller\Utilisateurs\ProfilRoleController;
use Controller\Utilisateurs\UtilisateurAdminController;
use Controller\Vehicules\VehiculesController;
use Controller\Villes\VillesController;

// --- Connexion ---
=======
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
>>>>>>> function
$conn = Database::getConnection();
if (!$conn) {
    $error = Database::getLastError();
    require_once __DIR__ . '/../src/View/erreur/erreur_connexion.php';
    exit;
}

/**
 * =======================================
<<<<<<< HEAD
 *  INITIALISATION DES REPOSITORIES
=======
 *  REPOSITORIES
>>>>>>> function
 * =======================================
 */
$utilisateursRepo = new UtilisateursRepository($conn);
$covoituragesRepo = new CovoituragesRepository($conn);
$vehiculesRepo    = new VehiculesRepository($conn);
<<<<<<< HEAD
$villesRepo       = new VillesRepository($conn);
=======
$villesRepo       = new VillesRepository();
>>>>>>> function
$avisRepo         = new AvisRepository($conn);

/**
 * =======================================
<<<<<<< HEAD
 *  INITIALISATION DES CONTRÔLEURS
 * =======================================
 */

// --- Utilisateurs ---
$authController             = new AuthController($utilisateursRepo);
$dashboardController        = new DashboardController($utilisateursRepo);
$profileController          = new ProfilController($utilisateursRepo);
$avatarController           = new AvatarController($utilisateursRepo);
$utilisateurAdminController = new UtilisateurAdminController($utilisateursRepo);
$profileRoleController      = new ProfilRoleController();


// --- Autres entités ---
$covoituragesController = new CovoituragesController(
    $covoituragesRepo, $utilisateursRepo, $vehiculesRepo, $villesRepo
);
$covoituragesRechercheController = new CovoituragesRechercheController($covoituragesRepo);
$covoituragesReservationController = new CovoituragesReservationController($covoituragesRepo);
$covoituragesDisplayController = new CovoituragesDisplayController($covoituragesRepo, $utilisateursRepo);


$vehiculesController = new VehiculesController($vehiculesRepo);
$villesController    = new VillesController();
$accueilController   = new AccueilController();
$avisController      = new AvisController();

/**
 * =======================================
 *  ROUTAGE PRINCIPAL
=======
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
>>>>>>> function
 * =======================================
 */
$entity = $_GET['entity'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

switch ($entity) {

    /**
<<<<<<< HEAD
     * =======================================
     *  UTILISATEURS
     * =======================================
     */
    case 'utilisateurs':
        switch ($action) {

            // 🔹 Authentification
=======
     * UTILISATEURS
     */
    case 'utilisateurs':
        switch ($action) {
>>>>>>> function
            case 'creer_compte':        $authController->register(); break;
            case 'se_connecter':        $authController->login(); break;
            case 'deconnexion':         $authController->logout(); break;

<<<<<<< HEAD
            // 🔹 Tableau de bord
            case 'tableau_de_bord':     $dashboardController->dashboard(); break;
            case 'charte_graphique':    $dashboardController->charteGraphique(); break;

            // 🔹 Profil utilisateur
            case 'mon_profil':          $profileController->profilUser(); break;
            case 'mise_a_jour_profil':  $profileController->updateProfilUtilisateur(); break;

            // 🔹 Photo de profil
            case 'mise_a_jour_avatar':  $avatarController->updateAvatar(); break;

            // 🔹 Profils par rôle
            case 'profil_passager':     $profileRoleController->profilPassager(); break;
            case 'profil_conducteur':   $profileRoleController->profilConducteur(); break;

            // 🔹 Espace admin/employé
=======
            case 'tableau_de_bord':     $dashboardController->dashboard(); break;
            case 'charte_graphique':    $dashboardController->charteGraphique(); break;

            case 'mon_profil':          $profileController->profilUser(); break;
            case 'mise_a_jour_profil':  $profileController->updateProfilUtilisateur(); break;

            case 'mise_a_jour_avatar':  $avatarController->updateAvatar(); break;

            case 'profil_passager':     $profileRoleController->profilPassager(); break;
            case 'profil_conducteur':   $profileRoleController->profilConducteur(); break;

>>>>>>> function
            case 'espace_employe':      $utilisateurAdminController->espaceEmploye(); break;
            case 'espace_admin':        $utilisateurAdminController->espaceAdmin(); break;
            case 'liste_utilisateurs':  $utilisateurAdminController->liste(); break;
            case 'supprimer':           $utilisateurAdminController->supprimer(); break;

<<<<<<< HEAD
            // 🔹 Par défaut
=======
>>>>>>> function
            default:                    $dashboardController->dashboard(); break;
        }
        break;

    /**
<<<<<<< HEAD
     * =======================================
     *  COVOITURAGES
     * =======================================
=======
     * COVOITURAGES
>>>>>>> function
     */
    case 'covoiturages':
        switch ($action) {
            case 'creer_covoiturage':             $covoituragesController->createCovoiturage(); break;
            case 'recherche_covoiturages':        $covoituragesRechercheController->rechercherCovoituragesSouples(); break;
            case 'mes_covoiturages':              $covoituragesDisplayController->mesCovoiturages(); break;
<<<<<<< HEAD
=======
            case 'supprimer':                     $covoituragesDisplayController->supprimer(); break;
>>>>>>> function
            case 'auto_completion':               $covoituragesRechercheController->autocompleteVilles(); break;
            case 'resultats_recherche':           $covoituragesRechercheController->resultatsRecherche(); break;
            case 'detail_covoiturage':            $covoituragesDisplayController->showDetails(); break;
            case 'liste_covoiturages_ecologique': $covoituragesDisplayController->listeCovoituragesByEcologique(1); break;
            case 'form_recherche_covoiturages':   $covoituragesRechercheController->formRechercheCovoiturages(); break;
            default:                              $covoituragesRechercheController->rechercheLarge(); break;
        }
        break;

    /**
<<<<<<< HEAD
     * =======================================
     *  VÉHICULES
     * =======================================
     */
    case 'vehicules':
        switch ($action) {
            case 'ajouter_vehicule':             $vehiculesController->ajouter(); break;
            case 'supprimer_vehicule':           $vehiculesController->delete();
            case 'liste_vehicules':              $vehiculesController->showVehicule(); break;
            case 'supprimer_vehicule_multiple':  $vehiculesController->deleteMultiple(); break;
            default:                             $vehiculesController->showVehicule(); break;
=======
     * VÉHICULES
     */
    case 'vehicules':
        switch ($action) {
            case 'ajouter_vehicule':            $vehiculesController->ajouter(); break;
            case 'supprimer_vehicule':          $vehiculesController->delete(); break;
            case 'liste_vehicules':             $vehiculesController->showVehicule(); break;
            case 'supprimer_vehicule_multiple': $vehiculesController->deleteMultiple(); break;
            default:                            $vehiculesController->showVehicule(); break;
>>>>>>> function
        }
        break;

    /**
<<<<<<< HEAD
     * =======================================
     *  VILLES
     * =======================================
=======
     * VILLES
>>>>>>> function
     */
    case 'villes':
        switch ($action) {
            case 'liste_des_villes': $villesController->show(); break;
            default:                 $villesController->show(); break;
        }
        break;

    /**
<<<<<<< HEAD
     * =======================================
     *  AVIS
     * =======================================
=======
     * AVIS
>>>>>>> function
     */
    case 'avis':
        switch ($action) {
            case 'avis': $avisController->showAvis(); break;
<<<<<<< HEAD
=======
            case 'ajouter': $avisController->ajouter(); break;
            case 'modifier': $avisController->modifier(); break;
            case 'note_moyenne': return $avisController->noteMoyenne($_SESSION['user_id']);
>>>>>>> function
            default:     $avisController->showAvis(); break;
        }
        break;

    /**
<<<<<<< HEAD
     * =======================================
     *  ACCUEIL (DEFAULT)
     * =======================================
=======
     * ACCUEIL
>>>>>>> function
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
