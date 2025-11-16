<?php

namespace Controller\Covoiturages;

use Config\Database;
use Entity\Covoiturage;
use Repository\CovoituragesRepository;
use Repository\UtilisateursRepository;
use Repository\VehiculesRepository;
use Repository\VillesRepository;
use PDO;

class CovoituragesController
{
    private CovoituragesRepository $covoituragesRepo; // nom clair
    private UtilisateursRepository $utilisateursRepo;
    private VehiculesRepository $vehiculesRepo;
    private VillesRepository $villesRepo;
    private PDO $conn;

    public function __construct(
        CovoituragesRepository $covoituragesRepo,   // nom identique au FrontController
        UtilisateursRepository $utilisateursRepo,
        VehiculesRepository $vehiculesRepo,
        VillesRepository $villesRepo
    ) {
        $this->covoituragesRepo = $covoituragesRepo; // assignation correcte
        $this->utilisateursRepo = $utilisateursRepo;
        $this->vehiculesRepo    = $vehiculesRepo;
        $this->villesRepo       = $villesRepo;
        $this->conn             = Database::getConnection();
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

        $errors = [];
        $successMsg = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $villeDepart  = (int) ($_POST['ville_depart'] ?? 0);
            $villeArrivee = (int) ($_POST['ville_arrivee'] ?? 0);
            $dateDepart   = trim($_POST['date_depart'] ?? '');
            $heureDepart  = trim($_POST['heure_depart'] ?? '');
            $distanceKm   = (float) ($_POST['distance_km'] ?? 0);
            $prix         = (float) ($_POST['prix'] ?? 0);
            $nbPlaces     = (int) ($_POST['nb_places'] ?? 1);
            $ecologique   = isset($_POST['ecologique']);
            $dureeMinutes = (int) ($_POST['duree_minutes'] ?? 0);
            $vehiculeId   = (int) ($_POST['id_vehicule'] ?? 0);

            if ($villeDepart === 0 || $villeArrivee === 0 || empty($dateDepart) || empty($heureDepart)) {
                $errors[] = "Veuillez remplir tous les champs obligatoires (ville départ/arrivée, date, heure).";
            }

            if ($vehiculeId === 0) {
                $errors[] = "Veuillez sélectionner un véhicule.";
            }

            if (empty($errors)) {
                $covoiturage = new Covoiturage(
                    $user->getIdUtilisateur(),
                    $vehiculeId,
                    $villeDepart,
                    $villeArrivee,
                    $dateDepart,
                    $heureDepart,
                    $dureeMinutes,
                    $distanceKm,
                    $prix,
                    $nbPlaces,
                    $ecologique,
                    'prévu',
                    $user->getPseudo(),
                    $user->getPhoto()
                );

                $entityValid = $this->validateCovoiturageEntity($covoiturage);
                if ($entityValid !== true) {
                    $errors = array_merge($errors, $entityValid);
                } else {
                    // Ici c’est la variable correcte
                    $success = $this->covoituragesRepo->create($covoiturage);
                    if ($success) {
                        $successMsg = "✅ Covoiturage créé avec succès !";
                        header("Location: index.php?entity=covoiturages&action=mes_covoiturages");
                        exit;
                    } else {
                        $errors[] = $this->covoituragesRepo->getLastError() ?? "Erreur lors de la création du covoiturage.";
                    }
                }
            }
        }

        require_once __DIR__ . '/../../View/covoiturages/creer_covoiturage.php';
    }

    private function validateCovoiturageEntity(Covoiturage $covoiturage): mixed
    {
        $errors = [];
        if ($covoiturage->getPrix() < 0) {
            $errors[] = "Le prix doit être supérieur ou égal à 0.";
        }
        if ($covoiturage->getNbPlaces() <= 0) {
            $errors[] = "Le nombre de places doit être supérieur à 0.";
        }
        if ($covoiturage->getDistanceKm() <= 0) {
            $errors[] = "La distance doit être supérieure à 0 km.";
        }
        if ($covoiturage->getDureeMinutes() <= 0) {
            $errors[] = "La durée doit être supérieure à 0 minute.";
        }
        return empty($errors) ? true : $errors;
    }

    public function mesCovoiturages(): void
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?entity=accueil&action=connexion');
            exit;
        }

        $covoiturages = $this->covoituragesRepo->getEntitiesByUtilisateur($userId);
        require_once __DIR__ . '/../../View/covoiturages/mes_covoiturages.php';
    }

    public function detailCovoiturage(int $id): void
    {
        $covoiturage = $this->covoituragesRepo->getEntityById($id);
        if (!$covoiturage) {
            header('Location: index.php?entity=covoiturages&action=mes_covoiturages');
            exit;
        }

        require_once __DIR__ . '/../../View/covoiturages/detail_covoiturage.php';
    }
}
