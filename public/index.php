<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// --- Autoload Composer ---
require_once __DIR__ . '/../vendor/autoload.php';

// --- Controllers ---
require_once __DIR__ . '/../src/Controller/UtilisateursController.php';
require_once __DIR__ . '/../src/Controller/AccueilController.php';

use Controller\UtilisateursController;
use Controller\AccueilController;

// --- Routing simple ---
$entity = $_GET['entity'] ?? 'accueil'; // page d'accueil par défaut
$action = $_GET['action'] ?? 'index';

switch ($entity) {

    case 'utilisateurs':
        $controller = new UtilisateursController();

        switch ($action) {
            case 'se_connecter':        // Formulaire de connexion : page incription.php
                $controller->login();
                break;

            case 'creer_compte':        // Formulaire création utilisateur : page creer_utilisateur.php
                $controller->create();
                break;

            case 'modifier_utilisateur':  // Mise à jour utilisateur : page modifier_utilisateur.php
                $controller->update();
                break;

            case 'supprimer_utilisateur': // Suppression utilisateur : page supprimer_utilisateur.php
                $controller->destroy();
                break;


        }
        break;

    case 'accueil':
        $controller = new AccueilController();
        switch ($action) {

            case 'index':               // Liste des utilisateurs : page index.php
            default:
                $controller->index();
                break;

            case 'se_connecter':        // Accède à la page d'accueil : page index.php
                $controller->index();
                break;

            case 'contact':
                $controller->contact();
                break;

            case 'covoiturage':
                $controller->covoiturage();
                break;
        }
        break;

}
