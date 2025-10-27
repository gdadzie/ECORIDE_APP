<?php
namespace Controller;


require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Entity/Utilisateur.php';
require_once __DIR__ . '/../Repository/UtilisateursRepository.php';
require_once __DIR__ . '/../../Config/Database.php';

use Config\Database;
use Entity\Utilisateur;
use Repository\UtilisateursRepository;
class AccueilController
{


    public function index(): void       // Route vers la page d'accueil de l'application Ecoride
    {
        require __DIR__ . '/../View/accueil/index.php';
    }

    public function contact(): void
    {
        require __DIR__ . '/../View/accueil/contact.php';
    }

    public function covoiturage(): void
    {
        require __DIR__ . '/../View/covoiturages/creer_covoiturage.php';
    }



}
