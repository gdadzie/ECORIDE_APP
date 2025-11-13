<?php
namespace Controller\Covoiturages;

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

    /**
     * Création d’un covoiturage
     */
    public function createCovoiturage(): void
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

        $vehicules = $this->vehiculesRepo->getVehiculesByUtilisateur($user->getIdUtilisateur());
        $stmt = $this->conn->query("SELECT id_ville, nom_ville FROM villes ORDER BY nom_ville ASC");
        $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $villeDepart  = (int) ($_POST['ville_depart'] ?? 0);
            $villeArrivee = (int) ($_POST['ville_arrivee'] ?? 0);
            $dateDepart   = $_POST['date_depart'] ?? '';
            $heureDepart  = $_POST['heure_depart'] ?? '';
            $distanceKm   = (float) ($_POST['distance_km'] ?? 0);
            $prix         = (float) ($_POST['prix'] ?? 0);
            $nbPlaces     = (int) ($_POST['nb_places'] ?? 1);
            $ecologique   = isset($_POST['ecologique']);
            $dureeMinutes = (int) ($_POST['duree_minutes'] ?? 0);
            $vehiculeId   = (int) ($_POST['id_vehicule'] ?? 0);

            if ($villeDepart === 0 || $villeArrivee === 0 || empty($dateDepart) || empty($heureDepart)) {
                $error = "Veuillez remplir tous les champs obligatoires.";
            } else {
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
                    $successMsg = "✅ Covoiturage créé avec succès !";
                } else {
                    $error = $this->repo->getLastError() ?? "❌ Erreur lors de la création du covoiturage.";
                }
            }
        }

        require_once __DIR__ . '/../../View/covoiturages/creer_covoiturage.php';
    }
}
