<?php
namespace Controller\View;

class CovoituragesViewController
{
    public function formRecherche(): void
    {
        require_once __DIR__ . '/../../View/partials/formulaire_recherche_covoiturages.php';
    }

    public function listeCovoiturages(array $covoiturages): void
    {
        require_once __DIR__ . '/../../../View/covoiturages/liste_covoiturages.php';
    }

    public function detailCovoiturage(array $covoiturage): void
    {
        require_once __DIR__ . '/../../../View/covoiturages/detail_covoiturage.php';
    }
}
