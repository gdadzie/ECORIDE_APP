<?php
/**
 * =======================================
 *  CONFIGURATION GÉNÉRALE
 * =======================================
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// =======================================
// AUTOLOAD & ENTITÉS NÉCESSAIRES
// =======================================
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../src/Entity/Utilisateur.php';
require_once __DIR__ . '/../src/Entity/Covoiturage.php';
require_once __DIR__ . '/../src/Entity/Ville.php';

// =======================================
// SESSION SÉCURISÉE
// =======================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupère l’utilisateur connecté (si présent)
$user = $_SESSION['user'] ?? null;

// Correction type utilisateur par défaut
if ($user instanceof \Entity\Utilisateur && method_exists($user, 'setTypeUtilisateur')) {
    $user->setTypeUtilisateur('passager');
    $_SESSION['user'] = $user;
}

/**
 * =======================================
 *  CONNEXION À LA BASE DE DONNÉES
 * =======================================
 */
use Config\Database;

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
$conn = Database::getConnection();
if (!$conn) {
    $error = Database::getLastError();
    require_once __DIR__ . '/../src/View/erreur/erreur_connexion.php';
    exit;
}

/**
 * =======================================
 *  INITIALISATION DES REPOSITORIES
 * =======================================
 */
$utilisateursRepo = new UtilisateursRepository($conn);
$covoituragesRepo = new CovoituragesRepository($conn);
$vehiculesRepo    = new VehiculesRepository($conn);
$villesRepo       = new VillesRepository($conn);
$avisRepo         = new AvisRepository($conn);

/**
 * =======================================
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
 * =======================================
 */
$entity = $_GET['entity'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

switch ($entity) {

    /**
     * =======================================
     *  UTILISATEURS
     * =======================================
     */
    case 'utilisateurs':
        switch ($action) {

            // 🔹 Authentification
            case 'creer_compte':        $authController->register(); break;
            case 'se_connecter':        $authController->login(); break;
            case 'deconnexion':         $authController->logout(); break;

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
            case 'espace_employe':      $utilisateurAdminController->espaceEmploye(); break;
            case 'espace_admin':        $utilisateurAdminController->espaceAdmin(); break;
            case 'liste_utilisateurs':  $utilisateurAdminController->liste(); break;
            case 'supprimer':           $utilisateurAdminController->supprimer(); break;

            // 🔹 Par défaut
            default:                    $dashboardController->dashboard(); break;
        }
        break;

    /**
     * =======================================
     *  COVOITURAGES
     * =======================================
     */
    case 'covoiturages':
        switch ($action) {
            case 'creer_covoiturage':             $covoituragesController->createCovoiturage(); break;
            case 'recherche_covoiturages':        $covoituragesRechercheController->rechercherCovoituragesSouples(); break;
            case 'mes_covoiturages':              $covoituragesDisplayController->mesCovoiturages(); break;
            case 'auto_completion':               $covoituragesRechercheController->autocompleteVilles(); break;
            case 'resultats_recherche':           $covoituragesRechercheController->resultatsRecherche(); break;
            case 'detail_covoiturage':            $covoituragesDisplayController->showDetails(); break;
            case 'liste_covoiturages_ecologique': $covoituragesDisplayController->listeCovoituragesByEcologique(1); break;
            case 'form_recherche_covoiturages':   $covoituragesRechercheController->formRechercheCovoiturages(); break;
            default:                              $covoituragesRechercheController->rechercheLarge(); break;
        }
        break;

    /**
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
        }
        break;

    /**
     * =======================================
     *  VILLES
     * =======================================
     */
    case 'villes':
        switch ($action) {
            case 'liste_des_villes': $villesController->show(); break;
            default:                 $villesController->show(); break;
        }
        break;

    /**
     * =======================================
     *  AVIS
     * =======================================
     */
    case 'avis':
        switch ($action) {
            case 'avis': $avisController->showAvis(); break;
            default:     $avisController->showAvis(); break;
        }
        break;

    /**
     * =======================================
     *  ACCUEIL (DEFAULT)
     * =======================================
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
