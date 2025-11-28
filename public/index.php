<?php
declare(strict_types=1);

// ✅ Mise en tampon de sortie pour éviter les erreurs de headers
ob_start();

// Affichage des erreurs pour le développement
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Définition de l'URL de base
define('BASE_URL', '/Ecoride_app/public/');

// Autoload Composer et config DB
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Config/Database.php';

// Inclusion des Entities
foreach (['Utilisateur', 'Covoiturage', 'Ville', 'Vehicule', 'Avis'] as $entity) {
    require_once __DIR__ . "/../src/Entity/$entity.php";
}

// ✅ Session
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;
if ($user instanceof \Entity\Utilisateur && !$user->getTypeUtilisateur()) {
    $user->setTypeUtilisateur('passager');
    $_SESSION['user'] = $user;
}

// Namespaces et Repositories
use Config\Database;
use Repository\{
    AvisRepository,
    CovoituragesRepository,
    CreditsRepository,
    MarquesRepository,
    ReservationRepository,
    UtilisateursRepository,
    VehiculesRepository,
    VillesRepository
};
use Controller\Accueil\AccueilController;
use Controller\Avis\AvisController;
use Controller\Credits\CreditsController;
use Controller\Marques\MarquesController;
use Controller\Reservations\ReservationsController;
use Controller\Vehicules\VehiculesController;
use Controller\Villes\VillesController;
use Controller\Covoiturages\{
    CovoituragesController,
    CovoituragesRechercheController,
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

// Services
$creditsService = new CreditsService($creditsRepo);
$reservationService = new ReservationService($reservationsRepo);

// Controllers
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
$marquesController   = new MarquesController($conn);

$covoituragesController = new CovoituragesController(
    $covoituragesRepo,
    $utilisateursRepo,
    $vehiculesRepo,
    $villesRepo
);
$covoituragesRechercheController = new CovoituragesRechercheController(
    $covoituragesRepo,
    $villesRepo
);
$covoituragesDisplayController = new CovoituragesDisplayController(
    $covoituragesRepo,
    $utilisateursRepo,
    $avisRepo,
    $creditsService,
    $reservationService,
    $reservationsRepo,
    $vehiculesRepo,
    $marquesRepo
);

$reservationsController = new ReservationsController(
    $conn,
    $reservationsRepo,
    $covoituragesDisplayController,
    $creditsController
);

// ✅ Récupération de la route
$entity = $_GET['entity'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

// ✅ Redirections si nécessaire avant tout HTML
if ($entity === 'utilisateurs' && $action === 'espace_admin') {
    if (!$user || $user->getTypeUtilisateur() !== 'admin') {
        header('Location: ' . BASE_URL);
        exit;
    }
}

// ✅ Routes qui renvoient du JSON ou pas de page HTML
if ($entity === 'covoiturages' && $action === 'auto_completion') {
    $covoituragesRechercheController->autoCompletion();
    exit; // Important : ne rien envoyer après
}

// ✅ Inclure le header seulement pour les pages normales
include __DIR__ . '/../src/View/partials/header.php';

// ✅ Routing normal
switch ($entity) {
    case 'utilisateurs':
        switch ($action) {
            case 'creer_compte': $authController->register(); break;
            case 'se_connecter': $authController->login(); break;
            case 'deconnexion': $authController->logout(); break;
            case 'tableau_de_bord': $dashboardController->dashboard(); break;
            case 'mon_profil': $profileController->profilUser(); break;
            case 'mise_a_jour_profil': $profileController->updateProfilUtilisateur(); break;
            case 'mise_a_jour_avatar': $avatarController->updateAvatar(); break;
            case 'profil_passager': $profileRoleController->profilPassager(); break;
            case 'profil_conducteur': $profileRoleController->profilConducteur(); break;
            case 'espace_employe': $utilisateurAdminController->espaceEmploye(); break;
            case 'espace_admin': $utilisateurAdminController->espaceAdmin(); break;
            case 'liste_utilisateurs': $utilisateurAdminController->liste(); break;
            case 'supprimer': $utilisateurAdminController->supprimer(); break;
            default: $dashboardController->dashboard(); break;
        }
        break;

    case 'covoiturages':
        switch ($action) {
            case 'creer_covoiturage': $covoituragesController->createCovoiturage(); break;
            case 'recherche_covoiturages': $covoituragesRechercheController->rechercherCovoituragesSouples(); break;
            case 'mes_covoiturages': $covoituragesDisplayController->mesCovoiturages(); break;
            case 'modifier_covoiturage': $covoituragesDisplayController->modifierCovoiturage(); break;
            case 'supprimer': $covoituragesDisplayController->supprimer(); break;
            case 'resultats_recherche': $covoituragesRechercheController->resultatsRecherche(); break;
            case 'detail_covoiturage': $covoituragesDisplayController->showDetails(); break;
            case 'liste_covoiturages_ecologique': $covoituragesDisplayController->listeCovoituragesByEcologique(1); break;
            case 'form_recherche_covoiturages': $covoituragesRechercheController->formRechercheCovoiturages(); break;
            default: $covoituragesRechercheController->rechercheLarge(); break;
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
            default: $accueilController->index(); break;
        }
        break;
}

// ✅ Inclure le footer pour les pages normales
include __DIR__ . '/../src/View/partials/footer.php';

// ✅ Envoyer tout le contenu mis en tampon
ob_end_flush();
