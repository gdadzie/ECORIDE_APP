<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('BASE_URL', '/Ecoride_app/public/');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Config/Database.php';

// Entities
foreach (['Utilisateur', 'Covoiturage', 'Ville', 'Vehicule', 'Avis'] as $entity) {
    require_once __DIR__ . "/../src/Entity/$entity.php";
}

// Session
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;
if ($user instanceof \Entity\Utilisateur && !$user->getTypeUtilisateur()) {
    $user->setTypeUtilisateur('passager');
    $_SESSION['user'] = $user;
}

// Namespaces
use Config\Database;
use Repository\{AvisRepository,
    CovoituragesRepository,
    CreditsRepository,
    MarquesRepository,
    ReservationRepository,
    UtilisateursRepository,
    VehiculesRepository,
    VillesRepository};
use Controller\Accueil\AccueilController;
use Controller\Avis\AvisController;
use Controller\Credits\CreditsController;
use Controller\Marques\marquesController;
use Controller\Reservations\ReservationsController;
use Controller\Vehicules\VehiculesController;
use Controller\Villes\VillesController;
use Controller\Covoiturages\{CovoituragesController, CovoituragesRechercheController, CovoituragesReservationController, CovoituragesDisplayController};
use Controller\Dashboard\DashboardController;
use Controller\Utilisateurs\{AuthController, AvatarController, ProfilController, ProfilRoleController, UtilisateurAdminController};
use Service\CreditsService;
use Service\ReservationService;



// Connexion DB
try {
    $conn = Database::getConnection();
    if (!$conn) throw new Exception(Database::getLastError() ?: 'Erreur inconnue de connexion.');
} catch (Exception $e) {
    require_once __DIR__ . '/../src/View/erreur/erreur_connexion.php';
    exit;
}

// Repositories
$utilisateursRepo = new UtilisateursRepository($conn);
$covoituragesRepo = new CovoituragesRepository($conn);
$vehiculesRepo    = new VehiculesRepository($conn);
$villesRepo       = new VillesRepository($conn);
$avisRepo         = new AvisRepository($conn);
$reservationsRepo = new ReservationRepository($conn);
$creditsRepo      = new CreditsRepository($conn);
$marquesRepo      = new MarquesRepository($conn);

// =====================
//  SERVICES
// =====================



$creditsService = new CreditsService($creditsRepo);
$reservationService = new \Service\ReservationService($reservationsRepo);



// =====================
//  CONTROLLERS
// =====================

$authController   = new AuthController($utilisateursRepo);
$dashboardController = new DashboardController($utilisateursRepo);
$profileController   = new ProfilController($utilisateursRepo);
$avatarController    = new AvatarController($utilisateursRepo);
$utilisateurAdminController = new UtilisateurAdminController($utilisateursRepo);
$profileRoleController = new ProfilRoleController();

$vehiculesController = new VehiculesController($vehiculesRepo);
$villesController    = new VillesController($villesRepo);
$accueilController   = new AccueilController();
$avisController      = new AvisController($avisRepo);
$creditsController   = new CreditsController($conn);
$marquesController  = new MarquesController($conn);


// =====================
//  CONTROLLERS COVOITURAGES
// =====================

// Création d’un covoiturage
$covoituragesController = new CovoituragesController(
    $covoituragesRepo,
    $utilisateursRepo,
    $vehiculesRepo,
    $villesRepo
);

// Recherche
$covoituragesRechercheController = new CovoituragesRechercheController(
    $covoituragesRepo,
    $villesRepo
);

// Réservation
$covoituragesReservationController = new CovoituragesReservationController(
    $covoituragesRepo,
    $utilisateursRepo
);

// Affichage des détails / Mes covoiturages / Participation

$covoituragesDisplayController = new CovoituragesDisplayController(
    $covoituragesRepo,      // 1
    $utilisateursRepo,      // 2
    $avisRepo,              // 3
    $creditsService,        // 4
    $reservationService,    // 5
    $reservationsRepo,      // 6
    $vehiculesRepo,         // 7
    $marquesRepo,           // 8
);



// =====================
//  CONTROLLER RÉSERVATIONS
// =====================
$reservationsController = new ReservationsController(
    $conn,
    $reservationsRepo,
    $covoituragesController
);

// Routing
$entity = $_GET['entity'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

switch ($entity) {
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
            case 'espace_admin':
                if (!$user || $user->getTypeUtilisateur() !== 'admin') {
                    header('Location: ' . BASE_URL);
                    exit;
                }
                $utilisateurAdminController->espaceAdmin(); break;
            case 'liste_utilisateurs':  $utilisateurAdminController->liste(); break;
            case 'supprimer':           $utilisateurAdminController->supprimer(); break;
            default:                    $dashboardController->dashboard(); break;
        }
        break;

    case 'covoiturages':
        switch ($action) {
            case 'creer_covoiturage':
                $covoituragesController->createCovoiturage();
                break;

            case 'recherche_covoiturages':
                $covoituragesRechercheController->rechercherCovoituragesSouples();
                break;

            case 'mes_covoiturages':
                $covoituragesDisplayController->mesCovoiturages();
                break;

            case 'modifier_covoiturage':
                $covoituragesDisplayController->modifierCovoiturage();
                break;

            case 'supprimer':
                $covoituragesDisplayController->supprimer();
                break;

            case 'auto_completion':
                $covoituragesRechercheController->autocompleteVilles();
                break;

            case 'resultats_recherche':
                $covoituragesRechercheController->resultatsRecherche();
                break;

            case 'detail_covoiturage':
                $covoituragesDisplayController->showDetails();
                break;


            case 'liste_covoiturages_ecologique':
                $covoituragesDisplayController->listeCovoituragesByEcologique(1);
                break;

            case 'form_recherche_covoiturages':
                $covoituragesRechercheController->formRechercheCovoiturages();
                break;

            default:
                $covoituragesRechercheController->rechercheLarge();
                break;
        }
        break;


    case 'reservations':
        switch ($action) {
            case 'detail_reservation':
                if (!$user) {
                    header('Location: index.php?entity=utilisateurs&action=se_connecter');
                    exit;
                }

                $covoiturageId = $_GET['covoiturage_id'] ?? null;
                if (!$covoiturageId) {
                    echo "Covoiturage introuvable.";
                    exit;
                }

                $covoiturage = $covoituragesController->find((int)$covoiturageId);
                $reservationsController->reserver($user, $covoiturage);
                break;

            default:
                header('Location: index.php?entity=covoiturages&action=resultats_recherche');
                exit;
        }
        break;

    case 'vehicules':
        switch ($action) {
            case 'ajouter_vehicule':            $vehiculesController->ajouter(); break;
            case 'supprimer_vehicule':          $vehiculesController->delete(); break;
            case 'liste_vehicules':             $vehiculesController->showVehicule(); break;
            case 'supprimer_vehicule_multiple': $vehiculesController->deleteMultiple(); break;
            default:                            $vehiculesController->showVehicule(); break;
        }
        break;

    case 'villes':
        $villesController->show();
        break;

    case 'avis':
        switch ($action) {
            case 'avis':          $avisController->showAvis(); break;
            case 'ajouter':       $avisController->ajouter(); break;
            case 'modifier':      $avisController->modifier(); break;
            case 'note_moyenne':  return $avisController->noteMoyenne($_SESSION['user_id'] ?? 0); break;
            default:              $avisController->showAvis(); break;
        }
        break;

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
            case 'resultat_recherches': $covoituragesDisplayController->resultatsRecherche(); break;
            default:                 $accueilController->index(); break;
        }
        break;
}