<?php
namespace Controller;

require_once __DIR__ .'/../src/Entity/Utilisateur.php';

session_start(); // toujours avant tout output !
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Config/Database.php';

use PDO;
use PDOException;
use Repository\UtilisateursRepository;
use Controller\UtilisateursController;
use Controller\AccueilController;

// Connexion PDO
try {
    $db = new PDO('mysql:host=localhost;dbname=ecoride_db;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base : " . $e->getMessage());
}

// Création du repository
$repo = new UtilisateursRepository($db);
$controller = new UtilisateursController($repo);

// Récupération des paramètres GET
$entity = $_GET['entity'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

switch ($entity) {

    case 'utilisateurs':
        $controller = new UtilisateursController($repo);

        switch ($action) {
            case 'creer_compte':
                $controller->register();
                break;

            case 'se_connecter':
                $controller->login();
                break;

            case 'tableau_de_bord':
                $controller->dashboard();
                break;

            case 'se_deconnecter':
                $controller->logout();
                break;
        }
        break;

    case 'accueil':
    default:
        $controller = new AccueilController();

        switch ($action) {
            case 'index':
                $controller->index();
                break;

            case 'logout':
                $controller->logout();
                break;
        }
        break;
}
