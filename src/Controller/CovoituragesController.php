<?php

namespace Controller;

use Config\Database;
use Entity\Covoiturage;
use Repository\CovoituragesRepository;
use Repository\VehiculesRepository;
use PDO;
use PDOException;

class CovoituragesController
{
    private CovoituragesRepository $repo;
    private VehiculesRepository $vehiculeRepo;
    private PDO $pdo;

    public function __construct(CovoituragesRepository $repo)
    {
        $this->repo = $repo;
        $this->vehiculeRepo = new VehiculesRepository(); // ✅ Initialisation
        $this->pdo = Database::getConnection();
    }

    // Créer un nouveau covoiturage
    public function createCovoiturage(): void
    {
        $message = '';
        $success = false;

        // Vérification utilisateur connecté
        if (empty($_SESSION['user'])) {
            header('Location: /index.php?entity=utilisateurs&action=se_connecter');
            exit;
        }

        $user = $_SESSION['user'];
        $user_id = $user->getIdUtilisateur();

        // Récupérer tous les véhicules de l'utilisateur
        $vehicules = $this->vehiculeRepo->getVehiculesByUtilisateur($user_id);

        // Récupérer toutes les villes pour autocomplete
        $stmt = $this->pdo->query("SELECT nom_ville FROM villes ORDER BY nom_ville ASC");
        $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ville_depart = trim($_POST['ville_depart'] ?? '');
            $ville_arrivee = trim($_POST['ville_arrivee'] ?? '');
            $date_depart = trim($_POST['date_depart'] ?? '');
            $heure_depart = trim($_POST['heure_depart'] ?? '');
            $nb_places = (int)($_POST['nb_places'] ?? 0);
            $ecologique = isset($_POST['ecologique']);
            $vehicule_id = (int)($_POST['vehicule_id'] ?? 0);

            // Validation simple
            if ($ville_depart === '' || $ville_arrivee === '' || $date_depart === '' || $heure_depart === '' || $nb_places <= 0 || $vehicule_id <= 0) {
                $message = "⚠️ Veuillez remplir tous les champs correctement et sélectionner un véhicule.";
            } else {
                $ville_depart_id = $this->getVilleIdByName($ville_depart);
                $ville_arrivee_id = $this->getVilleIdByName($ville_arrivee);

                if ($ville_depart_id === null || $ville_arrivee_id === null) {
                    $message = "⚠️ Une ou plusieurs villes sont invalides.";
                } else {
                    $distance_km = $this->calculDistance($ville_depart, $ville_arrivee);

                    $covoiturage = new Covoiturage(
                        id_utilisateur: $user_id,
                        id_vehicule: $vehicule_id,
                        ville_depart: $ville_depart_id,
                        ville_arrivee: $ville_arrivee_id,
                        date_depart: $date_depart,
                        heure_depart: $heure_depart,
                        distance_km: $distance_km,
                        nb_places: $nb_places,
                        ecologique: $ecologique
                    );

                    $result = $this->repo->create($covoiturage);

                    if ($result) {
                        $message = "✅ Covoiturage créé avec succès !";
                        $success = true;
                    } else {
                        $errorInfo = $this->repo->getLastError();
                        $message = "⚠️ Erreur lors de la création du covoiturage : $errorInfo";
                    }
                }
            }
        }

        // Appel de la vue
        require __DIR__ . '/../View/covoiturages/creer_covoiturage.php';
    }

    // Autocomplete pour les villes
    public function autocompleteVilles(): void
    {
        $term = trim($_GET['term'] ?? '');
        if ($term === '') {
            echo json_encode([]);
            return;
        }

        $stmt = $this->pdo->prepare("SELECT nom_ville FROM villes WHERE nom_ville LIKE :term LIMIT 10");
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
        $stmt = $this->pdo->prepare("SELECT id_ville FROM villes WHERE nom_ville = :nom_ville");
        $stmt->execute([':nom_ville' => $nom]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id_ville'] : null;
    }

    // Récupérer tous les covoiturages d'un utilisateur
    public function getCovoituragesByUtilisateur(int $userId): array
    {
        return $this->repo->getCovoituragesByUtilisateur($userId);
    }
}
