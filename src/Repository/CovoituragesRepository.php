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
                (id_utilisateur, id_vehicule, ville_depart, ville_arrivee, date_depart, date_arrivee, 
                 heure_depart, heure_arrivee, prix, nb_places, ecologique, statut)
                VALUES 
                (:id_utilisateur, :id_vehicule, :ville_depart, :ville_arrivee, :date_depart, :date_arrivee, 
                 :heure_depart, :heure_arrivee, :prix, :nb_places, :ecologique, :statut)
            ');

            return $stmt->execute([
                ':id_utilisateur' => $covoiturage->getIdUtilisateur(),
                ':id_vehicule' => $covoiturage->getIdVehicule(),
                ':ville_depart' => $covoiturage->getVilleDepart(),
                ':ville_arrivee' => $covoiturage->getVilleArrivee(),
                ':date_depart' => $covoiturage->getDateDepart(),
                ':date_arrivee' => $covoiturage->getDateArrivee(),
                ':heure_depart' => $covoiturage->getHeureDepart(),
                ':heure_arrivee' => $covoiturage->getHeureArrivee(),
                ':prix' => $covoiturage->getPrix(),
                ':nb_places' => $covoiturage->getNbPlaces(),
                ':ecologique' => $covoiturage->getEcologique(),
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

            // ⚙️ Instanciation du covoiturage avec tous les paramètres requis
            $covoiturage = new Covoiturage(
                (int)$data['id_utilisateur'],
                (int)$data['id_vehicule'],
                (string)$data['ville_depart'],
                (string)$data['ville_arrivee'],
                (string)$data['date_depart'],
                (string)$data['date_arrivee'],
                (string)$data['heure_depart'],
                (string)$data['heure_arrivee'],
                (float)$data['prix'],
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

    // 🟢 Récupérer tous les covoiturages
    public function getAllCovoiturages(): array
    {
        $stmt = $this->conn->query('SELECT * FROM covoiturages');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🟢 Rechercher un covoiturage par ville et date
    public function getCovoiturageByVilleAndDateDepart(string $ville_depart, string $date_depart): array
    {
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
        string $ville_depart,
        string $ville_arrivee,
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

    // 🟢 Décrémenter le nombre de places disponibles
    public function decrementNbPlaces(int $id_covoiturage): bool
    {
        try {
            $stmtCheck = $this->conn->prepare('SELECT nb_places FROM covoiturages WHERE id_covoiturage = :id');
            $stmtCheck->execute([':id' => $id_covoiturage]);
            $covoit = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$covoit || (int)$covoit['nb_places'] <= 0) {
                return false;
            }

            $stmtUpdate = $this->conn->prepare('
                UPDATE covoiturages 
                SET nb_places = nb_places - 1 
                WHERE id_covoiturage = :id
            ');
            return $stmtUpdate->execute([':id' => $id_covoiturage]);

        } catch (PDOException $e) {
            error_log('Erreur lors de la décrémentation des places : ' . $e->getMessage());
            return false;
        }
    }

    // 🟢 Annuler un covoiturage
    public function annulerCovoiturage(int $id_covoiturage, int $id_utilisateur): bool
    {
        try {
            $stmt = $this->conn->prepare('
                UPDATE covoiturages
                SET statut = :statut
                WHERE id_covoiturage = :id_covoiturage AND id_utilisateur = :id_utilisateur
            ');

            $stmt->execute([
                ':statut' => 'annulé',
                ':id_covoiturage' => $id_covoiturage,
                ':id_utilisateur' => $id_utilisateur
            ]);

            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log('Erreur lors de l\'annulation du covoiturage : ' . $e->getMessage());
            return false;
        }
    }
}
