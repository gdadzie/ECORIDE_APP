<?php

namespace Controller;

use Config\Database;
use Entity\Covoiturage;
use Entity\Ville;
use PDO;
use Repository\CovoituragesRepository;

class CovoituragesController
{
    private CovoituragesRepository $repo;

    public function __construct(CovoituragesRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createCovoiturage(): void
    {
        $message = '';
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ville_depart = trim($_POST['ville_depart'] ?? '');
            $ville_arrivee = trim($_POST['ville_arrivee'] ?? '');
            $heure_depart = trim($_POST['heure_depart'] ?? '');
            $nb_places = (int)($_POST['nb_places'] ?? 0);
            $ecologique = isset($_POST['ecologique']) ? (bool)$_POST['ecologique'] : false;

            if ($ville_depart === '' || $ville_arrivee === '' || $heure_depart === '' || $nb_places <= 0) {
                $message = "⚠️ Veuillez remplir tous les champs correctement.";
            } else {
                $distance_km = $this->calculDistance($ville_depart, $ville_arrivee);
                $vehicule_id = $this->getVehiculeIdUtilisateur($_SESSION['user_id']);

                $covoiturage = new Covoiturage(
                    $_SESSION['user_id'],
                    $vehicule_id,
                    $ville_depart,
                    $ville_arrivee,
                    date('Y-m-d'),
                    null,
                    $heure_depart,
                    null,
                    $distance_km,
                    $nb_places,
                    $ecologique
                );

                if ($this->repo->create($covoiturage)) {
                    $message = "Covoiturage créé avec succès !";
                    $success = true;
                } else {
                    $message = "Erreur lors de la création du covoiturage.";
                }
            }
        }

        require __DIR__ . '/../View/covoiturages/creer_covoiturage.php';
    }

    public function search(): void
    {
        $covoiturages = [];
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ville_depart = trim($_POST['ville_depart'] ?? '');
            $ville_arrivee = trim($_POST['ville_arrivee'] ?? '');
            $date_depart = trim($_POST['date_depart'] ?? '');

            if ($ville_depart === '' || $ville_arrivee === '' || $date_depart === '') {
                $message = "⚠️ Veuillez remplir tous les champs avant de lancer la recherche.";
            } else {
                $covoiturages = $this->repo->getCovoituragesByVilleDepartArriveeEtDate(
                    $ville_depart, $ville_arrivee, $date_depart
                );

                if (empty($covoiturages)) {
                    $message = "Aucun covoiturage trouvé pour cette recherche.";
                }
            }
        }

        require __DIR__ . '/../View/covoiturages/liste_covoiturage.php';
    }

    public function autocompleteVilles(): void
    {
        $term = trim($_GET['term'] ?? '');
        if ($term === '') {
            echo json_encode([]);
            return;
        }
        $villes = Ville::autocomplete($term);
        echo json_encode($villes);
    }

    private function calculDistance(string $ville_depart, string $ville_arrivee): float
    {
        if ($ville_depart === $ville_arrivee) return 0.0;
        return 100.0; // Placeholder
    }

    private function getVehiculeIdUtilisateur(int $user_id): int
    {
        return 1; // Placeholder
    }

    function getVilleIdByName(string $nom): ?int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT id_ville FROM ecoride_db.villes WHERE nom_ville = :nom_ville");
        $stmt->execute([':nom' => $nom]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id_ville'] : null;
    }



}
