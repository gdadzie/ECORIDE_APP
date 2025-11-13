<?php

namespace Repository;

use Config\Database;
use PDO;
use PDOException;

class CovoituragesRepository
{
    private ?PDO $conn = null;
    private ?string $lastError = null;

    public function __construct()
    {
        $this->conn = Database::getConnection();
        if (!$this->conn) {
            $this->lastError = Database::getLastError() ?? "Connexion à la base de données impossible.";
            throw new \RuntimeException($this->lastError);
        }
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * Créer un covoiturage (attend une Entity\Covoiturage)
     */
    public function create($covoiturage): bool
    {
        try {
            $sql = "INSERT INTO covoiturages 
                (id_utilisateur, id_vehicule, ville_depart, ville_arrivee, date_depart, heure_depart, distance_km, prix, nb_places, ecologique, duree_minutes, statut)
                VALUES (:id_utilisateur, :id_vehicule, :ville_depart, :ville_arrivee, :date_depart, :heure_depart, :distance_km, :prix, :nb_places, :ecologique, :duree_minutes, :statut)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_utilisateur' => $covoiturage->getIdUtilisateur(),
                ':id_vehicule' => $covoiturage->getIdVehicule(),
                ':ville_depart' => $covoiturage->getVilleDepart(),
                ':ville_arrivee' => $covoiturage->getVilleArrivee(),
                ':date_depart' => $covoiturage->getDateDepart(),
                ':heure_depart' => $covoiturage->getHeureDepart(),
                ':distance_km' => $covoiturage->getDistanceKm(),
                ':prix' => $covoiturage->getPrix(),
                ':nb_places' => $covoiturage->getNbPlaces(),
                ':ecologique' => $covoiturage->isEcologique() ? 1 : 0,
                ':duree_minutes' => $covoiturage->getDureeMinutes(),
                ':statut' => $covoiturage->getStatut()
            ]);
            $covoiturageId = (int)$this->conn->lastInsertId();
            $covoiturage->setIdCovoiturage($covoiturageId);
            return true;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log('CovoituragesRepository::create error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer tous les covoiturages (avec noms de villes)
     */
    public function getAllCovoiturages(): array
    {
        try {
            $stmt = $this->conn->query("
                SELECT c.*, vd.nom_ville AS ville_depart_nom, va.nom_ville AS ville_arrivee_nom
                FROM covoiturages c
                JOIN villes vd ON c.ville_depart = vd.id_ville
                JOIN villes va ON c.ville_arrivee = va.id_ville
                ORDER BY c.date_depart ASC, c.heure_depart ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur getAllCovoiturages : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Recherche souple par ville de départ / arrivée et date optionnelle
     */
    public function rechercherCovoituragesSouples(string $villeDepart = '', string $villeArrivee = '', ?string $dateDepart = null): array
    {
        $sql = "
            SELECT c.*, 
                   vd.nom_ville AS ville_depart_nom, 
                   va.nom_ville AS ville_arrivee_nom
            FROM covoiturages c
            JOIN villes vd ON c.ville_depart = vd.id_ville
            JOIN villes va ON c.ville_arrivee = va.id_ville
            WHERE 1=1
        ";

        $params = [];
        if ($villeDepart !== '') {
            $sql .= " AND LOWER(vd.nom_ville) LIKE :villeDepart";
            $params['villeDepart'] = '%' . strtolower($villeDepart) . '%';
        }
        if ($villeArrivee !== '') {
            $sql .= " AND LOWER(va.nom_ville) LIKE :villeArrivee";
            $params['villeArrivee'] = '%' . strtolower($villeArrivee) . '%';
        }
        if ($dateDepart !== '' && $dateDepart !== null) {
            $sql .= " AND c.date_depart = :dateDepart";
            $params['dateDepart'] = $dateDepart;
        }

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur rechercherCovoituragesSouples : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer covoiturage par id
     */
    public function getCovoiturageById(int $id): ?array
    {
        try {
            $stmt = $this->conn->prepare("
            SELECT c.*, 
                   vd.nom_ville AS ville_depart_nom, 
                   va.nom_ville AS ville_arrivee_nom,
                   u.pseudo
            FROM covoiturages c
            JOIN villes vd ON c.ville_depart = vd.id_ville
            JOIN villes va ON c.ville_arrivee = va.id_ville
            JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
            WHERE c.id_covoiturage = :id
        ");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log('Erreur getCovoiturageById : ' . $e->getMessage());
            return null;
        }
    }


    /**
     * Récupérer covoiturages par utilisateur
     */
    public function getCovoituragesByUtilisateur(int $userId): array
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.*, vd.nom_ville AS ville_depart_nom, va.nom_ville AS ville_arrivee_nom
                FROM covoiturages c
                JOIN villes vd ON c.ville_depart = vd.id_ville
                JOIN villes va ON c.ville_arrivee = va.id_ville
                WHERE c.id_utilisateur = :userId
                ORDER BY c.date_depart DESC
            ");
            $stmt->execute([':userId' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur getCovoituragesByUtilisateur: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Filtrer par covoiturages écologiques (ecologique = 1 ou 0)
     */
    public function filterByEcologique(int $ecologique = 1): array
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.*, vd.nom_ville AS ville_depart_nom, va.nom_ville AS ville_arrivee_nom
                FROM covoiturages c
                JOIN villes vd ON c.ville_depart = vd.id_ville
                JOIN villes va ON c.ville_arrivee = va.id_ville
                WHERE c.ecologique = :eco
                ORDER BY c.date_depart ASC
            ");
            $stmt->execute([':eco' => $ecologique]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('filterByEcologique error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Mettre à jour le statut d'un covoiturage
     */
    public function updateStatutCovoiturage(int $id, string $statut): bool
    {
        try {
            $stmt = $this->conn->prepare("UPDATE covoiturages SET statut = :statut WHERE id_covoiturage = :id");
            $stmt->execute([':statut' => $statut, ':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log('updateStatutCovoiturage error: ' . $e->getMessage());
            return false;
        }
    }

    public function rechercherCovoituragesParVilles(string $villeDepart, string $villeArrivee): array
    {
        $sql = "
        SELECT c.*,
               vd.nom_ville AS ville_depart_nom,
               va.nom_ville AS ville_arrivee_nom,
               u.pseudo
        FROM covoiturages c
        JOIN villes vd ON c.ville_depart = vd.id_ville
        JOIN villes va ON c.ville_arrivee = va.id_ville
        JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
        WHERE 1=1
          AND c.nb_places > 0
    ";

        $params = [];

        if ($villeDepart !== '') {
            $sql .= " AND vd.nom_ville LIKE :ville_depart";
            $params[':ville_depart'] = '%' . $villeDepart . '%';
        }

        if ($villeArrivee !== '') {
            $sql .= " AND va.nom_ville LIKE :ville_arrivee";
            $params[':ville_arrivee'] = '%' . $villeArrivee . '%';
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rechercherCovoituragesParVilleUnique(string $ville): array
    {
        $sql = "
        SELECT c.*,
               vd.nom_ville AS ville_depart_nom,
               va.nom_ville AS ville_arrivee_nom,
               u.pseudo
        FROM covoiturages c
        JOIN villes vd ON c.ville_depart = vd.id_ville
        JOIN villes va ON c.ville_arrivee = va.id_ville
        JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
        WHERE vd.nom_ville LIKE :ville OR va.nom_ville LIKE :ville
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':ville' => '%' . $ville . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}
