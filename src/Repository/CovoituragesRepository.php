<?php
namespace Repository;

use Entity\Covoiturage;
use Config\Database;
use PDO;
use PDOException;

class CovoituragesRepository
{
    private PDO $conn;
    private ?string $lastError = null;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Créer un covoiturage
    public function create(Covoiturage $covoiturage): bool
    {
        try {
            $stmt = $this->conn->prepare('
                INSERT INTO covoiturages 
                (id_utilisateur, id_vehicule, ville_depart, ville_arrivee, date_depart,
                 heure_depart, distance_km, nb_places, ecologique, statut)
                VALUES 
                (:id_utilisateur, :id_vehicule, :ville_depart, :ville_arrivee, :date_depart,
                 :heure_depart, :distance_km, :nb_places, :ecologique, :statut)
            ');

            return $stmt->execute([
                ':id_utilisateur' => $covoiturage->getIdUtilisateur(),
                ':id_vehicule' => $covoiturage->getIdVehicule(),
                ':ville_depart' => $covoiturage->getVilleDepart(),
                ':ville_arrivee' => $covoiturage->getVilleArrivee(),
                ':date_depart' => $covoiturage->getDateDepart(),
                ':heure_depart' => $covoiturage->getHeureDepart(),
                ':distance_km' => $covoiturage->getDistanceKm(),
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
}
