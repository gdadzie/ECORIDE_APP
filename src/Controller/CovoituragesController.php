<?php
namespace Controller;

use Config\Database;
use Entity\Covoiturage;
use Repository\CovoituragesRepository;
use Repository\UtilisateursRepository;
use Repository\VehiculesRepository;
use Repository\VillesRepository;
use PDO;

class CovoituragesController
{
    private CovoituragesRepository $repo;
    private UtilisateursRepository $utilisateursRepo;
    private VehiculesRepository $vehiculesRepo;
    private VillesRepository $villesRepo;
    private PDO $conn;

    public function __construct(
        CovoituragesRepository $repo,
        UtilisateursRepository $utilisateursRepo,
        VehiculesRepository $vehiculesRepo,
        VillesRepository $villesRepo
    ) {
        $this->repo = $repo;
        $this->utilisateursRepo = $utilisateursRepo;
        $this->vehiculesRepo = $vehiculesRepo;
        $this->villesRepo = $villesRepo;
        $this->conn = Database::getConnection();
    }

    /****************************
     * Création d’un covoiturage
     ****************************/
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

        require_once __DIR__ . '/../View/covoiturages/creer_covoiturage.php';
    }

    /************************************
     * Recherche flexible de covoiturages
     ************************************/
    public function rechercherCovoituragesSouples(string $villeDepart, string $villeArrivee, ?string $dateDepart = null): array
    {
        $query = "
            SELECT c.*, vd.nom_ville AS ville_depart_nom, va.nom_ville AS ville_arrivee_nom
            FROM covoiturages c
            JOIN villes vd ON c.ville_depart = vd.id_ville
            JOIN villes va ON c.ville_arrivee = va.id_ville
            WHERE 1=1
        ";

        $params = [];
        if (!empty($villeDepart)) {
            $query .= " AND LOWER(vd.nom_ville) LIKE LOWER(:villeDepart)";
            $params[':villeDepart'] = "%$villeDepart%";
        }
        if (!empty($villeArrivee)) {
            $query .= " AND LOWER(va.nom_ville) LIKE LOWER(:villeArrivee)";
            $params[':villeArrivee'] = "%$villeArrivee%";
        }
        if (!empty($dateDepart)) {
            $query .= " AND c.date_depart = :dateDepart";
            $params[':dateDepart'] = $dateDepart;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*******************************
     * Action AJAX pour la recherche
     *******************************/
    public function recherche(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $villeDepart  = trim($_POST['ville_depart'] ?? '');
        $villeArrivee = trim($_POST['ville_arrivee'] ?? '');
        $dateDepart   = $_POST['date_depart'] ?? null;

        // Validation basique
        if (empty($villeDepart) && empty($villeArrivee)) {
            echo json_encode([
                'success' => false,
                'message' => 'Veuillez saisir au moins une ville de départ ou d’arrivée.'
            ]);
            return;
        }

        try {
            $covoiturages = $this->rechercherCovoituragesSouples($villeDepart, $villeArrivee, $dateDepart);

            if (empty($covoiturages)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Aucun covoiturage trouvé pour ces critères.'
                ]);
                return;
            }

            // On enrichit légèrement les données pour le front Echolide
            $data = array_map(function($c) {
                return [
                    'id' => $c['id_covoiturage'],
                    'ville_depart_nom' => $c['ville_depart_nom'],
                    'ville_arrivee_nom' => $c['ville_arrivee_nom'],
                    'date_depart' => $c['date_depart'],
                    'heure_depart' => $c['heure_depart'] ?? '—',
                    'nb_places' => $c['nb_places'],
                    'prix' => $c['prix'],
                    'ecologique' => (bool) $c['ecologique'],
                    'duree_minutes' => $c['duree_minutes'],
                    'statut' => $c['statut']
                ];
            }, $covoiturages);

            echo json_encode([
                'success' => true,
                'count'   => count($covoiturages),
                'data'    => $data
            ]);

        } catch (\Throwable $e) {
            error_log('Erreur recherche covoiturages : ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ]);
        }
    }



    /*******************************
     * Recherche via formulaire classique
     *******************************/
    public function rechercherCovoiturages()
    {
        $villeDepart  = trim($_POST['ville_depart'] ?? '');
        $villeArrivee = trim($_POST['ville_arrivee'] ?? '');
        $dateDepart   = $_POST['date_depart'] ?? null;

        $resultats = [];
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($villeDepart) && empty($villeArrivee)) {
                $error = "❌ Veuillez entrer au moins une ville de départ ou d’arrivée.";
            } else {
                $resultats = $this->rechercherCovoituragesSouples($villeDepart, $villeArrivee, $dateDepart);
                if (empty($resultats)) {
                    $error = "😕 Aucun covoiturage trouvé pour ces critères.";
                }
            }
        }

        require_once __DIR__ . '/../View/partials/formulaire_recherche_covoiturages.php';
    }

    public function rechercheLarge(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $ville = trim($_POST['ville'] ?? '');

        if (empty($ville)) {
            echo json_encode([
                'success' => false,
                'message' => 'Veuillez saisir le nom d’une ville.'
            ]);
            return;
        }

        try {
            $covoiturages = $this->repo->rechercherCovoituragesParVille($ville);

            if (empty($covoiturages)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Aucun covoiturage trouvé pour cette ville.'
                ]);
                return;
            }

            echo json_encode([
                'success' => true,
                'count'   => count($covoiturages),
                'data'    => $covoiturages
            ]);

        } catch (\Throwable $e) {
            error_log('Erreur rechercheLarge covoiturages : ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ]);
        }
    }


    /*******************************
     * Autocompletion des villes
     *******************************/
    public function autocompleteVilles(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $term = trim($_GET['term'] ?? '');
        if ($term === '') {
            echo json_encode([]);
            return;
        }

        try {
            $stmt = $this->conn->prepare(
                "SELECT nom_ville 
                 FROM villes 
                 WHERE LOWER(nom_ville) LIKE LOWER(:term)
                 ORDER BY nom_ville ASC 
                 LIMIT 10"
            );
            $stmt->execute([':term' => $term . '%']);
            $villes = $stmt->fetchAll(PDO::FETCH_COLUMN);

            echo json_encode(is_array($villes) ? $villes : []);
        } catch (\PDOException $e) {
            error_log('autocompleteVilles error: ' . $e->getMessage());
            echo json_encode([]);
        }
        exit;
    }

    /************************************
     * Récupérer tous les covoiturages
     ************************************/
    public function getCovoituragesByUtilisateur(int $userId): array
    {
        return $this->repo->getCovoituragesByUtilisateur($userId);
    }

    /************************************
     * Filtrer covoiturages écologiques
     ************************************/
    public function listeCovoituragesByEcologique(): void
    {
        $ecologique = isset($_GET['ecologique']) ? (int)$_GET['ecologique'] : 1;
        $covoiturages = $this->repo->filterByEcologique($ecologique);
        require_once __DIR__ . '/../View/covoiturages/liste_covoiturages.php';
    }

    public function resultatsRecherche(): void
    {
        $covoiturages = $this->repo->getAllCovoiturages();

        require_once __DIR__ . '/../View/partials/resultats_covoiturages.php';
    }

    public function showDetails()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            $covoiturage = null;
        } else {
            $covoiturage = $this->repo->getCovoiturageByUtilisateur($id);
        }

        if (!$covoiturage) {
            echo "<p class='text-center text-danger'>Covoiturage introuvable.</p>";
            return;
        }

        require_once __DIR__ . '/../View/covoiturages/detail_covoiturage.php';
    }

    // Réserver un covoiturage
    public function reserverCovoiturage(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION['user_id'] ?? null;
        $idCovoiturage = (int) ($_POST['id_covoiturage'] ?? 0);

        if (!$userId || $idCovoiturage <= 0) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté ou covoiturage invalide.']);
            return;
        }

        try {
            $covoiturage = $this->repo->getCovoiturageByUtilisateur($idCovoiturage);
            if (!$covoiturage) {
                echo json_encode(['success' => false, 'message' => 'Covoiturage introuvable.']);
                return;
            }

            // Mettre à jour le statut en "réservé"
            $this->repo->updateStatutCovoiturage($idCovoiturage, 'réservé');

            echo json_encode(['success' => true, 'message' => 'Covoiturage réservé avec succès !']);

        } catch (\Throwable $e) {
            error_log('Erreur réservation covoiturage : ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
        }
    }








}
