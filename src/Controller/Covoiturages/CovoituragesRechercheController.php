<?php
namespace Controller\Covoiturages;

use Config\Database;
use Repository\CovoituragesRepository;
use PDO;

class CovoituragesRechercheController
{
    private CovoituragesRepository $repo;
    private PDO $conn;

    public function __construct(CovoituragesRepository $repo)
    {
        $this->repo = $repo;
        $this->conn = Database::getConnection();
    }

    public function rechercherCovoituragesSouples(string $villeDepart, string $villeArrivee, ?string $dateDepart = null): array
    {
        return $this->repo->rechercherCovoituragesSouples($villeDepart, $villeArrivee, $dateDepart);
    }

    public function recherche(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $villeDepart  = trim($_POST['ville_depart'] ?? '');
        $villeArrivee = trim($_POST['ville_arrivee'] ?? '');
        $dateDepart   = $_POST['date_depart'] ?? null;

        if (empty($villeDepart) && empty($villeArrivee)) {
            echo json_encode(['success' => false, 'message' => 'Veuillez saisir au moins une ville.']);
            return;
        }

        $covoiturages = $this->repo->rechercherCovoituragesSouples($villeDepart, $villeArrivee, $dateDepart);
        echo json_encode([
            'success' => !empty($covoiturages),
            'data'    => $covoiturages
        ]);
    }

    public function rechercheLarge(): void
    {
        $this->recherche();
    }

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
                "SELECT nom_ville FROM villes WHERE LOWER(nom_ville) LIKE LOWER(:term) ORDER BY nom_ville ASC LIMIT 10"
            );
            $stmt->execute([':term' => $term . '%']);
            echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
        } catch (\PDOException $e) {
            echo json_encode([]);
        }
    }

    public function formRechercheCovoiturages(): void
    {
        require_once __DIR__ . '/../../View/accueil/index2.php';
    }

    public function resultatsRecherche(): void
    {
        // Récupération sécurisée des paramètres GET
        $depart      = $_GET['ville_depart'] ?? null;
        $arrivee     = $_GET['ville_arrivee'] ?? null;
        $dateDepart  = $_GET['date_depart'] ?? null;

        // Vérifier si au moins un filtre est présent
        if ($depart || $arrivee || $dateDepart) {
            // Appel de la fonction flexible avec les valeurs fournies
            $covoiturages = $this->repo->findCovoiturageByDepartArriveeDate($depart, $arrivee, $dateDepart);
        } else {
            // Aucun filtre fourni : résultat vide
            $covoiturages = [];
        }

        // Inclusion de la vue
        require __DIR__ . '/../../View/accueil/resultats_recherches_covoiturages.php';
    }

}
