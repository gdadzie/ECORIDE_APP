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
        $covoiturage = $id > 0 ? $this->covoituragesRepo->getCovoiturageEntityById($id) : null;

        if (!$covoiturage) {
            echo "<p class='text-center text-danger'>Covoiturage introuvable.</p>";
            return;
        }

        // ⚡ Calcul automatique de l’heure d’arrivée
        $covoiturage->calculerHeureArrivee();

        require __DIR__ . '/../../View/covoiturages/detail_covoiturage.php';
    }

    /**
     * Récupère tous les covoiturages d'un utilisateur sous forme d'objets
     */
    public function getCovoituragesByUtilisateur(int $userId): array
    {
        $liste = $this->covoituragesRepo->getCovoituragesEntitiesByUtilisateur($userId);

        // ⚡ Calcul automatique pour chaque objet
        foreach ($liste as $c) {
            $c->calculerHeureArrivee();
        }

        return $liste;
    }

    /**
     * Liste des covoiturages écologiques
     */
    public function listeCovoituragesByEcologique(): void
    {
        $ecologique = (int) ($_GET['ecologique'] ?? 1);

        // ⚡ On récupère des ENTITÉS, pas des tableaux
        $covoiturages = $this->covoituragesRepo->filterByEcologiqueEntity($ecologique);

        // ⚡ On calcule l’heure d’arrivée pour chacun
        foreach ($covoiturages as $c) {
            $c->calculerHeureArrivee();
        }

        require __DIR__ . '/../../View/covoiturages/liste_covoiturages.php';
    }

    /**
     * Liste des covoiturages créés par l'utilisateur connecté
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

        $covoiturages = $this->covoituragesRepo->getCovoituragesEntitiesByUtilisateur($userId);

        // ⚡ Toujours calculer l’heure d’arrivée
        foreach ($covoiturages as $c) {
            $c->calculerHeureArrivee();
        }

        require __DIR__ . '/../../View/covoiturages/mes_covoiturages.php';
    }

    /**
     * Suppression d’un covoiturage
     */
    public function supprimer(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $covoiturageId = (int)($_GET['id'] ?? 0);

        if (!$userId || $covoiturageId <= 0) {
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }

        // ⚡ On récupère l'objet, pas un tableau
        $covoiturage = $this->covoituragesRepo->getCovoiturageEntityById($covoiturageId);

        if (!$covoiturage || $covoiturage->getIdUtilisateur() != $userId) {
            header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
            exit;
        }

        $this->covoituragesRepo->deleteCovoiturage($covoiturageId);

        header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
        exit;
    }
}
