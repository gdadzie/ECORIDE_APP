<?php
namespace Repository;

use PDO;
use PDOException;
use Config\Database;
use Entity\Vehicule;

class VehiculesRepository
{
    private PDO $conn;

    public function __construct(PDO $conn = null)
    {
        if ($conn) {
            $this->conn = $conn;
        } else {
            $db = new Database();
            $this->conn = $db->getConnection();
        }
    }

    // Récupérer toutes les marques
    public function getAllMarques(): array
    {
        try {
            $stmt = $this->conn->query("SELECT id_marque, nom_marque FROM marques ORDER BY nom_marque ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getAllMarques: " . $e->getMessage());
            return [];
        }
    }

    // Ajouter un véhicule
    public function addVehicule(array $data): bool
    {
        try {
            $sql = "INSERT INTO vehicules (
                        id_utilisateur, id_marque, modele, couleur, energie, immatriculation, nb_places, date_premiere_immatriculation
                    ) VALUES (
                        :id_utilisateur, :id_marque, :modele, :couleur, :energie, :immatriculation, :nb_places, :date_premiere_immatriculation
                    )";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id_utilisateur' => (int)($data['id_utilisateur'] ?? 0),
                ':id_marque' => (int)($data['id_marque'] ?? 0),
                ':modele' => $data['modele'] ?? null,
                ':couleur' => $data['couleur'] ?? null,
                ':energie' => $data['energie'] ?? null,
                ':immatriculation' => $data['immatriculation'] ?? null,
                ':nb_places' => isset($data['nb_places']) ? (int)$data['nb_places'] : null,
                ':date_premiere_immatriculation' => $data['date_premiere_immatriculation'] ?? null
            ]);
        } catch (PDOException $e) {
            error_log("Erreur addVehicule: " . $e->getMessage());
            return false;
        }
    }

    // Récupérer les véhicules d’un utilisateur
    public function getVehiculesByUtilisateur(int $userId): array
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT v.*, m.nom_marque
                FROM vehicules v
                INNER JOIN marques m ON v.id_marque = m.id_marque
                WHERE v.id_utilisateur = :id_utilisateur
                ORDER BY m.nom_marque, v.modele ASC
            ");
            $stmt->execute([':id_utilisateur' => $userId]);

            $vehicules = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $vehicule = new Vehicule(
                    (int)($row['id_utilisateur'] ?? 0),
                    (int)($row['id_marque'] ?? 0),
                    $row['modele'] ?? null,
                    $row['couleur'] ?? null,
                    $row['energie'] ?? null,
                    $row['immatriculation'] ?? null,
                    $row['date_premiere_immatriculation'] ?? null,
                    isset($row['nb_places']) ? (int)$row['nb_places'] : null
                );
                $vehicule->setIdVehicule((int)($row['id_vehicule'] ?? 0));
                $vehicule->setNomMarque($row['nom_marque'] ?? null);
                $vehicules[] = $vehicule;
            }

            return $vehicules;
        } catch (PDOException $e) {
            error_log("Erreur getVehiculesByUtilisateur: " . $e->getMessage());
            return [];
        }
    }

    // Supprimer un véhicule (si l’utilisateur est propriétaire)
    public function deleteVehiculeByUtilisateur(int $idVehicule, int $idUtilisateur): bool
    {
        try {
            $stmt = $this->conn->prepare("
                DELETE FROM vehicules
                WHERE id_vehicule = :id_vehicule AND id_utilisateur = :id_utilisateur
            ");
            return $stmt->execute([
                ':id_vehicule' => $idVehicule,
                ':id_utilisateur' => $idUtilisateur
            ]);
        } catch (PDOException $e) {
            error_log("Erreur suppression véhicule : " . $e->getMessage());
            return false;
        }
    }


}
