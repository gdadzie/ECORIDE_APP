<?php
namespace Controller;

require_once __DIR__ .'/../src/Entity/Utilisateur.php';
require_once __DIR__ .'/../src/Entity/Covoiturage.php';

session_start(); // toujours avant tout output !
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Config/Database.php';

use Config\Database;
use PDO;
use PDOException;
use Repository\UtilisateursRepository;
use Repository\CovoituragesRepository;
use Controller\UtilisateursController;
use Controller\AccueilController;
use Entity\Covoiturage;


// Connexion PDO
try {
    $db = new PDO('mysql:host=localhost;dbname=ecoride_db;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base : " . $e->getMessage());
}

$db = Database::getConnection(); // ou nouveau PDO(...)



/// Création des repositories
$utilisateursRepo = new UtilisateursRepository($db);
$covoituragesRepo = new CovoituragesRepository($db);

// Création des contrôleurs
$utilisateursController = new UtilisateursController($utilisateursRepo);
$covoituragesController = new CovoituragesController($covoituragesRepo);
$accueilController = new AccueilController();

// Récupération des paramètres GET
$entity = $_GET['entity'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

switch ($entity) {

    case 'utilisateurs':
        switch ($action) {
            case 'creer_compte':
                $utilisateursController->register();
                break;

            case 'se_connecter':
                $utilisateursController->login();
                break;

            case 'tableau_de_bord':
                $utilisateursController->dashboard();
                break;

            case 'se_deconnecter':
                $utilisateursController->logout();
                break;

            case 'liste_utilisateurs':
                $utilisateursController->liste();
                break;

            case 'supprimer':  // <-- Ajout de cette ligne
                $utilisateursController->supprimer();
                break;
        }
        break;

    case 'covoiturages':
        switch ($action) {
            case 'creer_covoiturage':
                $covoituragesController->createCovoiturage();
                break;

            case 'rechercher_covoiturage':
                $covoituragesController->search();
                break;

            case 'liste_covoiturages':
                $covoituragesController->search();
                break;
        }
        break;

    case 'accueil':
    default:
        switch ($action) {
            case 'index':
                $accueilController->index();
                break;

            case 'logout':
                $accueilController->logout();
                break;

            case 'contact':
                $accueilController->contact();
                break;

            case 'covoiturage':
                $accueilController->covoiturage();
                break;
        }
        break;


}
