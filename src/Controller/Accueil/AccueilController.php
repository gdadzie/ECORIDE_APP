<?php
namespace Controller\Accueil;


require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Entity/Utilisateur.php';
require_once __DIR__ . '/../../Repository/UtilisateursRepository.php';
require_once __DIR__ . '/../../Repository/CovoituragesRepository.php';
require_once __DIR__ . '/../../../Config/Database.php';

class AccueilController
{

    //-------------------- ACCES A LA PAGE D ACCUEIL --------------------//
    public function index(): void       // Route vers la page d'accueil de l'application Ecoride
    {
        require __DIR__ . '/../../View/accueil/index2.php';
    }
    //-------------------- DECONNEXION --------------------//
    public function logout(): void
    {
        require __DIR__ . '/../../View/accueil/index.php';

    }

    //-------------------- ACCES A LA PAGE CONTACT --------------------//
    public function contact(): void
    {
        require __DIR__ . '/../../View/accueil/contact.php';
    }


    //-------------------- ACCES A LA PAGE COVOITURAGES --------------------//

    public function covoiturage(): void
    {
        require __DIR__ . '/../../View/accueil/convoiturages.php';
    }

    //-------------------- ACCES A LA PAGE CONNEXION --------------------//
    public function pageConnexion(): void
    {
        require __DIR__ . '/../../View/accueil/se_connecter.php';
    }

    //-------------------- ACCES A LA PAGE CREER COMPTE --------------------//
    public function register()
    {
        require __DIR__ . '/../View/utilisateurs/creer_compte_utilisateur.php';
    }


    //-------------------- RECHERCHER UN COVOITURAGE --------------------//


    //-------------------- ACCES A LA PAGE MENTIONS LEGALES --------------------//

    public function mentionsLegales(): void
    {
        require __DIR__ . '/../../View/accueil/mentions_legales.php';
    }


    public function grace(): void
    {
        require __DIR__ . '/../../View/accueil/dashboard.php';
    }




}
