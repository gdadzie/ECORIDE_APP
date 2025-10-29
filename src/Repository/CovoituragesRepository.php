<?php

namespace Repository;

use Entity\Covoiturage;
use Config\Database;
use PDO;
use PDOException;

class CovoituragesRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // 🟢 Créer un nouveau covoiturage
    public function create(Covoiturage $covoiturage): bool
    {
        try {
            $stmt = $this->conn->prepare('
                INSERT INTO covoiturages 
                (id_utilisateur, id_vehicule, ville_depart, ville_arrivee, date_depart,
                 heure_depart, distance_km,  nb_places, ecologique, statut)
                VALUES 
                (:id_utilisateur, :id_vehicule, :ville_depart, :ville_arrivee, :date_depart,
                 :heure_depart, :distance_km,  :nb_places, :ecologique, :statut)
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

        } catch (PDOException $e) {
            error_log('Erreur lors de la création du covoiturage : ' . $e->getMessage());
            return false;
        }
    }

    // 🟢 Récupérer un covoiturage par ID
    public function getCovoiturageById(int $id_covoiturage): ?Covoiturage
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM covoiturages WHERE id_covoiturage = :id');
            $stmt->execute([':id' => $id_covoiturage]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) return null;

            $covoiturage = new Covoiturage(
                (int)$data['id_utilisateur'],
                (int)$data['id_vehicule'],
                (int)$data['ville_depart'],
                (int)$data['ville_arrivee'],
                (string)$data['date_depart'],
                (string)$data['heure_depart'],
                (float)$data['distance_km'],
                (int)$data['nb_places'],
                (bool)$data['ecologique'],
                (string)$data['statut']
            );

            $covoiturage->setIdCovoiturage((int)$data['id_covoiturage']);
            return $covoiturage;

        } catch (PDOException $e) {
            error_log('Erreur PDO : ' . $e->getMessage());
            return null;
        }
    }

    // 🟢 Récupérer les covoiturages d’une ville et d’une date
    public function getAllCovoiturages(string $ville_depart, string $date_depart): array
    {
        if (empty($ville_depart) || empty($date_depart)) return [];

        $stmt = $this->conn->prepare('
            SELECT * FROM covoiturages 
            WHERE ville_depart = :ville AND date_depart = :date
        ');
        $stmt->execute([
            ':ville' => $ville_depart,
            ':date' => $date_depart
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🟢 Modifier un covoiturage
    public function updateCovoiturage(
        int $id_covoiturage,
        int $ville_depart,
        int $ville_arrivee,
        string $date_depart,
        string $heure_depart,
        int $nb_places,
        bool $ecologique
    ): bool {
        try {
            $stmt = $this->conn->prepare('
                UPDATE covoiturages SET 
                    ville_depart = :ville_depart,
                    ville_arrivee = :ville_arrivee,
                    date_depart = :date_depart,
                    heure_depart = :heure_depart,
                    nb_places = :nb_places,
                    ecologique = :ecologique
                WHERE id_covoiturage = :id
            ');

            return $stmt->execute([
                ':ville_depart' => $ville_depart,
                ':ville_arrivee' => $ville_arrivee,
                ':date_depart' => $date_depart,
                ':heure_depart' => $heure_depart,
                ':nb_places' => $nb_places,
                ':ecologique' => $ecologique,
                ':id' => $id_covoiturage
            ]);

        } catch (PDOException $e) {
            error_log('Erreur lors de la modification du covoiturage : ' . $e->getMessage());
            return false;
        }
    }

    // 🟢 Supprimer un covoiturage
    public function deleteCovoiturage(int $id_covoiturage): bool
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM covoiturages WHERE id_covoiturage = :id');
            return $stmt->execute([':id' => $id_covoiturage]);
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression du covoiturage : ' . $e->getMessage());
            return false;
        }
    }
}
