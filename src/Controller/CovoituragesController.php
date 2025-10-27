<?php

use Entity\Covoiturage;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Entity/Utilisateur.php';
require_once __DIR__ . '/../Repository/UtilisateursRepository.php';
require_once __DIR__ . '/../../Config/Database.php';

class CovoituragesController
{
    private \Repository\CovoituragesRepository $repo;

    public function __construct()
    {
        $this->repo = new \Repository\CovoituragesRepository();
    }
}
