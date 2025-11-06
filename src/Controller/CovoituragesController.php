<?php
namespace Controller;

use Config\Database;
use Entity\Covoiturage;
use Repository\CovoituragesRepository;
use Repository\UtilisateursRepository;
use Repository\VehiculesRepository;
use PDO;

class CovoituragesController
{
    private CovoituragesRepository $repo;
    private UtilisateursRepository $utilisateursRepo;
    private VehiculesRepository $vehiculesRepo;
    private PDO $conn;

    public function __construct(
        CovoituragesRepository $repo,
        UtilisateursRepository $utilisateursRepo,
        VehiculesRepository $vehiculesRepo
    ) {
        $this->repo = $repo;
        $this->utilisateursRepo = $utilisateursRepo;
        $this->vehiculesRepo = $vehiculesRepo;
        $this->conn = Database::getConnection();
    }

    public function createCovoiturage()
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $villeDepart  = $_POST['ville_depart'] ?? '';
            $villeArrivee = $_POST['ville_arrivee'] ?? '';
            $dateDepart   = $_POST['date_depart'] ?? '';
            $heureDepart  = $_POST['heure_depart'] ?? '';
            $distanceKm   = (float) ($_POST['distance_km'] ?? 0);
            $prix         = (float) ($_POST['prix'] ?? 0);
            $nbPlaces     = (int) ($_POST['nb_places'] ?? 1);
            $ecologique   = isset($_POST['ecologique']);
            $dureeMinutes = (int) ($_POST['duree_minutes'] ?? 0);
            $vehiculeId   = (int) ($_POST['id_vehicule'] ?? 0);

            $covoiturage = new Covoiturage(
                $user->getIdUtilisateur(),
                $vehiculeId,
                $villeDepart,
                $villeArrivee,
                $dateDepart,
                $heureDepart,
                $distanceKm,
                $prix,
                $nbPlaces,
                $ecologique,
                $dureeMinutes,
                'prévu'
            );

            $success = $this->repo->create($covoiturage);

            if ($success) {
                header('Location: index.php?entity=covoiturages&action=liste_covoiturages');
                exit;
            } else {
                $error = $this->repo->getLastError();
                include __DIR__ . '/../View/erreur_covoiturage.php';
            }
        } else {
            require_once __DIR__ . '/../View/utilisateurs/creer_covoiturage.php';
        }
    }


    // Autocomplete pour les villes
    public function autocompleteVilles(): void
    {
        $term = trim($_GET['term'] ?? '');
        if ($term === '') {
            echo json_encode([]);
            return;
        }

        $stmt = $this->conn->prepare("SELECT nom_ville FROM villes WHERE nom_ville LIKE :term LIMIT 10");
        $stmt->execute([':term' => $term . '%']);
        $villes = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo json_encode($villes);
    }

    // Calcul distance fictive (placeholder)
    private function calculDistance(string $ville_depart, string $ville_arrivee): float
    {
        return ($ville_depart === $ville_arrivee) ? 0.0 : 100.0;
    }

    // Récupérer l'ID d'une ville par son nom
    private function getVilleIdByName(string $nom): ?int
    {
        $stmt = $this->conn->prepare("SELECT id_ville FROM villes WHERE nom_ville = :nom_ville");
        $stmt->execute([':nom_ville' => $nom]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id_ville'] : null;
    }

    // Récupérer tous les covoiturages d'un utilisateur
    public function getCovoituragesByUtilisateur(int $userId): array
    {
        return $this->repo->getCovoituragesByUtilisateur($userId);
    }

    public function listeCovoituragesByEcologique(): void
    {
        // On récupère la valeur passée dans l’URL, par défaut 1 (écologique).
        $ecologique = isset($_GET['ecologique']) ? (int)$_GET['ecologique'] : 1;

        // On appelle le repository avec cette valeur
        $covoiturages = $this->repo->filterByEcologique($ecologique);

        // On inclut la vue et on lui donne accès à la variable $covoiturages
        require_once __DIR__ . '/../View/utilisateurs/liste_covoiturages.php';
    }

}