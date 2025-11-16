<?php
namespace Controller\Covoiturages;

use Entity\Covoiturage;
use Repository\CovoituragesRepository;
use Repository\UtilisateursRepository;

class CovoituragesDisplayController
{
    private CovoituragesRepository $covoituragesRepo;
    private UtilisateursRepository $utilisateursRepo;
    private ?Covoiturage $covoiturage = null;

    public function __construct(CovoituragesRepository $covoituragesRepo, UtilisateursRepository $utilisateursRepo)
    {
        $this->covoituragesRepo = $covoituragesRepo;
        $this->utilisateursRepo = $utilisateursRepo;

    }

    /**
     * 🔍 Affichage des résultats d’une recherche
     */
    public function resultatsRecherche()
    {
        $depart = $_GET['depart'] ?? null;
        $arrivee = $_GET['arrivee'] ?? null;
        $date = $_GET['date'] ?? null;

        $covoiturages = $this->covoituragesRepo->rechercherCovoiturages($depart, $arrivee, $date);

        require __DIR__ . '/../../View/covoiturages/resultats_recherche.php';
    }

    /**
     * Affiche les détails d'un covoiturage
     */
    public function showDetails(): void
    {

        $id = intval($_GET['id'] ?? 0);

        // Récupération entité
        $covoiturage = $this->covoituragesRepo->getEntityById($id);

        if (!$covoiturage) {
            echo "<p class='text-center text-danger'>Covoiturage introuvable.</p>";
            return;
        }

        // Calcul de l'heure d'arrivée dans l'objet
        $covoiturage->calculerHeureArrivee();

        // On rend l'objet disponible dans la vue
        $this->covoiturage = $covoiturage;


        require __DIR__ . '/../../View/covoiturages/detail_covoiturage.php';
    }


    /**
     * Récupère tous les covoiturages d'un utilisateur sous forme d'objets
     */
    public function getCovoituragesByUtilisateur(int $userId): array
    {
        $liste = $this->covoituragesRepo->getCovoituragesByUtilisateur($userId);

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
        $covoiturages = $this->covoituragesRepo->filterByEcologique($ecologique);

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

        $covoiturages = $this->covoituragesRepo->getCovoituragesByUtilisateur($userId);

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
        $covoiturage = $this->covoituragesRepo->getEntityById($covoiturageId);

        if (!$covoiturage || $covoiturage->getIdUtilisateur() != $userId) {
            header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
            exit;
        }

        $this->covoituragesRepo->delete($covoiturageId);

        header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
        exit;
    }

    /**
     * Recherche de covoiturages (souple)
     */
    public function rechercher(): void
    {
        $villeDepart = $_GET['ville_depart'] ?? '';
        $villeArrivee = $_GET['ville_arrivee'] ?? '';
        $dateDepart = $_GET['date_depart'] ?? null;

        $covoiturages = $this->covoituragesRepo->rechercherCovoiturages($villeDepart, $villeArrivee, $dateDepart);

        require __DIR__ . '/../../View/covoiturages/liste_covoiturages.php';
    }

    private function calculerHeureArrivee(string $heureDepart, int $dureeMinutes): string
    {
        if (empty($heureDepart) || $dureeMinutes <= 0) return '00:00:00';
        $depart = new \DateTime($heureDepart);
        $depart->modify("+{$dureeMinutes} minutes");
        return $depart->format("H:i:s");
    }

    private function calculerHeureArriveePourListe(array $covoits): void
    {
        foreach ($covoits as $c) {
            $c->calculerHeureArrivee();
        }
    }


}
