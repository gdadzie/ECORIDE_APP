<?php
namespace Controller\Covoiturages;

use Repository\CovoituragesRepository;
use Repository\UtilisateursRepository;

class CovoituragesDisplayController
{
    private CovoituragesRepository $covoituragesRepo;
    private UtilisateursRepository $utilisateursRepo;

    public function __construct(CovoituragesRepository $covoituragesRepo, UtilisateursRepository $utilisateursRepo)
    {
        $this->covoituragesRepo = $covoituragesRepo;
        $this->utilisateursRepo = $utilisateursRepo;
    }

    /**
     * Affiche les détails d'un covoiturage
     */
    public function showDetails(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $covoiturage = $id > 0 ? $this->covoituragesRepo->getCovoiturageById($id) : null;

        if (!$covoiturage) {
            echo "<p class='text-center text-danger'>Covoiturage introuvable.</p>";
            return;
        }

        require __DIR__ . '/../../View/covoiturages/detail_covoiturage.php';
    }

    /**
     * Récupère tous les covoiturages d'un utilisateur
     */
    public function getCovoituragesByUtilisateur(int $userId): array
    {
        return $this->covoituragesRepo->getCovoituragesByUtilisateur($userId);
    }

    /**
     * Affiche les covoiturages écologiques
     */
    public function listeCovoituragesByEcologique(): void
    {
        $ecologique = (int) ($_GET['ecologique'] ?? 1);
        $covoiturages = $this->covoituragesRepo->filterByEcologique($ecologique);
        require __DIR__ . '/../../View/covoiturages/liste_covoiturages.php';
    }

    /**
     * Affiche les covoiturages créés par l'utilisateur connecté
     */
    public function mesCovoiturages(): void
    {


        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }

        $user = $this->utilisateursRepo->findById($userId);
        if (!$user) {
            session_destroy();
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }

        $covoiturages = $this->covoituragesRepo->getCovoituragesByUtilisateur($userId);

        // ⚡ Passe $user et $covoiturages à la vue
        require __DIR__ . '/../../View/covoiturages/mes_covoiturages.php';
    }

}
