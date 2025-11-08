<?php
namespace Repository;

use Entity\Covoiturage;
use Config\Database;
use PDO;
use PDOException;

class CovoituragesRepository
{
    private ?PDO $conn =null ;

    private ?string $lastError = null;


    public function __construct()
    {
        $this->conn = Database::getConnection();

        if (!$this->conn) {
            $error = Database::getLastError() ?? "Connexion à la base de données impossible.";
            throw new \RuntimeException($error);
        }
    }


    // Créer un covoiturage
    public function create(Covoiturage $covoiturage): bool
    {
        try {
            $stmt = $this->conn->prepare('
                INSERT INTO covoiturages 
                (id_utilisateur, id_vehicule, ville_depart, ville_arrivee, date_depart,
                 heure_depart, distance_km,prix, nb_places, ecologique, statut)
                VALUES 
                (:id_utilisateur, :id_vehicule, :ville_depart, :ville_arrivee, :date_depart,
                 :heure_depart, :distance_km,:prix, :nb_places, :ecologique, :statut)
            ');

            return $stmt->execute([
                ':id_utilisateur' => $covoiturage->getIdUtilisateur(),
                ':id_vehicule' => $covoiturage->getIdVehicule(),
                ':ville_depart' => $covoiturage->getVilleDepart(),
                ':ville_arrivee' => $covoiturage->getVilleArrivee(),
                ':date_depart' => $covoiturage->getDateDepart(),
                ':heure_depart' => $covoiturage->getHeureDepart(),
                ':distance_km' => $covoiturage->getDistanceKm(),
                ':prix' => $covoiturage->getPrix(),
                ':nb_places' => $covoiturage->getNbPlaces(),
                ':ecologique' => $covoiturage->isEcologique(),
                ':statut' => $covoiturage->getStatut(),
            ]);

        } catch (\PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log('Erreur lors de la création du covoiturage : ' . $e->getMessage());
            return false;
        }
    }

    // Récupérer covoiturages par utilisateur
    public function getCovoituragesByUtilisateur(int $userId): array
    {
        try {
            $stmt = $this->conn->prepare('
                SELECT * FROM covoiturages 
                WHERE id_utilisateur = :user_id
                ORDER BY date_depart DESC, heure_depart DESC
            ');
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur getCovoituragesByUtilisateur : ' . $e->getMessage());
            return [];
        }
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function filterByEcologique(int $ecologique): array
    {
        $stmt = $this->conn->prepare("
        SELECT *
        FROM covoiturages
        WHERE ecologique = :ecologique
    ");
        $stmt->execute(['ecologique' => $ecologique]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function filterByPrixMax(float $prixMax): array
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM covoiturages
            WHERE prix <= :prixMax
            ORDER BY prix 
        ");

        $stmt->execute([':prixMax' => $prixMax]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filterByDureeMax(int $dureeMax): array
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM covoiturages
            WHERE duree_minutes <= :dureeMax
            ORDER BY covoiturages.duree_minutes 
        ");

        $stmt->execute([':dureeMax' => $dureeMax]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}