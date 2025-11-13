<?php
namespace Controller\Avis;

use Entity\Avis;
use Repository\AvisRepository;

class AvisController
{
    private AvisRepository $repo;

    public function __construct()
    {
        $this->repo = new AvisRepository();
    }

    // Ajouter un avis (ex: depuis un formulaire)
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $avis = new Avis(
                    $_POST['id_covoiturage'] ?? null,
                    $_POST['id_emetteur'] ?? null,
                    $_POST['id_receveur'] ?? null,
                    $_POST['note'] ?? null,
                    $_POST['commentaire'] ?? null,
                    'en attente'
                );
                $avis->validate();
                $this->repo->add($avis);
                echo "<div class='alert alert-success'>Avis ajouté avec succès !</div>";
            } catch (\Exception $e) {
                echo "<div class='alert alert-danger'>Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }

        require_once __DIR__ . '/../../View/Avis/liste_avis.php';
    }

    public function showAvis()
    {


        require_once __DIR__ . '/../../View/Avis/liste_avis.php';
    }
}
